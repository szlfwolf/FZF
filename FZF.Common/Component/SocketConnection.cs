using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Sockets;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Component
{
    class SocketConnection
    {

        public delegate string DispathCommandDelegate(string strcmd);
        public event DispathCommandDelegate DispatchCommand;

        public Stack<string> CmdStack = new Stack<string>();
        public List<string> CmdList = new List<string>();

        private Socket _socket = null;
        public SocketConnection(Socket s)
        {
            _socket = s;
        }

        public string CMDPromot = "Server";
        public string DefaultMsag = "Welcome!";

        public void WaitForSendData()
        {
            // Send a welcome greet
            byte[] buffer = Encoding.Default.GetBytes(DefaultMsag + "\r\n\r\n");
            _socket.Send(buffer, 0, buffer.Length, 0);

            byte[] cmdPromtbuffer = Encoding.Default.GetBytes("\r\n" + CMDPromot + ">");
            _socket.Send(cmdPromtbuffer, 0, cmdPromtbuffer.Length, 0);

            byte[] cmdbuffer = new byte[255];
            int cmdbuffIndex = 0;

            string output = null;

            while (true)
            {

                buffer = new byte[255];


                // Read the sended command                    
                int rec = _socket.Receive(buffer, 0, buffer.Length, 0);


                if (rec == 2 && buffer[0] == 13 && buffer[1] == 10)
                {
                    string strcmd = Encoding.Default.GetString(cmdbuffer, 0, cmdbuffIndex);

                    SaveCommand(strcmd);

                    output = "\r\n" + DispatchMessage(strcmd) + "\r\n";
                    cmdbuffer = new byte[255];

                }
                else if (rec == 1 && (buffer[0] >= 32 && buffer[0] < 127))
                {
                    cmdbuffer[cmdbuffIndex++] = buffer[0];
                    //strcmd += Encoding.Default.GetString(buffer, 0, rec);
                    continue;
                }
            }

        }
        private void SaveCommand(string strcmd)
        {
            //未解决修改命令问题，暂不要循环命令。
            //if (!CmdList.Contains(strcmd))
            {
                CmdStack.Push(strcmd);
                // CmdList.Add(strcmd);
            }
        }

        private string DispatchMessage(string cmd)
        {
            if (DispatchCommand != null)
            {
                try
                {
                    return DispatchCommand(cmd);
                }
                catch (Exception ex)
                {
                    return ex.Message;
                }
            }

            return DefaultMsag;
        }

    }
}
