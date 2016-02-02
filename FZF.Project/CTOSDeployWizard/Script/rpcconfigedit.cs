//css_dir ..\;
//css_ref CM.TOS.V4.Common.dll;
//css_ref CTOSDeployWizard;

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
	class rpcconfigedit : IConfigEdit
	{
		public string Name
		{
			get { return "前台配置"; }
		}
		
			
			
		public bool modify(WsConfig wsconfig)
		{
			try
			{
				if (!Directory.Exists(wsconfig.RpcPhysicalPath)) Directory.CreateDirectory(wsconfig.RpcPhysicalPath);
				if (!Directory.Exists(wsconfig.RealtimeServerPath)) Directory.CreateDirectory(wsconfig.RealtimeServerPath);
				if (!Directory.Exists(wsconfig.SubscribeServerPath)) Directory.CreateDirectory(wsconfig.SubscribeServerPath);
				if (!Directory.Exists(wsconfig.CacheServerPath)) Directory.CreateDirectory(wsconfig.CacheServerPath);

				//检查站点配置文件是否存在
				if (!Program.CheckConfig(new string[] { 
					wsconfig.RpcPhysicalPath, 
					wsconfig.LogWebSitePath, 
					wsconfig.LogServerSitePath
					 }, "web.config"))
				{
					return false;
				}

				ExeConfigurationFileMap filemap = new ExeConfigurationFileMap();
				filemap.ExeConfigFilename = Path.Combine(wsconfig.RpcPhysicalPath, "web.config");//配置文件路径
				var rpcconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
				rpcconfig.ConnectionStrings.ConnectionStrings["OracleConnection"].ConnectionString = wsconfig.OracleConnection;
				rpcconfig.AppSettings.Settings["MSMQNAME"].Value = wsconfig.MsmqName;
				rpcconfig.AppSettings.Settings["MSMQSERVERIP"].Value = IISWorker.GetIp();

				rpcconfig.AppSettings.Settings["ENVTEXT"].Value = wsconfig.EnvText;
				rpcconfig.AppSettings.Settings["ENVTEXTCOLOR"].Value = wsconfig.EnvTextColor;
				rpcconfig.AppSettings.Settings["ENVBKCOLOR"].Value = wsconfig.EnvBKColor;

				rpcconfig.Save(ConfigurationSaveMode.Modified);
				LocalLoggingService.Info("更新前台中间层站点[{0}]的web.config： 消息队列地址、数据库连接地址、环境标识", wsconfig.SiteName);

				
			
				Program.copyconfigfile(Path.Combine(wsconfig.RpcPhysicalPath, "Config"), "更新" + wsconfig.SiteName + "配置文件");

				//创建IIS站点
				Program.CreateWs(wsconfig.RpcPhysicalPath, wsconfig.SiteName, wsconfig.RpcDomainPort, wsconfig.IsCreateAppPool);

				//修改服务的配置文件
				if (!DeployServer(wsconfig))
				{
					return false;
				}

				//生成前台配置文件
				string dcip = IISWorker.GetIp();
				string rsip = dcip;
				string WsUrl = string.Format("http://{0}:{1}/Service.asmx", dcip, wsconfig.RpcDomainPort);
				string msg = string.Format(@"Windows Registry Editor Version 5.00

[HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\Chnet\CTOS\PACS1.0]
""WebService""=""{0}""
""DebugLevel""=""3""
""PORT""=""{1}""
""AUTOUPDATE""=""N""
""Timeout""=""600""
""DCIP""=""{2}""
""DCPORT""=""{3}""
""REALTIMESERVER""=""{4}""
", WsUrl, wsconfig.SubscribePubport, dcip, wsconfig.SubscribeDcport, rsip);
				using (FileStream fs = File.Create("v4.reg"))
				{
					byte[] b = Encoding.UTF8.GetBytes(msg);
					fs.Write(b, 0, b.Length);
					fs.Flush();
				}

				LocalLoggingService.Info("生成前台注册表文件：v4.reg");
			}
			catch (Exception ex)
			{
				LocalLoggingService.Error(ex.Message);
				return false;
			}

			return true;
		}


		private bool DeployServer(WsConfig wsconfig)
		{

			//1,cacheserver
			ExeConfigurationFileMap filemap = new ExeConfigurationFileMap();
			filemap.ExeConfigFilename = Path.Combine(wsconfig.CacheServerPath, wsconfig.CacheServerExe + ".config");//配置文件路径

			if (!Program.CheckConfig(new string[] { 
					wsconfig.CacheServerPath
					 }, wsconfig.CacheServerExe + ".config"))
			{
				return false;
			}

			var config = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
			config.ConnectionStrings.ConnectionStrings["CTOSV4DbContext"].ConnectionString = wsconfig.CTOSV4DbContext;
			config.Save(ConfigurationSaveMode.Modified);

			LocalLoggingService.Info("修改缓存服务配置文件[exe.config]：数据库连接地址");

			Program.copyconfigfile(Path.Combine(wsconfig.CacheServerPath, "Config"), "更新缓存服务配置");

			if (!Program.CheckConfig(new string[] { 
					wsconfig.RealtimeServerPath,
					wsconfig.SubscribeServerPath
					 }, "config.ini"))
			{
				return false;
			}

			string wsIp, realtimeIp, subscribeIp, msmqIp;
			msmqIp = wsIp = realtimeIp = subscribeIp = IISWorker.GetIp();

			string WsUrl = string.Format("http://{0}:{1}/Service.asmx", wsIp, wsconfig.RpcDomainPort);

			//读取实时配置，修改msmq / 中间层地址。            
			string serverini = wsconfig.RealtimeServerPath + "\\config.ini";
			string queuename = string.Format(@"DIRECT=TCP:{0}\private$\{1}", msmqIp, wsconfig.MsmqName);
			
			IniUtility.IniWriteValue("MONITOR_SERVER", "SERVICENAME", wsconfig.MsmqName, serverini);
			IniUtility.IniWriteValue("MONITOR_SERVER", "PORT", wsconfig.RealtimeServerPort, serverini);
			IniUtility.IniWriteValue("MONITOR_SERVER", "LOCALADDRESS", realtimeIp, serverini);
			IniUtility.IniWriteValue("MSMQ_SERVER", "QUEUENAME", queuename, serverini);
			IniUtility.IniWriteValue("MSMQ_SERVER", "SENDMSMQ", queuename, serverini);
			IniUtility.IniWriteValue("WEBSERVICE", "URL", WsUrl, serverini);

			LocalLoggingService.Info("修改实时服务配置文件[config.ini]：前台中间层地址、消息队列地址");


			//读取订阅配置。修改。
			string cacheServerFullAddress = string.Format(@"{0}", CommonConfiguration.GetConfig().TOSDataServiceAddress);
			string logServerFullAddress = CommonConfiguration.GetConfig().MongodbServiceAddress;
			string subscribeini = wsconfig.SubscribeServerPath + "\\config.ini";
			IniUtility.IniWriteValue("DATASERVER", "WEBSERVICE", WsUrl, subscribeini);
			IniUtility.IniWriteValue("DATASERVER", "CACHESERVER", cacheServerFullAddress, subscribeini);
			IniUtility.IniWriteValue("DATASERVER", "DCPORT", wsconfig.SubscribeDcport.ToString(), subscribeini);
			IniUtility.IniWriteValue("DATASERVER", "PUBPORT", wsconfig.SubscribePubport.ToString(), subscribeini);
			IniUtility.IniWriteValue("REALTIME", "REALTIMESERVER", realtimeIp, subscribeini);
			IniUtility.IniWriteValue("REALTIME", "PORT", wsconfig.RealtimeServerPort, subscribeini);
			IniUtility.IniWriteValue("LOG", "LOGSERVER", logServerFullAddress, subscribeini);


			LocalLoggingService.Info("修改订阅服务配置文件[config.ini]：前台中间层地址、缓存服务地址、实时服务地址、日志服务地址");

			return true;
		}
	}
}
