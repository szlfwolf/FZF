using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CTOSDeployWizard.Script
{
	public interface IConfigEdit
	{
		 bool modify(WsConfig wsconfig);
		 string Name { get; }
	}
}
