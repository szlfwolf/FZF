

using CM.TOS.V4.Common;
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
	class disconfigedit : IConfigEdit
	{
		public string Name
		{
			get { return "后台配置"; }
		}

		public bool modify(WsConfig wsconfig)
		{
			try
			{
				if (!Directory.Exists(wsconfig.DISServerSitePath)) Directory.CreateDirectory(wsconfig.DISServerSitePath);
				if (!Directory.Exists(wsconfig.DISAPIServerSitePath)) Directory.CreateDirectory(wsconfig.DISAPIServerSitePath);
				if (!Directory.Exists(wsconfig.DISInterAPIServerSitePath)) Directory.CreateDirectory(wsconfig.DISInterAPIServerSitePath);
				if (!Directory.Exists(wsconfig.EdiServerSitePath)) Directory.CreateDirectory(wsconfig.EdiServerSitePath);

				//检查站点配置文件是否存在
				if (!Program.CheckConfig(new string[] { 
					wsconfig.DISServerSitePath, 
					wsconfig.DISAPIServerSitePath, 
					wsconfig.DISInterAPIServerSitePath,
					wsconfig.EdiServerSitePath
					 }, "web.config"))
				{
					return false;
				}

			ExeConfigurationFileMap filemap = new ExeConfigurationFileMap();
			string diswebcofigpath = Path.Combine(wsconfig.DISServerSitePath, "web.config");//配置文件路径
			filemap.ExeConfigFilename = diswebcofigpath;
			var disconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
			disconfig.ConnectionStrings.ConnectionStrings["OracleConnection"].ConnectionString = wsconfig.DisConnection;
			disconfig.AppSettings.Settings["MSMQNAME"].Value = wsconfig.MsmqName;
			disconfig.AppSettings.Settings["MSMQSERVERIP"].Value = IISWorker.GetIp();

			//disconfig.AppSettings.Settings["ENVTEXT"].Value = wsconfig.EnvText;
			//disconfig.AppSettings.Settings["ENVTEXTCOLOR"].Value = wsconfig.EnvTextColor;
			//disconfig.AppSettings.Settings["ENVBKCOLOR"].Value = wsconfig.EnvBKColor;

			SetDisUrl(wsconfig, disconfig);
			disconfig.Save(ConfigurationSaveMode.Modified);
			LocalLoggingService.Info("更新DIS中间层站点[{0}]的web.config： 消息队列地址、数据库连接地址、引用的服务地址", wsconfig.DISServerSite);

			Program.copyconfigfile(Path.Combine(wsconfig.DISServerSitePath, "Config"), "更新" + wsconfig.DISServerSite + "配置文件");

			//修改disapi站点web.config
			string disapiwebcofigpath = Path.Combine(wsconfig.DISAPIServerSitePath, "web.config");//配置文件路径
			filemap.ExeConfigFilename = disapiwebcofigpath;
			var disapiconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
			disapiconfig.ConnectionStrings.ConnectionStrings["OracleConnection"].ConnectionString = wsconfig.DisConnection;
			disapiconfig.AppSettings.Settings["MSMQNAME"].Value = wsconfig.MsmqName;
			disapiconfig.AppSettings.Settings["MSMQSERVERIP"].Value = IISWorker.GetIp();
			SetDisUrl(wsconfig, disapiconfig);
			disapiconfig.Save(ConfigurationSaveMode.Modified);

			LocalLoggingService.Info("更新DIS中间层站点[{0}]的web.config： 消息队列地址、数据库连接地址、引用的服务地址", wsconfig.DISAPIServerSite);

			Program.copyconfigfile(Path.Combine(wsconfig.DISAPIServerSitePath, "Config"), "更新" + wsconfig.DISAPIServerSite + "配置文件");

			//修改disintapi站点web.config
			string disintapiwebcofigpath = Path.Combine(wsconfig.DISInterAPIServerSitePath, "web.config");//配置文件路径
			filemap.ExeConfigFilename = disintapiwebcofigpath;
			var disintapiconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
			disintapiconfig.ConnectionStrings.ConnectionStrings["OracleConnection"].ConnectionString = wsconfig.DisConnection;
			disintapiconfig.AppSettings.Settings["MSMQNAME"].Value = wsconfig.MsmqName;
			disintapiconfig.AppSettings.Settings["MSMQSERVERIP"].Value = IISWorker.GetIp();
			SetDisUrl(wsconfig, disintapiconfig);
			disintapiconfig.Save(ConfigurationSaveMode.Modified);

			LocalLoggingService.Info("更新DIS中间层站点[{0}]的web.config： 消息队列地址、数据库连接地址、引用的服务地址", wsconfig.DISInterAPIServerSite);

			Program.copyconfigfile(Path.Combine(wsconfig.DISInterAPIServerSitePath, "Config"), "更新" + wsconfig.DISInterAPIServerSite + "配置文件");

			//修改EDI站点web.config
			filemap.ExeConfigFilename = Path.Combine(wsconfig.EdiServerSitePath, "web.config");//配置文件路径
			var ediconfig = ConfigurationManager.OpenMappedExeConfiguration(filemap, ConfigurationUserLevel.None);
			ediconfig.ConnectionStrings.ConnectionStrings["OracleConnection"].ConnectionString = wsconfig.EdiConnection;

			ApplicationSettingsGroup ediappSection = ediconfig.GetSectionGroup("applicationSettings") as ApplicationSettingsGroup;


			SetDisUrl(wsconfig, ediconfig);

			ediconfig.Save(ConfigurationSaveMode.Modified);			
			LocalLoggingService.Info("更新EDI中间层站点[{0}]的web.config： 数据库连接地址、后台内部api服务地址", wsconfig.EdiServerSite);

			//创建IIS站点
		
				Program.CreateWs(wsconfig.DISServerSitePath, wsconfig.DISServerSite, wsconfig.DISServerSitePort, wsconfig.IsCreateAppPool);
				Program.CreateWs(wsconfig.DISAPIServerSitePath, wsconfig.DISAPIServerSite, wsconfig.DISAPIServerSitePort, wsconfig.IsCreateAppPool);
				Program.CreateWs(wsconfig.DISInterAPIServerSitePath, wsconfig.DISInterAPIServerSite, wsconfig.DISInterAPIServerSitePort, wsconfig.IsCreateAppPool);

				Program.CreateWs(wsconfig.EdiServerSitePath, wsconfig.EdiServerSite, wsconfig.EdiServerSitePort, wsconfig.IsCreateAppPool);

				//生成客户端配置文件
				using (FileStream fs = File.Create("WebSeviceProxyUrl.xml"))
				{
					string dis = string.Format(disxml, IISWorker.GetIp(), wsconfig.DISServerSitePort);
					byte[] b = Encoding.Default.GetBytes(dis);
					fs.Write(b, 0, b.Length);
					fs.Flush();
				}


				using (FileStream fs = File.Create("NBWinUIPacs.xml"))
				{
					string dis = string.Format(comxml, IISWorker.GetIp(), wsconfig.DISServerSitePort, wsconfig.DISServerSite);
					byte[] b = Encoding.Default.GetBytes(dis);
					fs.Write(b, 0, b.Length);
					fs.Flush();
				}
			}
			catch (Exception ex)
			{
				LocalLoggingService.Error(ex.Message);
				return false;
			}

			return true;
		}

		//修改applicationSettings
		private void SetDisUrl(WsConfig wsconfig, Configuration config)
		{			
			ApplicationSettingsGroup appSection = config.GetSectionGroup("applicationSettings") as ApplicationSettingsGroup;			
			ClientSettingsSection css = appSection.Sections[0] as ClientSettingsSection;

			SettingElement se = css.Settings.Get("CM_EDI_Service_DocumentManagement_WSTosEdiInterface_WSTosEdiInterface");
			if (se != null)
			{
				css.Settings.Remove(se);
				se.Value.ValueXml.InnerXml = string.Format("http://{0}:{1}/Service.asmx", IISWorker.GetIp(), wsconfig.DISInterAPIServerSitePort);
				css.Settings.Add(se);
			}
			se = css.Settings.Get("CM_CTOS_BLL_BLLWebProxy_WSEdiTosInterface_WsEdiTosInterface");
			if (se != null)
			{
				css.Settings.Remove(se);
				se.Value.ValueXml.InnerXml = string.Format("http://{0}:{1}/Service.asmx", IISWorker.GetIp(), wsconfig.EdiServerSitePort);
				css.Settings.Add(se);
			}
			se = css.Settings.Get("TosPACSAddress");
			if (se != null)
			{
				css.Settings.Remove(se);
				se.Value.ValueXml.InnerXml = string.Format("http://{0}:{1}/Service.asmx", IISWorker.GetIp(), wsconfig.RpcDomainPort);
				css.Settings.Add(se);
			}
			se = css.Settings.Get("CM_CTOS_BLL_BLLWebProxy_WSBaseDataSearchForBilling_WSBaseDataSearch");
			if (se != null)
			{
				string url = string.IsNullOrEmpty(wsconfig.BillingDataServerSitePath) ? se.Value.ValueXml.InnerText = wsconfig.BillingDataServerSite :string.Format("http://{0}:{1}/{2}/Service.asmx", IISWorker.GetIp(), wsconfig.BillingDataServerSitePort, wsconfig.BillingDataServerSite);

				css.Settings.Remove(se);
				se.Value.ValueXml.InnerXml = url;
				css.Settings.Add(se);
			}
			se = css.Settings.Get("CM_CTOS_BLL_BLLWebProxy_WSBillingExternalInterface_WSBillingExternalInterface");
			if (se != null)
			{
				string url = string.IsNullOrEmpty(wsconfig.BillingInternelServerSitePath) ? se.Value.ValueXml.InnerText = wsconfig.BillingInternelServerSite :string.Format("http://{0}:{1}/{2}/Service.asmx", IISWorker.GetIp(), wsconfig.BillingInternelServerSitePort);

				css.Settings.Remove(se);
				se.Value.ValueXml.InnerXml = url;
				css.Settings.Add(se);
			}
		}

		public string disxml = @"<?xml version=""1.0"" encoding=""utf-8"" ?>

<CM.CTOS.WinUIWebProxy.Properties.Settings>
  <setting name=""WinUIWebProxy_WSAccountDetailManagement_WSAccountDetailManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSAccountDetailManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSAssistantOperation_WSAssistantOperation""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSAssistantOperation.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSBaseContainerService_WSBaseContainerService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSBaseContainerService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSBaseDataSearch_WSBaseDataSearch""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSBaseDataSearch.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSBasePortService_WSBasePortService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSBasePortService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSBaseResourceService_WSBaseResourceService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSBaseResourceService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSBillingService_WSBillingService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSBillingService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSChargeRuleManagement_WSChargeRuleManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSChargeRuleManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSContainerService_WSContainerService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSCONTAINERSERVICE.ASMX</value>
  </setting>
  <setting name=""WinUIWebProxy_WSContractKindManagement_WSContractKindManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSContractKindManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSContractManagement_WSContractManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSContractManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSEDIManagement_WSEDIManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSEDIManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSEmptyStorageCTNNumSet_WSEmptyStorageCTNNumSet""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSEmptyStorageCTNNumSet.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSEPortService_WSEPortService"" serializeAs=""String"">
    <value>http://{0}:{1}/CM.CTOS.WsReportServices/WSEPortService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSFdebitInvoiceService_WSFdebitInvoiceService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSFdebitInvoiceService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSFeeItemManagement_WSFeeItemManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSFeeItemManagement.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSGateManagement_WSGateManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSGateManagement.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSEGate_WSEGate"" serializeAs=""String"">
    <value>http://{0}:{1}/WSEGate.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSLoggingService_WSLoggingService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSLoggingService.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSOrderManagement_WSOrderManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSOrderManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSReport_WSReport"" serializeAs=""String"">
    <value>http://{0}:{1}/CM.CTOS.WsReportServices/WSReport.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSSysManagement_WSSysManagement""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSSysManagement.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSVesselDocument_WSVesselDocument""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSVesselDocument.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSVesselSchedule_WSVesselSchedule""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSVesselSchedule.asmx</value>
  </setting>
  <setting name=""WinUIWebProxy_WSVesselService_WSVesselService""
      serializeAs=""String"">
    <value>http://{0}:{1}/WSVesselService.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSShipImportService_WSTosShipImplService""
      serializeAs=""String"">
    <value>http://192.168.78.177/services/EportService</value>
  </setting>

  <setting name=""CM_CTOS_WinUIPacs_WSWinUIPacs_WSWinUIPacs"" serializeAs=""String"">
    <value>http://{0}:{1}/WSWinUIPacs.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSBaseDataSearchForBilling_WSBaseDataSearch""
                     serializeAs=""String"">
    <value>http://10.128.5.190:2433/CM.DATATRON.WebService/WSBaseDataSearch.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSBillingExternalInterface_WSBillingExternalInterface""
                     serializeAs=""String"">
    <value>http://10.128.5.190:2433/CM.BILLING.WebService.InterFace/WSBillingExternalInterface.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_QGCostco_QGCostco"" serializeAs=""String"">
    <value>http://{0}:{1}/CM.CTOS.ApiServiceLayer/QGCostco.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_TCodesMaintain_TCodesMaintain"" serializeAs=""String"">
    <value>http://{0}:7012/CM.CTOS.EGateSendTest/TCodesMaintain.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_TCodesMaintainJava_TCodesMaintainService"" serializeAs=""String"">
    <value>http://202.96.124.26:18181/EDICLP4ZS/services/TCodesMaintain</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_ESBProxy_ESBProxyService"" serializeAs=""String"">
    <value>http://202.96.124.26:18181/EDIESB/services/ESBProxy</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSUpdateNB_WSVCDataAccess"" serializeAs=""String"">
    <value>http://192.168.78.102:8888/CM.CTOS.RPCWebServiceLayer.UpdateNB/Service.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_DoorWsw_doorwsw"" serializeAs=""String"">
    <value>http://{0}:{1}/NBGateSrvDock/doorwsw.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_ESBWS_ESBWS"" serializeAs=""String"">
    <value>http://192.168.78.36:6655/ESBWS.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSH986_WSH986Service"" serializeAs=""String"">
    <value>http://{0}:{1}/WSH986Service.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSCFSService_WSCFSService"" serializeAs=""String"">
    <value>http://{0}:{1}/WSCFSService.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSRFManagement_WSRFManagement"" serializeAs=""String"">
    <value>http://{0}:{1}/WSRFManagement.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_WSGateSelfService_WSGateSelfService"" serializeAs=""String"">
    <value>http://{0}:{1}/WSGateSelfService.asmx</value>
  </setting>
  <setting name=""CM_CTOS_WinUIWebProxy_SSZZService"" serializeAs=""String"">
    <value>http://{0}:18778/QGCostco.asmx</value>
  </setting>
  
</CM.CTOS.WinUIWebProxy.Properties.Settings>
";

		public string comxml = @"<?xml version=""1.0"" standalone=""yes""?>

<NewDataSet>
  <dt>
    <URL>http://{0}:{1}/{2}/WSWinUIPacs.asmx</URL>
    <URLNAME>WSWinUIPacs</URLNAME>
  </dt>
</NewDataSet>
";
	}
}
