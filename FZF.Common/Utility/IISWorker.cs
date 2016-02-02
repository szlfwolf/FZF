

namespace CM.TOS.V4.Common
{
    using CM.TOS.V4.Common.Component;
using Microsoft.Web.Administration;
using System;
using System.Collections;
using System.Collections.Generic;
using System.DirectoryServices;
using System.Linq;
using System.Net;
using System.Security.AccessControl;
using System.Text;
using System.Threading.Tasks;


    public class IISInfo
    {
        public string DomainPort { get; set; }
        public string AppPool { get; set; }
    }
        /// <summary>
        /// IIS 操作方法集合
        /// http://blog.csdn.net/ts1030746080/article/details/8741399 错误
        /// </summary>
        public class IISWorker
        {
            private static string HostName = "localhost";

            /// <summary>
            /// 获取本地IIS版本
            /// </summary>
            /// <returns></returns>
            public static string GetIIsVersion()
            {
                try
                {
                    DirectoryEntry entry = new DirectoryEntry("IIS://" + HostName + "/W3SVC/INFO");
                    string version = entry.Properties["MajorIISVersionNumber"].Value.ToString();
                    return version;
                }
                catch (Exception se)
                {
                    LocalLoggingService.Error("IIS 5.0: {0}",se);
                    //说明一点:IIS5.0中没有(int)entry.Properties["MajorIISVersionNumber"].Value;属性，将抛出异常 证明版本为 5.0
                    return string.Empty;
                }
            }

            /// <summary>
            /// 创建虚拟目录网站
            /// </summary>
            /// <param name="webSiteName">网站名称</param>
            /// <param name="physicalPath">物理路径</param>
            /// <param name="domainPort">站点+端口，如192.168.1.23:90</param>
            /// <param name="isCreateAppPool">是否创建新的应用程序池</param>
            /// <returns></returns>
            public static int CreateWebSite(string webSiteName, string physicalPath, int port, bool isCreateAppPool)
            {
                string domainPort = string.Format("{0}:{1}", GetIp(), port);
                DirectoryEntry root = new DirectoryEntry("IIS://" + HostName + "/W3SVC");
                // 为新WEB站点查找一个未使用的ID
                int siteID = 1;
                foreach (DirectoryEntry e in root.Children)
                {
                    if (e.SchemaClassName == "IIsWebServer")
                    {
                        int ID = Convert.ToInt32(e.Name);
                        if (ID >= siteID) { siteID = ID + 1; }
                    }
                }
                // 创建WEB站点
                DirectoryEntry site = (DirectoryEntry)root.Invoke("Create", "IIsWebServer", siteID);
                site.Invoke("Put", "ServerComment", webSiteName);
                site.Invoke("Put", "KeyType", "IIsWebServer");
                site.Invoke("Put", "ServerBindings", domainPort + ":");
                site.Invoke("Put", "ServerState", 4);
                site.Invoke("Put", "FrontPageWeb", 1);
                site.Invoke("Put", "DefaultDoc", "Default.html");
                // site.Invoke("Put", "SecureBindings", ":443:");
                site.Invoke("Put", "ServerAutoStart", 1);
                site.Invoke("Put", "ServerSize", 1);
                site.Invoke("SetInfo");
                // 创建应用程序虚拟目录

                DirectoryEntry siteVDir = site.Children.Add("Root", "IISWebVirtualDir");
                siteVDir.Properties["AppIsolated"][0] = 2;
                siteVDir.Properties["Path"][0] = physicalPath;
                siteVDir.Properties["AccessFlags"][0] = 513;
                siteVDir.Properties["FrontPageWeb"][0] = 1;
                siteVDir.Properties["AppRoot"][0] = "LM/W3SVC/" + siteID + "/Root";
                siteVDir.Properties["AppFriendlyName"][0] = "Root";

                if (isCreateAppPool)
                {
                    if (IsAppPoolName(webSiteName))
                    {
                        DeleteAppPool(webSiteName);
                    }

                    DirectoryEntry apppools = new DirectoryEntry("IIS://" + HostName + "/W3SVC/AppPools");

                    DirectoryEntry newpool = apppools.Children.Add(webSiteName, "IIsApplicationPool");
                    newpool.Properties["AppPoolIdentityType"][0] = "2"; //4
                    newpool.Properties["ManagedPipelineMode"][0] = "0"; //0:集成模式 1:经典模式
                    newpool.CommitChanges();
                    siteVDir.Properties["AppPoolId"][0] = webSiteName;

                    #region 修改应用程序的配置(包含托管模式及其NET运行版本)
                    ServerManager sm = new ServerManager();
                    sm.ApplicationPools[webSiteName].ManagedRuntimeVersion = "v4.0";
//                    sm.ApplicationPools[webSiteName].ManagedPipelineMode = ManagedPipelineMode; //托管模式Integrated为集成 Classic为经典
                    sm.CommitChanges();
                    #endregion
                }

                siteVDir.CommitChanges();
                site.CommitChanges();
                return siteID;
            }

