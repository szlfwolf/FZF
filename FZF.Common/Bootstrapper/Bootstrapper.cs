using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Practices.Unity;
using CM.TOS.V4.Common.Component;
using CM.TOS.V4.Common.Extension;
using CM.TOS.V4.Common.Utility;

namespace CM.TOS.V4.Common
{
    public class Bootstrapper : Disposable
    {
        private IUnityContainer container;

        public Bootstrapper(IUnityContainer container)
        {
            this.container = container;

            container.RegisterInstance<IUnityContainer>(container);

            BuildManagerWrapper.Current.ConcreteTypes
                       .Where(type => typeof(BootstrapperTask).IsAssignableFrom(type))
                       .Each(type => container.RegisterMultipleTypesAsSingleton(typeof(BootstrapperTask), type));
        }


        public bool Execute()
        {
            bool successful = true;
            var tasks = container.ResolveAll<BootstrapperTask>().OrderBy(t => t.Order).ToList();

            foreach (var task in tasks)
            {
                LocalLoggingService.Debug("TOSFramework.Bootstrapper begin execute '{0}' ({1})", task.GetType().FullName, task.Description);
                try
                {
                    if (task.Execute() == TaskContinuation.Break)
                    {
                        LocalLoggingService.Warning("TOSFramework.Bootstrapper execute abort '{0}' ({1})", task.GetType().FullName, task.Description);
                        successful = false;
                        break;
                    }
                }
                catch (Exception ex)
                {
                    LocalLoggingService.Error("TOSFramework.Bootstrapper execute error '{0}'，error msg：{1}", task.GetType().FullName, ex.ToString());
                }
            };
            return successful;
        }

        protected override void InternalDispose()
        {
            container.ResolveAll<BootstrapperTask>().OrderByDescending(t => t.Order).Each(task =>
            {
                try
                {
                    LocalLoggingService.Debug("TOSFramework.Bootstrapper begin dispose '{0}' ({1})", task.GetType().FullName, task.Description);
                    task.Dispose();
                }
                catch (Exception ex)
                {
                    LocalLoggingService.Error("TOSFramework.Bootstrapper dispose error '{0}'，error msg：{1}", task.GetType().FullName, ex.ToString());
                }
            });
        }
    }
}
