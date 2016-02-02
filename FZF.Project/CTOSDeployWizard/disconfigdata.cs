using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CTOSDeployWizard
{
    class disconfigdata : ConfigurationSection
    {
        [ConfigurationProperty("name")]
        public string Name
        {
            get { return (string)this["name"]; }
            set { this["name"] = value; }
        }

        [ConfigurationProperty("size")]
        public float Size
        {
            get { return (float)this["size"]; }
            set { this["size"] = value; }
        }

        [ConfigurationProperty("style")]
        public int Style
        {
            get { return (int)this["style"]; }
            set { this["style"] = value; }
        }
    }
}
