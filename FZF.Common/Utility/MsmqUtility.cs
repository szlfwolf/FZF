using CM.TOS.V4.Common.Component;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Messaging;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Utility
{
    public class MsmqUtility
    {
        public static bool Createqueue(string queuePath)
        {
            try
            {
                if (!MessageQueue.Exists(queuePath))
                {
                    MessageQueue.Create(queuePath);
                }
                else
                {
                    LocalLoggingService.Info("队列已经存在");
                }
            }
            catch (Exception ex)
            {
                LocalLoggingService.Error("创建队列失败:{0}", ex);
                return false;
            }
            return true;
        }
    }
}
