@echo off
REM Image Management System - Setup Script for Windows
REM This script sets up the image storage system for local development

echo.
echo ==========================================
echo Image Management System Setup - Windows
echo ==========================================
echo.

REM Check PHP version
echo Checking PHP version...
php -v | findstr /R "PHP [0-9]"

REM Create storage directories
echo.
echo Creating storage directories...
if not exist "storage\app\public\services" mkdir storage\app\public\services
if not exist "storage\app\public\projects" mkdir storage\app\public\projects
if not exist "storage\app\public\testimonials" mkdir storage\app\public\testimonials
if not exist "storage\app\public\about" mkdir storage\app\public\about
echo. ✓ Storage directories created

REM Create public/uploads directories (for development/testing)
echo.
echo Creating public/uploads directories...
if not exist "public\uploads\services" mkdir public\uploads\services
if not exist "public\uploads\projects" mkdir public\uploads\projects
if not exist "public\uploads\testimonials" mkdir public\uploads\testimonials
if not exist "public\uploads\about" mkdir public\uploads\about
echo. ✓ Public uploads directories created

REM Create symlink
echo.
echo Creating storage symlink...
if exist "public\storage" (
    echo. ⚠ Symlink already exists
) else (
    setlocal enabledelayedexpansion
    set "source=%cd%\storage\app\public"
    set "link=%cd%\public\storage"
    
    REM Try to create symlink (requires admin privileges)
    mklink /D "!link!" "!source!" >nul 2>&1
    if !errorlevel! equ 0 (
        echo. ✓ Symlink created successfully
    ) else (
        echo. ✗ Failed to create symlink - you may need to run as Administrator
        echo.   Or use this command:
        echo.   mklink /D "public\storage" "storage\app\public"
    )
    endlocal
)

REM Run PHP artisan storage:link
echo.
echo Running PHP artisan storage:link...
php artisan storage:link

REM Check directories
echo.
echo Verifying directory structure...
if exist "storage\app\public" (
    echo. ✓ Storage directories exist
    dir /b storage\app\public\
) else (
    echo. ✗ Storage directories missing
)

if exist "public\uploads" (
    echo. ✓ Public uploads directories exist
    dir /b public\uploads\
) else (
    echo. ✗ Public uploads directories missing
)

echo.
echo ==========================================
echo Setup complete!
echo ==========================================
echo.
echo Next steps:
echo 1. Run: php artisan migrate
echo 2. Run: php artisan db:seed (optional)
echo 3. Start server: php artisan serve --port=8000
echo 4. Test image upload in admin panel
echo.
pause
