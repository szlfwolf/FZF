using System;
using Microsoft.Practices.Unity;

namespace CM.TOS.V4.Common.Component
{
    public class LocalServiceLocator
    {
        public static T GetService<T>()
        {
            if (TOSFramework.Status == TOSFrameworkStatus.NotStarted)
            {
                throw new Exception("TOSFramework尚未启动！");
            }
            else
            {
                try
                {
                    T instance = TOSFramework.Container.Resolve<T>();
                    return instance;
                }
                catch (Exception ex)
                {
                    LocalLoggingService.Error("TOSFramework.LocalServiceLocator 不能解析 '{0}'，异常信息：{1}", typeof(T).FullName, ex.ToString());
                    throw;
                }
            }
        }
    }
}
