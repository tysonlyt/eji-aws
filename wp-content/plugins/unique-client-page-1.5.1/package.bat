@echo off
echo Creating Unique Client Page plugin package...

set PLUGIN_DIR=unique-client-page
set OUTPUT_DIR=..\..\..\..\unique-client-page-package
set ZIP_FILE=%OUTPUT_DIR%\%PLUGIN_DIR%-%DATE:/=-%-%TIME::=-%.zip

rem Create output directory if it doesn't exist
if not exist "%OUTPUT_DIR%" mkdir "%OUTPUT_DIR%"

rem Create a temporary directory for packaging
set TEMP_DIR=%TEMP%\%PLUGIN_DIR%
if exist "%TEMP_DIR%" rmdir /s /q "%TEMP_DIR%"
mkdir "%TEMP_DIR%"

rem Copy all files to temp directory, excluding unnecessary files
xcopy /E /I /Y /EXCLUDE:package-exclude.txt . "%TEMP_DIR%"

rem Create the ZIP file
powershell -command "Compress-Archive -Path '%TEMP_DIR%\*' -DestinationPath '%ZIP_FILE%' -Force"

rem Clean up
rmdir /s /q "%TEMP_DIR%"

echo.
echo Package created successfully: %ZIP_FILE%
echo.
pause
