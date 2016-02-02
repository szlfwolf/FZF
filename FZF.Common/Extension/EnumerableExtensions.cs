
using System;
using System.Collections.Generic;
using System.Diagnostics;

namespace CM.TOS.V4.Common.Extension
{
    public static class EnumerableExtensions
    {
        [DebuggerStepThrough]
        public static void Each<T>(this IEnumerable<T> instance, Action<T> action)
        {
            if (instance != null)
            {
                foreach (T item in instance)
                {
                    action(item);
                }
            }
        }

        //[DebuggerStepThrough]
        //public static void ParallelEach<T>(this IEnumerable<T> instance, Action<T> action)
        //{
        //    if (instance != null)
        //    {
        //        Parallel.ForEach(instance, item => action(item));
        //    }
        //}
    }
}
