using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common
{
    public abstract class AbstractService : Disposable
    {
        public bool Enabled { get; protected set; }
        public virtual string ServiceName { get; protected set; }

        public AbstractService()
        {
            Enabled = true;
            ServiceName = this.GetType().Name;
        }

        protected override void InternalDispose()
        {
            Enabled = false;
        }
    }
}