            /// <summary>
            /// 得到网站的物理路径
            /// </summary>
            /// <param name="rootEntry">网站节点</param>
            /// <returns></returns>
            public static string GetWebsitePhysicalPath(DirectoryEntry rootEntry)
            {
                string physicalPath = "";
                foreach (DirectoryEntry childEntry in rootEntry.Children)
                {
                    if ((childEntry.SchemaClassName == "IIsWebVirtualDir") && (childEntry.Name.ToLower() == "root"))
                    {
                        if (childEntry.Properties["Path"].Value != null)
                        {
                            physicalPath = childEntry.Properties["Path"].Value.ToString();
                        }
                        else
                        {
                            physicalPath = "";
                        }
                    }
                }
                return physicalPath;
            }

            /// <summary>
            /// 获取站点名
            /// </summary>
            public static List<IISInfo> GetServerBindings()
            {
                List<IISInfo> iisList = new List<IISInfo>();
                string entPath = String.Format("IIS://{0}/w3svc", HostName);
                DirectoryEntry ent = new DirectoryEntry(entPath);
                foreach (DirectoryEntry child in ent.Children)
                {
                    if (child.SchemaClassName.Equals("IIsWebServer", StringComparison.OrdinalIgnoreCase))
                    {
                        if (child.Properties["ServerBindings"].Value != null)
                        {
                            object objectArr = child.Properties["ServerBindings"].Value;
                            string serverBindingStr = string.Empty;
                            if (IsArray(objectArr))//如果有多个绑定站点时
                            {
                                object[] objectToArr = (object[])objectArr;
                                serverBindingStr = objectToArr[0].ToString();
                            }
                            else//只有一个绑定站点
                            {
                                serverBindingStr = child.Properties["ServerBindings"].Value.ToString();
                            }
                            IISInfo iisInfo = new IISInfo();
                            iisInfo.DomainPort = serverBindingStr;
                            iisInfo.AppPool = child.Properties["AppPoolId"].Value.ToString();//应用程序池
                            iisList.Add(iisInfo);
                        }
                    }
                }
                return iisList;
            }


            //public static bool CreateAppPool(string appPoolName, string Username, string Password)
            //{
            //    if (IsAppPoolName(appPoolName)) { DeleteAppPool(appPoolName); }
            //    bool issucess = false;
            //    try
            //    {
            //        //创建一个新程序池
            //        DirectoryEntry newpool;
            //        DirectoryEntry apppools = new DirectoryEntry("IIS://" + HostName + "/W3SVC/AppPools");
            //        newpool = apppools.Children.Add(appPoolName, "IIsApplicationPool");

            //        //设置属性 访问用户名和密码 一般采取默认方式
            //        newpool.Properties["WAMUserName"][0] = Username;
            //        newpool.Properties["WAMUserPass"][0] = Password;
            //        newpool.Properties["AppPoolIdentityType"][0] = "3";
            //        newpool.CommitChanges();

            //        #region 修改应用程序的配置(包含托管模式及其NET运行版本)
            //        ServerManager sm = new ServerManager();
            //        sm.ApplicationPools[appPoolName].ManagedRuntimeVersion = "v4.0";
            //        sm.ApplicationPools[appPoolName].ManagedPipelineMode = ManagedPipelineMode.Classic; //托管模式Integrated为集成 Classic为经典
            //        sm.CommitChanges();
            //        #endregion

            //        issucess = true;
            //        return issucess;
            //    }
            //    catch // (Exception ex) 
            //    {
            //        return false;
            //    }
            //}

            /// <summary>
            /// 判断程序池是否存在
            /// </summary>
            /// <param name="AppPoolName">程序池名称</param>
            /// <returns>true存在 false不存在</returns>
            private static bool IsAppPoolName(string AppPoolName)
            {
                bool result = false;
                DirectoryEntry appPools = new DirectoryEntry("IIS://localhost/W3SVC/AppPools");
                foreach (DirectoryEntry getdir in appPools.Children)
                {
                    if (getdir.Name.Equals(AppPoolName))
                    {
                        result = true;
                    }
                }
                return result;
            }

