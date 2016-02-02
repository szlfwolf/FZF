
using CM.TOS.V4.Wcf;
using CMPORT.Workspace.ServiceContract;

using CM.TOS.V4.Common;
using CM.TOS.V4.Common.Utility;
using CM.TOS.V4.Common.Component;
using CTOSDeployWizard;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;


namespace CTOSDeployWizard.Script
{
	class ssoconfigedit : IConfigEdit
	{
		public string Name
		{
			get { return "认证服务配置"; }
		}

		public bool modify(WsConfig wsconfig)
		{
			try
			{
				//创建目录
				if (!Directory.Exists(wsconfig.LogWebSitePath)) Directory.CreateDirectory(wsconfig.LogWebSitePath);
				if (!Directory.Exists(wsconfig.LogServerSitePath)) Directory.CreateDirectory(wsconfig.LogServerSitePath);
				if (!Directory.Exists(wsconfig.SSOServerSitePath)) Directory.CreateDirectory(wsconfig.SSOServerSitePath);

				//检查sso系统环境。
				//sso-1，检查系统环境变量：
				string var_tns_admin = "TNS_ADMIN";
				var tnsadmin = Environment.GetEnvironmentVariable(var_tns_admin);
				if (string.IsNullOrEmpty(tnsadmin))
				{
					LocalLoggingService.Warning("未找到系统参数: TNS_ADMIN.");
					return false;
				}
				LocalLoggingService.Info("检查系统环境变量成功. " + tnsadmin);

				string queuepath = String.Format(".\\Private$\\{0}", wsconfig.MsmqName);
				CM.TOS.V4.Common.Utility.MsmqUtility.Createqueue(queuepath);
				LocalLoggingService.Info("创建实时消息队列: " + queuepath);

				//检查站点配置文件是否存在
				if (!Program.CheckConfig(new string[] { 
					wsconfig.SSOServerSitePath, 
					wsconfig.LogWebSitePath, 
					wsconfig.LogServerSitePath
					 }, "web.config"))
				{
					return false;
				}

				ExeConfigurationFileMap filemap = new ExeConfigurationFileMap();
				filemap.ExeConfigFilename = Path.Combine(wsconfig.SSOServerSitePath, "web.config");//配置文件路径
				var ssoconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
				ssoconfig.ConnectionStrings.ConnectionStrings["OracleDbContext"].ConnectionString = wsconfig.SSOConnection;
				ssoconfig.Save(ConfigurationSaveMode.Modified);
				LocalLoggingService.Info("更新认证服务站点[{0}]的web.config：数据库连接地址", wsconfig.SSOServerSite);

				Program.copyconfigfile(Path.Combine(wsconfig.SSOServerSitePath, "Config"), "更新" + wsconfig.SSOServerSite + "配置文件");
				Program.copyconfigfile(Path.Combine(wsconfig.LogWebSitePath, "Config"), "更新" + wsconfig.LogWebSite + "配置文件");
				Program.copyconfigfile(Path.Combine(wsconfig.LogServerSitePath, "Config"), "更新" + wsconfig.LogServerSite + "配置文件");

				Program.CreateWs(wsconfig.SSOServerSitePath, wsconfig.SSOServerSite, wsconfig.SSOServerSitePort, wsconfig.IsCreateAppPool);
				Program.CreateWs(wsconfig.LogWebSitePath, wsconfig.LogWebSite, wsconfig.LogWebSitePort, wsconfig.IsCreateAppPool);
				Program.CreateWs(wsconfig.LogServerSitePath, wsconfig.LogServerSite, wsconfig.LogServerSitePort, wsconfig.IsCreateAppPool);


				LocalLoggingService.Info("开始验证部署网站：");


				//sso-2,调用sso服务。
				try
				{
					string passwordhash = CMPORT.Workspace.SSOHelper.GenPassword(wsconfig.User, wsconfig.Password);
					string languageno = "zh-CN";
					string cip = IISWorker.GetIp();
					long errcode = 0;
					string tid = WcfService.WcfServiceLocator.GetService<ILoginService>().LoginApp(wsconfig.User, passwordhash, languageno, cip, ref errcode);
					if (string.IsNullOrEmpty(tid) || errcode > 0)
					{
						LocalLoggingService.Warning("获取tid失败，错误码：" + errcode);
						return false;
					}
					LocalLoggingService.Warning("sso登录成功，获取tid：" + tid);
				}
				catch (Exception ex)
				{
					LocalLoggingService.Error("sso登录失败：" + ex.Message);
					return false; 
				}

			}
			catch (Exception ex)
			{
				LocalLoggingService.Error(ex.Message);

				return false;
			}

			return true;
		}
	}
}
