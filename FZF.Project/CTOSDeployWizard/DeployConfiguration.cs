using CM.TOS.V4.Common;
using CM.TOS.V4.Common.Component;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CTOSDeployWizard
{
    public class DeployConfiguration
    {
        public static WsConfig GetWsConfig()
        {
            string ip = IISWorker.GetIp();
            return LocalConfigService.GetConfig<WsConfig>(new WsConfig
            {
                SiteName = "WsRpc",
                RpcDomainPort = 8110,
                RpcPhysicalPath = Path.Combine(Environment.CurrentDirectory, @"WsRpc"),
                IsCreateAppPool = true  ,
                IsReCreateAppPool = false,
                
                MsmqName = "tos_queue_realtime_v4.0",

                OracleConnection = "User Id=pacs;Password=www;Data Source=TOSDEV",
                SSOConnection = "User Id=SSO;Password=www;Data Source=TOSDEV",
                DisConnection = "User Id=dis;Password=www;Data Source=TOSDEV",                
                EdiConnection = "User Id=edi;Password=www;Data Source=TOSDEV",
                BillingConnection = "User Id=dis;Password=www;Data Source=TOSDEV",
                BillingSqlServerConnection = "server=192.168.78.16;user id=sa;password=Billing16;database=NBTESTA;connect timeout=0;Max Pool Size=100;Min Pool Size=2",

                CacheServerExe = "CTOS.DATACACHE.SERVICES.exe",
                CacheServerPath = Path.Combine(Environment.CurrentDirectory, @"CacheServer"),
                CTOSV4DbContext = @"metadata=res://*/Table.CTOSV4.csdl|res://*/Table.CTOSV4.ssdl|res://*/Table.CTOSV4.msl;provider=Oracle.DataAccess.Client;provider connection string=""data source=TOSDEV;password=WWW;persist security info=True;user id=CTOS""",

                LogServerSite="WsLog",
                LogServerSitePort = 8111,
                LogServerSitePath = Path.Combine(Environment.CurrentDirectory,  @"WsLog"),

                LogWebSite = "WebLog",
                LogWebSitePath = Path.Combine(Environment.CurrentDirectory, @"WebLog"),
                LogWebSitePort = 8114,

                DISServerSite = "WsDIS",
                DISServerSitePort = 8115,
                DISServerSitePath = Path.Combine(Environment.CurrentDirectory, @"WsDIS"),

                DISAPIServerSite = "WsDISAPI",
                DISAPIServerSitePort = 8116,
                DISAPIServerSitePath = Path.Combine(Environment.CurrentDirectory, @"WsDISAPI"),

                DISInterAPIServerSite = "WsDISInterAPI",
                DISInterAPIServerSitePort = 8117,
                DISInterAPIServerSitePath = Path.Combine(Environment.CurrentDirectory, @"WsDISInterAPI"),

                EdiServerSite = "WsEdi",
                EdiServerSitePort = 8118,
                EdiServerSitePath = Path.Combine(Environment.CurrentDirectory, @"WsEdi"),

                BillingServerSite = "WsBilling",
                BillingServerSitePort = 8120,
                BillingServerSitePath = null,

                BillingDataServerSite = "http://10.128.7.40:4549/CM.DATATRON.WebService/WSBaseDataSearch.asmx",
                BillingDataServerSitePort = 8121,
                BillingDataServerSitePath = null,

                BillingInternelServerSite = "http://10.128.7.40:4549/CM.BILLING.WebService.InterFace/WSBillingExternalInterface.asmx",
                BillingInternelServerSitePort = 8122,
                BillingInternelServerSitePath = null,

                BillingExternelServerSite = "WsBillingExternal",
                BillingExternelServerSitePort = 8123,
                BillingExternelServerSitePath = null,
            

                //LogServerExe = "SYSTEMLOG.SERVICES.exe",
                //LogServerPath = @"LogServer",

                //TOSDataServiceAddress = ip+":9900/RealtimeService",
                //MongodbServiceAddress = ip + ":17777/MongodbService",
                //SSOServiceAddress = ip + ":17778/LoginService",

                
                SSOServerSite="WsSso",
                SSOServerSitePort = 8112,                
                SSOServerSitePath = Path.Combine(Environment.CurrentDirectory, @"WsSso"),

                //SSOServerExe = "CMPORT.Workspace.Host.exe",
                //SSOServerPath = @"SsoServer",
                

                RealtimeServerExe = "CTOS.REALTIME.SERVICES.exe",
                RealtimeServerPath = Path.Combine(Environment.CurrentDirectory, @"RealtimeServer"),
                RealtimeServerPort = "708",

                SubscribeServerExe = "CTOS.SUBSCRIBE.SERVICES.exe",
                SubscribeServerPath = Path.Combine(Environment.CurrentDirectory, @"SubscribeServer"),
                SubscribePubport =  11112,
                SubscribeDcport = 11111,            

                User = "TEST",
                Password = "1",

                EnvText = "NBTEST3.3",
                EnvTextColor = "RGB(255,255,1)",
                EnvBKColor = "RGB(255,0,0)",

                
            }
                );
        }
    }
    public class WsConfig
    {
        //网站名称
        public string SiteName { get; set; }
        //端口
        public int RpcDomainPort { get; set; }
        //物理路径
        public string RpcPhysicalPath { get; set; }
        //是否创建新的应用程序池
        public bool IsCreateAppPool { get; set; }
        //oracle string
        public string OracleConnection { get; set; }
        //msmq 
        public string MsmqName { get; set; }

        //public string TOSDataServiceAddress { get; set; }
        //public string MongodbServiceAddress { get; set; }
        //public string SSOServiceAddress { get; set; }

        //网站部署
        public string SSOConnection { get; set; }
        public string SSOServerSite { get; set; }
        public int SSOServerSitePort { get; set; }
        public string SSOServerSitePath { get; set; }
        //exe部署
        public string SSOServerExe { get; set; }
        public string SSOServerPath { get; set; }

        public string CacheServerExe { get; set; }
        public string CacheServerPath { get; set; }
        public string CTOSV4DbContext { get; set; }

        //网站部署
        public string LogServerSite { get; set; }
        public int LogServerSitePort { get; set; }
        public string LogServerSitePath { get; set; }
        //exe部署
        public string LogServerExe { get; set; }
        public string LogServerPath { get; set; }


        //日志查询网站部署
        public string LogWebSite { get; set; }
        public int LogWebSitePort { get; set; }
        public string LogWebSitePath { get; set; }

        //后台中间层服务部署
        public string DisConnection { get; set; }
        public string DISServerSite { get; set; }
        public int DISServerSitePort { get; set; }
        public string DISServerSitePath { get; set; }

        //后台中间层-API服务部署
        public string DISAPIServerSite { get; set; }
        public int DISAPIServerSitePort { get; set; }
        public string DISAPIServerSitePath { get; set; }

        //后台中间层-InterAPI服务部署
        public string DISInterAPIServerSite { get; set; }
        public int DISInterAPIServerSitePort { get; set; }
        public string DISInterAPIServerSitePath { get; set; }

        //后台中间层-DoublePassEdi服务部署
        public string DoublePassEDIServerSite { get; set; }
        public int DoublePassEDIServerSitePort { get; set; }
        public string DoublePassEDIServerSitePath { get; set; }

        //Billing
        public string BillingServerSite { get; set; }
        public int BillingServerSitePort { get; set; }
        public string BillingServerSitePath { get; set; }

        //BillingInter
        public string BillingInterServerSite { get; set; }
        public int BillingInterServerSitePort { get; set; }
        public string BillingInterServerSitePath { get; set; }

        //BillingExter
        public string BillingExterServerSite { get; set; }
        public int BillingExterServerSitePort { get; set; }
        public string BillingExterServerSitePath { get; set; }

        //BillingData
        public string BillingDataServerSite { get; set; }
        public int BillingDataServerSitePort { get; set; }
        public string BillingDataServerSitePath { get; set; }




        public string RealtimeServerExe { get; set; }
        public string RealtimeServerPath { get; set; }
        public string RealtimeServerPort { get; set; }

        public string SubscribeServerExe { get; set; }
        public string SubscribeServerPath { get; set; }
        public int SubscribePubport { get; set; }
        public int SubscribeDcport { get; set; }

        public string User { get; set; }
        public string Password { get; set; }



        public string EdiConnection { get; set; }

        public string BillingConnection { get; set; }

        public string BillingSqlServerConnection { get; set; }

        public string EdiServerSite { get; set; }

        public int EdiServerSitePort { get; set; }

        public string EdiServerSitePath { get; set; }

        public string BillingInternelServerSite { get; set; }

        public int BillingInternelServerSitePort { get; set; }

        public string BillingInternelServerSitePath { get; set; }

        public string BillingExternelServerSite { get; set; }

        public int BillingExternelServerSitePort { get; set; }

        public string BillingExternelServerSitePath { get; set; }

        public bool IsReCreateAppPool { get; set; }

        public string EnvText { get; set; }

        public string EnvBKColor { get; set; }

        public string EnvTextColor { get; set; }
    }
}
