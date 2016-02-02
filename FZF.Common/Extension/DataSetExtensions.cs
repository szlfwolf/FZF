using System;
using System.Collections.Generic;
using System.Data;
using System.Diagnostics;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Extension
{
    public static class DataSetExtensions
    {
        [DebuggerStepThrough]
        public static bool IsEmpty(this DataSet instance)
        {
            if (instance == null || instance.Tables.Count < 1) return false;
            return true;
        }
    }
}
