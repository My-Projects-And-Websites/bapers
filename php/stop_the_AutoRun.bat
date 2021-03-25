@echo off&setlocal enabledelayedexpansion
for /f "eol=* tokens=*" %%i in (autorun_switch.php) do (
set a=%%i
set "a=!a:1=0!"
echo !a!>>$)
move $ autorun_switch.php