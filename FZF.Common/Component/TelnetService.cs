using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Component
{
    public class TelnetService
    {
         public delegate string DispathCommandDelegate(string strcmd);
        public event  DispathCommandDelegate DispatchCommand;
        public Stack<string> CmdStack = new Stack<string>();
        public List<string> CmdList = new List<string>();  

        public string CMDPromot = "Server";
        public string DefaultMsag = "Welcome!";

        public string Ip = "127.0.0.1";               
        int Port = 443;

        public TelnetService() 
        {

        }
        public TelnetService(string ip, int port) { Ip = ip;  Port = port; }
        public TelnetService(string ip, int port,string cmdPromot, string defaultMsg) : this(ip,port)
         {
             if (!string.IsNullOrEmpty(cmdPromot)) CMDPromot = cmdPromot;
             if ( !string.IsNullOrEmpty( defaultMsg) ) DefaultMsag = defaultMsg;
         }
        public void Start()
        {
            Thread _createServer = new Thread(new ThreadStart(WaitForConnect));
            _createServer.Start();

            //WaitForConnect();
        }
        public void WaitForConnect()
        {


                

                while (true)
                {                    
                    try
                    {
                        IPAddress HOST = IPAddress.Parse(Ip);
                        IPEndPoint serverEP = new IPEndPoint(HOST, Port);
                        Socket sck = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);


                        Socket msg = null;

                        sck.Bind(serverEP);
                        sck.Listen(Port);
                        LocalLoggingService.Info("Telnet service: Listening for clients...");
                        
                        msg = sck.Accept();
                        try
                        {
                            SendReceiveMsg(msg);
                        }
                        catch (SocketException se)
                        {
                            Console.WriteLine(se.Message);
                        }
                        catch (Exception e)
                        {
							Console.WriteLine(e.Message);
                        }

                        sck.Close();
                        msg.Close();
                    }
                    catch (Exception e)
                    {
						Console.WriteLine(e.Message);
                    }
                }
               

        }

        private void SendReceiveMsg(Socket msg)
        {
            // Send a welcome greet
            byte[] buffer = Encoding.Default.GetBytes(DefaultMsag + "\r\n\r\n");
            msg.Send(buffer, 0, buffer.Length, 0);

            byte[] cmdPromtbuffer = Encoding.Default.GetBytes("\r\n" + CMDPromot + ">");
            msg.Send(cmdPromtbuffer, 0, cmdPromtbuffer.Length, 0);

            byte[] cmdbuffer = new byte[255];
            int cmdbuffIndex = 0;

            string output = null;

            while (true)
            {
                buffer = new byte[255];


                // Read the sended command                    
                int rec = msg.Receive(buffer, 0, buffer.Length, 0);

                if (rec == 0) break;


                if (rec == 2 && buffer[0] == 13 && buffer[1] == 10)
                {
                    string strcmd = Encoding.Default.GetString(cmdbuffer, 0, cmdbuffIndex);

                    SaveCommand(strcmd);

                    output = "\r\n" + DispatchMessage(strcmd) + "\r\n";
                    cmdbuffer = new byte[255];

                }
                else if (rec == 3 && buffer[0] == 27 && buffer[1] == 91)
                {
                    //小键盘，上箭头。
                    if (buffer[2] == 65)
                    {
                        //cmdbuffer[cmdbuffIndex] = 0;
                        string hiscmd = GetHisCommand();
                        if (string.IsNullOrEmpty(hiscmd)) continue;

                        cmdbuffer = new byte[255];

                        byte[] hiscmdbyte = Encoding.Default.GetBytes(hiscmd);

                        hiscmdbyte.CopyTo(cmdbuffer, 0);
                        cmdbuffIndex = hiscmdbyte.Length;

                        msg.Send(cmdPromtbuffer, 0, cmdPromtbuffer.Length, 0);
                        msg.Send(hiscmdbyte, 0, hiscmdbyte.Length, 0);
                        continue;
                    }
                }
                else if ((rec == 1 && buffer[0] == 8) ||
                    (rec == 3 && buffer[0] == 27 && buffer[1] == 68))
                {
                    msg.Send(cmdPromtbuffer, 0, cmdPromtbuffer.Length, 0);

                    //回格键 或 左箭头 
                    if (cmdbuffIndex > 0)
                    {
                        cmdbuffIndex -= 1;
                        cmdbuffer[cmdbuffIndex] = 0;


                        msg.Send(buffer, 0, buffer.Length, 0);
                    }
                    continue;
                }
                else
                {
                    for (int i = 0; i < rec; i++)
                    {
                        cmdbuffer[cmdbuffIndex++] = buffer[i];
                    }
                    continue;
                }




                if (output != null)
                {
                    byte[] cmdOutput = Encoding.Default.GetBytes(output);
                    msg.Send(cmdOutput, 0, cmdOutput.Length, 0);
                }


                msg.Send(cmdPromtbuffer, 0, cmdPromtbuffer.Length, 0);

                cmdbuffIndex = 0;
                //cmdOutput = new byte[255];
            }
        }

        private void SaveCommand(string strcmd)
        {
            if (string.IsNullOrEmpty(strcmd)) return;
            if (!CmdList.Contains(strcmd))
            {
                CmdStack.Push(strcmd);
                CmdList.Add(strcmd);
            }
        }

        private string GetHisCommand()
        {
            if (CmdStack.Count < 1)
            {
                CmdList.ForEach(x => CmdStack.Push(x));
            }
            return CmdStack.Count > 0 ? CmdStack.Pop() : string.Empty;
        }

        private  string DispatchMessage(string cmd)
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
