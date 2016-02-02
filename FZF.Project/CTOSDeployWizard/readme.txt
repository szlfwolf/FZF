1、参照install.jpg安装系统组件。

2、参照readme.bmp了解目录结构

3、采用动态加载script目录下的cs文件执行具体部署功能。

4、SSO部署说明:
	Oracle.ManagedDataAccess.EntityFramework 依赖环境变量TNS_ADMIN获取TNS.
	新增系统环境变量后需重启IIS. 命令行输入：iisreset
