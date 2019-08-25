@ECHO OFF
SET MYPATH=%~dp0
IF EXIST %MYPATH%bulk_copy_errors.log del /F %MYPATH%bulk_copy_errors.log
mysql_config_editor.exe remove --login-path=wb_migration_source 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
mysql_config_editor.exe set --login-path=wb_migration_source -h127.0.0.1 -P3306 -uroot -p 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
SET command=mysql.exe -h127.0.0.1 -P3306 -uroot -p -s -N information_schema -e "SELECT Variable_Value FROM GLOBAL_VARIABLES WHERE Variable_Name = 'datadir'" 2>> "%MYPATH%bulk_copy_errors.log"
FOR /F "tokens=* USEBACKQ" %%F IN (`%command%`) DO (
    SET DADADIR=%%F
)
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
pushd %DADADIR%
echo [0 %%] Creating directory dump_prjmgt
mkdir dump_prjmgt
pushd dump_prjmgt
copy NUL import_prjmgt.sql
echo SET SESSION UNIQUE_CHECKS=0; >> import_prjmgt.sql
echo SET SESSION FOREIGN_KEY_CHECKS=0; >> import_prjmgt.sql
echo use migrated_prjmgt; >> import_prjmgt.sql
echo [9 %%] Start dumping tables
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt tasks --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename tasks.txt tasks.csv
del tasks.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/tasks.csv' INTO TABLE tasks FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [18 %%] Dumped table tasks
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt departments --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename departments.txt departments.csv
del departments.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/departments.csv' INTO TABLE departments FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [27 %%] Dumped table departments
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt task_times --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename task_times.txt task_times.csv
del task_times.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/task_times.csv' INTO TABLE task_times FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [36 %%] Dumped table task_times
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt customers --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename customers.txt customers.csv
del customers.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/customers.csv' INTO TABLE customers FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [45 %%] Dumped table customers
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt projects --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename projects.txt projects.csv
del projects.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/projects.csv' INTO TABLE projects FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [54 %%] Dumped table projects
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt resources --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename resources.txt resources.csv
del resources.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/resources.csv' INTO TABLE resources FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [63 %%] Dumped table resources
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt dept_projects --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename dept_projects.txt dept_projects.csv
del dept_projects.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/dept_projects.csv' INTO TABLE dept_projects FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [72 %%] Dumped table dept_projects
mysqldump.exe --login-path=wb_migration_source -t --tab=. prjmgt employees --fields-terminated-by=, 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
rename employees.txt employees.csv
del employees.sql
echo LOAD DATA INFILE 'migrated_prjmgt_#####_import/employees.csv' INTO TABLE employees FIELDS TERMINATED BY ',' ENCLOSED BY ''; >> import_prjmgt.sql
echo [81 %%] Dumped table employees
copy NUL import_prjmgt.cmd
(echo @ECHO OFF) >> import_prjmgt.cmd
(echo echo Started load data. Please wait.) >> import_prjmgt.cmd
(echo SET MYPATH=%%~dp0) >> import_prjmgt.cmd
(echo IF EXIST %%MYPATH%%import_errors.log del /F %%MYPATH%%import_errors.log) >> import_prjmgt.cmd
(echo SET command=mysql.exe -h127.0.0.1 -P3306 -uroot -p -s -N information_schema -e "SELECT Variable_Value FROM GLOBAL_VARIABLES WHERE Variable_Name = 'datadir'" 2^>^> %%MYPATH%%import_errors.log) >> import_prjmgt.cmd
(echo FOR /F "tokens=* USEBACKQ" %%%%F IN ^(^`%%command%%^`^) DO ^() >> import_prjmgt.cmd
(echo     SET DADADIR=%%%%F) >> import_prjmgt.cmd
(echo ^)) >> import_prjmgt.cmd
(echo if %%ERRORLEVEL%% GEQ 1 ^() >> import_prjmgt.cmd
(echo     echo Script has failed. See the log file for details.) >> import_prjmgt.cmd
(echo     exit /b 1) >> import_prjmgt.cmd
(echo ^)) >> import_prjmgt.cmd
(echo pushd %%DADADIR%%) >> import_prjmgt.cmd
(echo mkdir migrated_prjmgt_#####_import) >> import_prjmgt.cmd
(echo xcopy %%MYPATH%%*.csv migrated_prjmgt_#####_import\* 2^>^> %%MYPATH%%import_errors.log) >> import_prjmgt.cmd
(echo if %%ERRORLEVEL%% GEQ 1 ^() >> import_prjmgt.cmd
(echo     echo Script has failed. See the log file for details.) >> import_prjmgt.cmd
(echo     exit /b 1) >> import_prjmgt.cmd
(echo ^)) >> import_prjmgt.cmd
(echo xcopy %%MYPATH%%*.sql migrated_prjmgt_#####_import\* 2^>^> %%MYPATH%%import_errors.log) >> import_prjmgt.cmd
(echo if %%ERRORLEVEL%% GEQ 1 ^() >> import_prjmgt.cmd
(echo     echo Script has failed. See the log file for details.) >> import_prjmgt.cmd
(echo     exit /b 1) >> import_prjmgt.cmd
(echo ^)) >> import_prjmgt.cmd
(echo mysql.exe -h127.0.0.1 -P3306 -uroot -p ^< migrated_prjmgt_#####_import\import_prjmgt.sql 2^>^> %%MYPATH%%import_errors.log) >> import_prjmgt.cmd
(echo if %%ERRORLEVEL%% GEQ 1 ^() >> import_prjmgt.cmd
(echo     echo Script has failed. See the log file for details.) >> import_prjmgt.cmd
(echo     exit /b 1) >> import_prjmgt.cmd
(echo ^)) >> import_prjmgt.cmd
(echo rmdir migrated_prjmgt_#####_import /s /q) >> import_prjmgt.cmd
(echo echo Finished load data) >> import_prjmgt.cmd
(echo popd) >> import_prjmgt.cmd
(echo pause) >> import_prjmgt.cmd
echo [90 %%] Generated import script import_prjmgt.cmd
popd
set TEMPDIR=%DADADIR%dump_prjmgt
echo Set fso = CreateObject("Scripting.FileSystemObject") > _zipIt.vbs
echo InputFolder = fso.GetAbsolutePathName(WScript.Arguments.Item(0)) >> _zipIt.vbs
echo ZipFile = fso.GetAbsolutePathName(WScript.Arguments.Item(1)) >> _zipIt.vbs
echo CreateObject("Scripting.FileSystemObject").CreateTextFile(ZipFile, True).Write "PK" ^& Chr(5) ^& Chr(6) ^& String(18, vbNullChar) >> _zipIt.vbs
echo Set objShell = CreateObject("Shell.Application") >> _zipIt.vbs
echo Set source = objShell.NameSpace(InputFolder).Items >> _zipIt.vbs
echo objShell.NameSpace(ZipFile).CopyHere(source) >> _zipIt.vbs
echo Do Until objShell.NameSpace( ZipFile ).Items.Count ^= objShell.NameSpace( InputFolder ).Items.Count >> _zipIt.vbs
echo wScript.Sleep 200 >> _zipIt.vbs
echo Loop >> _zipIt.vbs
CScript  _zipIt.vbs  "%TEMPDIR%"  "%DADADIR%dump_prjmgt.zip" 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
echo [100 %%] Zipped all files to dump_prjmgt.zip file
xcopy dump_prjmgt.zip %MYPATH% 2>> "%MYPATH%bulk_copy_errors.log"
if %ERRORLEVEL% GEQ 1 (
    echo Script has failed. See the log file for details.
    exit /b 1
)
del dump_prjmgt.zip
del _zipIt.vbs
del /F /Q dump_prjmgt\*.*
rmdir dump_prjmgt
popd
echo Now you can copy %MYPATH%dump_prjmgt.zip file to the target server and run the import script.
pause
