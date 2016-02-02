using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net.Sockets;
namespace CM.TOS.V4.Common.Component
{
    public class TelnetClient
    {
        private TcpClient Client; 
        private NetworkStream ns; 
        private string m_LogonPrompt = "chs:";
        private string m_PasswordPrompt = "ctms"; 
        private readonly int BuffSize = 1024 * 4;

        /// <summary>        
        /// 登录输入用户名提示字符        
        /// 默认值：ogin:        
        /// </summary>        
        public string LoginPrompt        
        {            
            set { m_LogonPrompt = value; }            
            get { return m_LogonPrompt; }        
        }
        /// <summary>        
        /// 登录输入密码提示字符        
        /// 默认值：assword:        
        /// </summary>     
        public string PasswordPrompt        
        {            
            set { m_PasswordPrompt = value; }            
            get { return m_PasswordPrompt; }        
        }
        /// <summary>        
        /// 连接状态        
        /// </summary>        
        public bool Connected        
        {            
            get { return Client != null ? Client.Connected : false; }        
        }
        /// <summary>        
        /// 连接远端主机        
        /// </summary>        
        /// <param name="hostname">主机名称</param>        
        /// <param name="port">端口</param>        
        /// <returns>远端主机响应文本</returns>        
        public string Connect(string hostname, int port)        
        {            
            string result = string.Empty;            
            try            
            {                
                Client = new TcpClient(hostname, port);                
                ns = Client.GetStream();                
                result = Negotiate();            
            }            
            catch (Exception e)            
            {                
                result = e.Message;            
            }            
            return result;        
        }
        /// <summary>        
        /// 连接远端主机并登录        
        /// </summary>        
        /// <param name="hostname">主机名称</param>        
        /// <param name="port">端口</param>        
        /// <param name="username">用户名</param>        
        /// <param name="password">密码</param>        
        /// <param name="waitTime">连接等待时间</param>        
        /// <returns></returns>        
        public string Connect(string hostname, int port, string username, string password, int waitTime)        
        {            
            string result = string.Empty;            
            result = Connect(hostname, port);            
            if (Connected && result.EndsWith(LoginPrompt))            
            {                
                Send(username, waitTime);                
                result = Receive();                
                if (result.EndsWith(PasswordPrompt))                
                {                    
                    Send(password, waitTime);                    
                    result = Receive();                    
                    if (!result.EndsWith("<"))                    
                    {                        
                        Client.Close();                        
                        result = "Logon Error";                    
                    }                
                }            
            }            
            return result;        
        }
        /// <summary>        
        /// 接收        
        /// </summary>        
        /// <returns>传回的数据</returns>        
        public string Receive()        
        {            
            StringBuilder result = new StringBuilder();            
            if (Connected && ns.CanRead)            
            {                
                byte[] buff = new byte[BuffSize];                
                int numberOfRead = 0;                
                do                
                {                    
                    numberOfRead = ns.Read(buff, 0, BuffSize);                    
                    result.AppendFormat("{0}", Encoding.ASCII.GetString(buff, 0, numberOfRead));                
                } 
                while (ns.DataAvailable);            
            }            
            return result.ToString().Trim();        
        }
        /// <summary>        
        /// 发送        
        /// </summary>        
        /// <param name="cmd">指令</param>        
        /// <param name="waitTime">等待超时时间（毫秒）</param>        
        public void Send(string cmd, int waitTime)
        {            
            if (Connected && ns.CanWrite)            
            {                
                cmd += "\r\n";                
                byte[] buff = Encoding.ASCII.GetBytes(cmd);                
                ns.Write(buff, 0, buff.Length);                
                System.Threading.Thread.Sleep(waitTime);            
            }        
        }
         /// <summary>        
        /// 关闭        
        /// </summary>        
        public void Close()        
        {            
            Client.Close();        
        }
        /// <summary>        
        /// 处理Telnet选项谈判，直到出现用户名输入提示        
        /// </summary>        
        /// <returns></returns>        
        private string Negotiate()        
        {            
            string result = string.Empty;            
            if (Connected)            
            {                
                while (true)                
                {                    
                    byte[] rev = ReceiveBytes();                    
                    result = Encoding.ASCII.GetString(rev).Trim();                    
                    if (result.EndsWith(LoginPrompt))                    
                    {                        
                        break;                    
                    }                    
                    int count = rev.Length / 3;                    
                    for (int i = 0; i < count; i++)                    
                    {                        
                        int iac = rev[i * 3];                        
                        int cmd = rev[i * 3 + 1];                        
                        int value = rev[i * 3 + 2];                        
                        if (((int)Verbs.IAC) != iac)                        
                        {                            
                            continue;                        
                        }                        
                        switch (cmd)                        
                        {                            
                            case (int)Verbs.DO:                                
                                ns.WriteByte((byte)iac);                                
                                ns.WriteByte(value == (int)Options.RD ? (byte)Verbs.WILL : (byte)Verbs.WONT);                                
                                ns.WriteByte((byte)value);                                
                                break;                            
                            case (int)Verbs.DONT:                                
                                ns.WriteByte((byte)iac);                                
                                ns.WriteByte((byte)Verbs.WONT);                                
                                ns.WriteByte((byte)value);                                
                                break;                            
                            case (int)Verbs.WILL:                                
                                ns.WriteByte((byte)iac);                                
                                ns.WriteByte(value == (int)Options.SGA ? (byte)Verbs.DO : (byte)Verbs.DONT);                                
                                ns.WriteByte((byte)value);                                
                                break;                            
                            case (int)Verbs.WONT:                                
                                ns.WriteByte((byte)iac);                                
                                ns.WriteByte((byte)Verbs.DONT);                                
                                ns.WriteByte((byte)value);                                
                                break;                            
                            default:                                
                                break;                        
                        }                    
                    }                
                }            
            }            
            return result;        
        }
        /// <summary>        
        /// 读取字节流        
        /// </summary>        
        /// <returns></returns>        
        private byte[] ReceiveBytes()        
        {            
            byte[] result = new byte[0];            
            byte[] buff = new byte[BuffSize];            
            int numberOfRead = 0;            
            if (Connected && ns.CanRead)            
            {                
                numberOfRead = ns.Read(buff, 0, BuffSize);                
                result = new byte[numberOfRead];                
                Array.Copy(buff, result, numberOfRead);            
            }            return result;        
        }
    }
    enum Verbs    
    {        
        WILL = 251,        
        WONT = 252,        
        DO = 253,        
        DONT = 254,        
        IAC = 255    
    }    
    enum Options    
    {        
        RD = 1,        
        SGA = 3    
    }
}
