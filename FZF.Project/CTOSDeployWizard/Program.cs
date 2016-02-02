using CM.TOS.V4.Common;
using CM.TOS.V4.Common.Component;
using CM.TOS.V4.Common.Utility;
using CSScriptLibrary;
using CTOSDeployWizard.Script;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Configuration;
using System.Diagnostics;
using System.DirectoryServices;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Xml;
using System.Reflection;
using Oracle.ManagedDataAccess.Client;

namespace CTOSDeployWizard
{
    public class Program
    {
        static string tosconfigname = "TOSConfig.config";
        static string tosconfig = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "Config") + "\\" + tosconfigname;
        static void Main(string[] args)
        {
            Program p = new Program();

            var wsconfig = DeployConfiguration.GetWsConfig();

            //删除，并用采用DeployConfiguration 中的设置。
            string ip = IISWorker.GetIp();

            if (!File.Exists(tosconfig))
            {               
                LocalConfigService.GetConfig<TOSConfig>(new TOSConfig
                {
                    LocalLoggingServiceLevel = LogLevel.Debug,
                    ClearLocalLogWhenStart = true,
                    LogToDb = true,
                    ApplicationName = "SERVER",
                    TOSDataServiceAddress ="net.tcp://"+ ip+":9900/RealtimeService",
                    MongodbServiceAddress = string.IsNullOrEmpty(wsconfig.LogServerSite) ? ("net.tcp://" + ip + ":17777/MongodbService") : string.Format("http://{0}:{1}/LogService.svc", ip, wsconfig.LogServerSitePort),
                    SSOServiceAddress = string.IsNullOrEmpty(wsconfig.SSOServerSite) ? ("net.tcp://" + ip + ":17778/LoginService") : string.Format("http://{0}:{1}/SSOService.svc", ip, wsconfig.SSOServerSitePort),                    
                    MongodbAddress = string.Format("mongodb://{0}:11212",ip)
                });
                LocalLoggingService.Info("请修改Config目录下的配置文件");




                
                


                Thread.Sleep(2000);
                return;
            }

			////检查站点配置文件是否存在
			//if (!CheckConfig(new string[] { wsconfig.LogServerSitePath, wsconfig.SSOServerSitePath, wsconfig.RpcPhysicalPath, wsconfig.DISServerSitePath, wsconfig.DISAPIServerSitePath, wsconfig.DISInterAPIServerSitePath, wsconfig.EdiServerSitePath }, "web.config"))
			//{
			//	Console.ReadLine();
			//	return;
			//}

			////检查前台服务配置文件是否存在
			//if (!CheckConfig(new string[] { wsconfig.SubscribeServerPath, wsconfig.RealtimeServerPath }, "config.ini"))
			//{
			//	Console.ReadLine();
			//	return;
			//}

			////检查前台缓存服务配置文件是否存在
			//if (!CheckConfig(new string[] { wsconfig.CacheServerPath }, wsconfig.CacheServerExe + ".config"))
			//{
			//	Console.ReadLine();
			//	return;
			//}

            

            TOSFramework.Start();

            //检查数据库连接            
            OracleConnection oraconn =null;
            try
            {
                oraconn = new OracleConnection(wsconfig.OracleConnection);
                oraconn.Open();
                oraconn.Close();
                oraconn.Dispose();
            }
            catch (Exception ex)
            {
                LocalLoggingService.Error("请检查数据库连接: " + ex.Message);
                Console.Read();
                return;
            }
            finally
            {
                LocalLoggingService.Info("检查数据库连接成功: " + wsconfig.OracleConnection);
            }
            
            //检查.net版本
            if (DotnetFrameworkChecker.Get45or451FromRegistry().StartsWith("No"))
            {
                LocalLoggingService.Error("请安装.Net框架v4.5+");
                Console.Read();
                return;
            }
            LocalLoggingService.Info("检查.net框架版本成功");



            //检查IIS版本
            var iisver = IISWorker.GetIIsVersion();
            int iisverint = 0;
            if (int.TryParse(iisver, out iisverint) && iisverint >= 7)
            {
                LocalLoggingService.Info("检查IIS版本成功: " + iisver);
            }
            else
            {
                LocalLoggingService.Warning("IIS 版本不符合要求，至少7.5以上.");
                Console.Read();
                return;
            }

			//启动IIS版本
			ProcessStartInfo psi = new ProcessStartInfo(Environment.GetFolderPath(Environment.SpecialFolder.System) + @"\iisreset.exe");
			psi.Arguments =  "/start";
			Process.Start(psi);
			LocalLoggingService.Info("IIS启动成功" );
			


			


			var css = Directory.GetFiles(Path.Combine(Environment.CurrentDirectory, "Script"), "*.cs");
			foreach (string cs in css)
			{
				try
				{
					//CSScript.Compile(cs);
					string refAssembly = Path.GetFullPath("CM.TOS.V4.Common.dll");

					AsmHelper helper = new AsmHelper(CSScript.Load(cs));
					//the only reflection based call 
					IConfigEdit proc = (IConfigEdit)helper.CreateAndAlignToInterface<IConfigEdit>("*");
					if (proc.modify(wsconfig))
					{
						LocalLoggingService.Warning("脚本成功执行：{0}", proc.Name);
					}
					else
					{
						LocalLoggingService.Error("脚本未成功执行：{0} ", proc.Name);
						Console.WriteLine("Press Enter to Continue");
						Console.ReadLine();
					}
				}
				catch (Exception ex)
				{
					LocalLoggingService.Error("脚本{0}执行失败: {1}", cs, ex.Message);
				}
			}
			
 

            LocalLoggingService.Info("发布完成！");



            
            //检查缓存服务

            Console.Write("是否启动服务程序？回车确认：");
            var keyinfo = Console.ReadKey();
            if (keyinfo.Key == ConsoleKey.Enter)
            {
                var serverinfo = new System.Collections.Generic.Dictionary<string, string>();


                     //serverinfo.Add(wsconfig.LogServerPath, wsconfig.LogServerExe);
                     //serverinfo.Add(wsconfig.SSOServerPath , wsconfig.SSOServerExe);
                     serverinfo.Add(wsconfig.RealtimeServerPath,wsconfig.RealtimeServerExe);
                     serverinfo.Add(wsconfig.CacheServerPath , wsconfig.CacheServerExe);                   
                     serverinfo.Add(wsconfig.SubscribeServerPath,wsconfig.SubscribeServerExe)    ;


                string batname = "DebugRegSystem.bat";
                foreach (var dir in serverinfo.Keys)
                {
                    string e = Path.Combine(dir, serverinfo[dir]);
#if DEBUG
                    LocalLoggingService.Info(e);   
#endif
                    
                    if (!File.Exists(e)) continue;

                    string batfullname = null;

                    if (e.EndsWith(wsconfig.SubscribeServerExe) || e.EndsWith(wsconfig.RealtimeServerExe) )
                    {
                         batfullname = Path.Combine(dir, batname);
                    }
                    if (File.Exists(batfullname))
                    {
                        LocalLoggingService.Info("注册服务");
                        Process.Start(batfullname);
                        Thread.Sleep(1500);
                    }

                   
                    bool isExist = false;
                    var procname = serverinfo[dir].Substring(0, serverinfo[dir].Length - 4);
                    foreach (var ps in Process.GetProcessesByName(procname))
                    {
                        isExist = true;
                        LocalLoggingService.Info("服务{0}已存在,", procname);

                        Console.Write("是否关闭服务程序重启？回车确认：");
                        var input = Console.ReadKey();
                        if (input.Key == ConsoleKey.Enter) { break; }                       
                        else
                        {                            
                            isExist = false;
                            ps.Kill();
                        }
                    }

                    if (isExist) continue;


                    LocalLoggingService.Info("启动服务：{0}", e);
                    
                    Process.Start(e);
                    if (e.EndsWith(wsconfig.CacheServerExe))
                    {
                        LocalLoggingService.Info("等候120s后启动下一个服务！");
                        Thread.Sleep(120 * 1000);
                    }
                }
            }

            TOSFramework.End();
        }