            /// <summary>
            /// 删除指定程序池
            /// </summary>
            /// <param name="AppPoolName">程序池名称</param>
            /// <returns>true删除成功 false删除失败</returns>
            private static bool DeleteAppPool(string AppPoolName)
            {
                bool result = false;
                DirectoryEntry appPools = new DirectoryEntry("IIS://localhost/W3SVC/AppPools");
                foreach (DirectoryEntry getdir in appPools.Children)
                {
                    if (getdir.Name.Equals(AppPoolName))
                    {
                        try
                        {
                            getdir.DeleteTree();
                            result = true;
                        }
                        catch
                        {
                            result = false;
                        }
                    }
                }
                return result;
            }

            /// <summary>
            /// 建立程序池后关联相应应用程序及虚拟目录
            /// </summary>
            public static void SetAppToPool(string appname, string poolName)
            {
                //获取目录
                DirectoryEntry getdir = new DirectoryEntry("IIS://localhost/W3SVC");
                foreach (DirectoryEntry getentity in getdir.Children)
                {
                    if (getentity.SchemaClassName.Equals("IIsWebServer"))
                    {
                        //设置应用程序程序池 先获得应用程序 在设定应用程序程序池
                        //第一次测试根目录
                        foreach (DirectoryEntry getchild in getentity.Children)
                        {
                            if (getchild.SchemaClassName.Equals("IIsWebVirtualDir"))
                            {
                                //找到指定的虚拟目录.
                                foreach (DirectoryEntry getsite in getchild.Children)
                                {
                                    if (getsite.Name.Equals(appname))
                                    {
                                        //【测试成功通过】
                                        getsite.Properties["AppPoolId"].Value = poolName;
                                        getsite.CommitChanges();
                                    }
                                }
                            }
                        }
                    }
                }
            }


            /// <summary>
            /// 判断网站是否已经存在
            /// </summary>
            /// <param name="strSiteName"></param>
            /// <returns></returns>
            public static string IsExist(string strSiteName)
            {
                string siteid = null;
                

                DirectoryEntry siteEntry = new DirectoryEntry("IIS://localhost/w3svc");

                foreach (DirectoryEntry childEntry in siteEntry.Children)
                {
                    if (childEntry.SchemaClassName == "IIsWebServer")
                    {
                        if (childEntry.Properties["ServerComment"].Value != null)
                        {
                            if (childEntry.Properties["ServerComment"].Value.ToString() == strSiteName)
                            {
                                
                                siteid = childEntry.Name;
                                break;
                            }
                        }
                    }
                }

                return siteid;
            }


            public static bool DelSite(string siteId)
            {                
                DirectoryEntry deRoot = new DirectoryEntry("IIS://localhost/W3SVC");
                try
                {
                    DirectoryEntry deVDir = new DirectoryEntry();
                    deRoot.RefreshCache();
                    deVDir = deRoot.Children.Find(siteId, "IIsWebServer");
                    deRoot.Children.Remove(deVDir);

                    deRoot.CommitChanges();
                    deRoot.Close();                    
                    return true;
                }
                catch (System.Exception ex)
                {
                    LocalLoggingService.Error("删除站点失败：{0}", ex);
                }
                return false;
            }
            

            /// <summary>
            /// 判断object对象是否为数组
            /// </summary>
            public static bool IsArray(object o)
            {
                return o is Array;
            }
            //获取本机ip
            private static string _ip = null;
            public static string GetIp()
            {
                if (!string.IsNullOrEmpty(_ip)) return _ip;
                foreach (var add in System.Net.Dns.GetHostEntry(System.Net.Dns.GetHostName()).AddressList)
                {
                    if (add.ToString().Contains(":")) continue;
                    _ip= add.ToString();
                    break;
                }
                return _ip;
            }

            /// <summary>
            /// 设置文件夹权限 处理给EVERONE赋予所有权限
            /// </summary>
            /// <param name="FileAdd">文件夹路径</param>
            public static void SetFileRole(string foldpath)
            {
                string FileAdd = foldpath;
                //FileAdd = FileAdd.Remove(FileAdd.LastIndexOf('\\'), 1);
                DirectorySecurity fSec = new DirectorySecurity();
                fSec.AddAccessRule(new FileSystemAccessRule("Network Service", FileSystemRights.FullControl, InheritanceFlags.ContainerInherit | InheritanceFlags.ObjectInherit, PropagationFlags.None, AccessControlType.Allow));
                System.IO.Directory.SetAccessControl(FileAdd, fSec);
            }
        }
    }

