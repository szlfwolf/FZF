using Microsoft.Practices.Unity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common
{
    public abstract class RegisterServiceBootstrapperTask : BootstrapperTask
    {
        public RegisterServiceBootstrapperTask(IUnityContainer container) : base(container) { }

        public override int Order
        {
            get
            {
                return 1;
            }
        }
    }
}
