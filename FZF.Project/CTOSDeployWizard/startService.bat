@echo off

set dbfolder=
set dbexe=mongod.exe

set logfolder=logserver\
set logexe=SYSTEMLOG.SERVICES.exe

pushd %~dp0


for /f "delims= " %%i in ('tasklist^|find /i "%dbexe%"') do (
goto :1
)
start DATABASE\mongodb\run.bat
:1

for /f "delims= " %%i in ('tasklist^|find /i "%logexe%"') do (
goto :2
)
start %logfolder%%logexe%
:2
pause


for /f "delims= " %%i in ('tasklist^|find /i %logexe%') do (
goto :3
)
start REALTIME\DATACACHE\TOSCacheServer.exe

echo staring TOSCacheServer,wait 100 seconds
ping 127.0.0.1 -n 100 -w 1000 > nul
:3

for /f "delims= " %%i in ('tasklist^|find /i "RealTimeServer.exe"') do (
goto :4
)
call Realtime\Server\RegSystem.bat
start Realtime\Server\RealTimeServer.exe


echo starting RealtimeServer,wait 3 seconds
ping 127.0.0.1 -n 3 -w 1000 > nul
:4

for /f "delims= " %%i in ('tasklist^|find /i "CTOS.SUBSCRIBE.SERVICES.exe"') do (
goto :5
)
call REALTIME\SUBSCRIBENEW\RegSystem.bat
start REALTIME\SUBSCRIBENEW\CTOS.SUBSCRIBE.SERVICES.exe


echo starting TOSDataServer,wait 20 seconds
ping 127.0.0.1 -n 20 -w 1000 > nul
:5

for /f "delims= " %%i in ('tasklist^|find /i "WirelessSrv.exe"') do (
goto :6
)
call Wireless\Server\RegSystem.bat
start Wireless\Server\WirelessSrv.exe
:6

for /f "delims= " %%i in ('tasklist^|find /i "CTOS.QUAYTALLY.SERVICES.exe"') do (
goto :7
)
call Wireless\qsgServer\RegSystem.bat
start Wireless\qsgServer\CTOS.QUAYTALLY.SERVICES.exe
:7

echo 'all services started'