        public static bool CheckConfig(string[] paths,string filename)
        {
            bool ret = true;
            paths.ToList().ForEach( p => 
                {
                    string webpath = p + "\\" + filename;
                     if ( !File.Exists( webpath ) )
                     {
                         ret = false;
                         LocalLoggingService.Warning("配置文件不存在："+ webpath);
                     }
                });
            return ret;
        }

        public static void copyconfigfile( string configPath,string msg)
        {
            if (string.IsNullOrEmpty(configPath)) return;
            if (!Directory.Exists(configPath))
            {
                try
                {
                    Directory.CreateDirectory(configPath);
                }
                catch (Exception ex)
                {
                    LocalLoggingService.Error(ex.Message);
                }
            }
            string logtosconfig = Path.Combine(configPath, tosconfigname);
            File.Copy(tosconfig, logtosconfig, true);
            LocalLoggingService.Info("{0}：TOSConfig", msg);
        }
		
        public static bool CreateWs(string path,string name,int port,bool ispool)
        {
            if (string.IsNullOrEmpty(path) || string.IsNullOrEmpty(name)) return false;
            
            IISWorker.SetFileRole(path);
            LocalLoggingService.Info("设置文件夹[{0}]权限", path);
            string siteid = IISWorker.IsExist(name);
            if (!string.IsNullOrEmpty(siteid))
            {
                if (!DeployConfiguration.GetWsConfig().IsReCreateAppPool)
                {
                    LocalLoggingService.Info("站点{0}已存在：[{1}]", name, siteid);
                    return true;
                }
                if (IISWorker.DelSite(siteid))
                {
                    LocalLoggingService.Info("删除站点：{0}", name);

                }
                else
                {
                    return false;
                }
            }
            int newsiteid = IISWorker.CreateWebSite(name, path, port, ispool);
            LocalLoggingService.Info("成功创建站点 :{0}[{1}]", name, newsiteid);
            return true;
        }

        
    }
}
