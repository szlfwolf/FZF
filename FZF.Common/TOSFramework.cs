using System;

using Microsoft.Practices.Unity;
using CM.TOS.V4.Common.Component;
using CM.TOS.V4.Common;

namespace CM.TOS.V4.Common
{
    public class TOSFramework
    {
        private static readonly IUnityContainer container = new UnityContainer();
        public static Bootstrapper bootstrapper = null;
        private const string Version = "V4.160104";

        public static IUnityContainer Container
        {
            get
            {
                return container;
            }
        }
        public static TOSFrameworkStatus Status { get; set; }

        public static void Start()
        {
            Status = TOSFrameworkStatus.Starting;
            LocalLoggingService.Info("TOSFramework begin...version：{0}", Version);
            bootstrapper = new Bootstrapper(container);
            if (bootstrapper.Execute())
            {
                Status = TOSFrameworkStatus.Started;
                LocalLoggingService.Info("TOSFramework end...version：{0}", Version);
            }
            else
            {
                Status = TOSFrameworkStatus.FailedToStart;
                throw new Exception("TOSFramework failed！");
            }
        }
        public static void End()
        {
            Status = TOSFrameworkStatus.Ending;
            LocalLoggingService.Info("TOSFramework begin dispose...");
            bootstrapper.Dispose();
            Status = TOSFrameworkStatus.Ended;
            LocalLoggingService.Info("TOSFramework end dispose");
            LocalLoggingService.Close();
        }
    }

    public enum TOSFrameworkStatus
    {
        NotStarted = 1,
        Starting = 2,
        Started = 3,
        FailedToStart = 4,
        Ending = 5,
        Ended = 6,
    }
}
