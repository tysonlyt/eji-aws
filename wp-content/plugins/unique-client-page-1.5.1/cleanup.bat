@echo off
echo Cleaning up temporary files...

del /s /q *.log
del /s /q *.tmp
del /s /q *.temp

echo Cleaning up backup directories...
if exist "assets\css\backup" rmdir /s /q "assets\css\backup"
if exist "assets\js\backup" rmdir /s /q "assets\js\backup"

echo Cleaning up development files...
del /s /q *.sublime-*
del /s /q *.code-workspace
del /s /q .DS_Store

echo Cleanup complete!
pause
