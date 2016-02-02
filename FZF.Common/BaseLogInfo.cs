using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common
{
    public class BaseLogInfo
    {
        public BaseLogInfo()
        {
            logtime = DateTime.Now;
            ip = CommonConfiguration.MachineIP;
        }

        /// <summary>
        /// 由前台客户端生成，动作发起的所有消息记录相同id。
        /// </summary>
        public Guid sid { get; set; }

        public string name { get; set; }

        /// <summary>
        /// 表示消息当前的状态。结合ip可知消息到达哪里。
        /// wcf的处理过程 》 wcf-c-brq -> wcf-s-arq -> wcf-s-brp -> wcf-c-arp
        /// wcf-s-arq / wcf-s-brp      wcf的服务端介绍请求、返回。
        /// wcf-c-brq / wcf-c-arp      wcf的客户端发送、返回
        /// </summary>
        public string state { get; set; }

        /// <summary>
        /// 记录日志的机器ip
        /// </summary>
        public string ip { get; set; }

        /// tos前台调用的处理过程 》 ws-in(1) -> ws-out (2) -> db->out (3) -> wcf-c-brq (4)-> wcf-s-arq (5)-> wcf-s-brp (6)-> wcf-c-arp (7)
        public int seq { get; set; }

        /// <summary>
        /// 发送、返回的消息体
        /// </summary>
        public object msg { get; set; }

        /// <summary>
        /// 记录日志的时间
        /// </summary>
        public DateTime logtime { get; set; }

        /// <summary>
        /// 分前台(f)、后台(b)，
        /// </summary>
        public string Category { get; set; }

        /// <summary>
        /// 方法名
        /// </summary>
        public string methodname { get; set; }

        /// <summary>
        /// ticket id ,表示用户的id
        /// </summary>
        public string tid { get; set; }

        /// <summary>
        /// ticket ip ,表示用户的ip
        /// </summary>
        public string tip { get; set; }

        /// <summary>
        /// SQL执行耗时
        /// </summary>
        public double? cost { get; set; }

        public override string ToString()
        {
            return this.sid.ToString() + "," + this.msg;
        }
    }
}
