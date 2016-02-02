using CM.TOS.V4.Common.Component;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common
{
    public class TOSConfig
    {
        public string ApplicationName { get; set; }

        public string LogPath { get; set; }
        public bool ClearLocalLogWhenStart { get; set; }

        public LogLevel LocalLoggingServiceLevel { get; set; }

        public bool LogToDb { get; set; }
        public string MongodbServiceAddress { get; set; }
        public string MongodbAddress { get; set; }

        public string TOSDataServiceAddress { get; set; }

        public string SSOServiceAddress { get; set; }


    }
}
