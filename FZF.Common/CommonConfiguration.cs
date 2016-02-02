using CM.TOS.V4.Common.Component;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common
{
    public class CommonConfiguration
    {
        public static readonly string MachineIP = string.Join(" / ", Dns.GetHostAddresses(Dns.GetHostName())
        .Where(a => a.AddressFamily == AddressFamily.InterNetwork).Select(add => add.ToString()).ToArray());

        public static readonly string MachineName = Environment.MachineName;

        public static TOSConfig GetConfig()
        {
            var config = LocalConfigService.GetConfig(new TOSConfig
            {
                ApplicationName = "TOSFramework",

                
                //日志服务地址
                MongodbServiceAddress = "http://localhost.:8111/LogService.svc",
                //日志数据库地址
                MongodbAddress = "mongodb://localhost:11212",
                //缓存服务地址
                TOSDataServiceAddress = "net.tcp://localhost:9900/RealtimeService",
                //SSO服务地址
                SSOServiceAddress = "http://localhost:8112/SSOService.svc",

                LocalLoggingServiceLevel = LogLevel.Debug,
                ClearLocalLogWhenStart = false,
                LogToDb = true      ,
                

            });

            //if (string.IsNullOrEmpty(config.ApplicationName))
                //throw new Exception("请在Config/TOSConfig.config中配置ApplicationName节点的值为应用程序的名字！");
            //if (string.IsNullOrEmpty(config.ConfigServiceAddress))
            //    throw new Exception("请在Config/TOSConfig.config中配置ConfigServiceAddress节点的值为配置服务的地址！");
            //if (string.IsNullOrEmpty(config.WcfConfigServiceAddress))
            //    throw new Exception("请在Config/TOSConfig.config中配置WcfConfigServiceAddress节点的值为Wcf配置服务的地址！");
            //if (string.IsNullOrEmpty(config.MongodbServiceAddress))
                //throw new Exception("请在Config/TOSConfig.config中配置MongodbServiceAddress节点的值为数据服务的地址！");

            return config;

        }
    }
}
