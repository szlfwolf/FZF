using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Extension
{
    public static class DateTimeExtensions
    {
        [DebuggerStepThrough]
        public static string ToTosString(this DateTime dt)
        {
            return dt.ToString("yyyy-MM-dd HH:mm:ss");
        }
    }
}
