# Image Management System - Complete Guide

## Overview

This document describes the complete image management system that works seamlessly across both **local development** and **live shared hosting** environments.

## Architecture

### Key Components

1. **ImageHelper** (`app/Helpers/ImageHelper.php`)
   - Detects which storage location is available
   - Generates correct URLs for both environments
   - Handles image deletion and validation

2. **ImageService** (`app/Services/ImageService.php`)
   - Centralized image upload, delete, and URL generation
   - Validates images before upload
   - Used by all other services

3. **Helper Functions** (`app/helpers.php`)
   - `imageUrl($path)` - Get image URL in Blade templates
   - `imageExists($path)` - Check if image exists

4. **Service Classes** (Updated)
   - `ServiceService.php` - Uses ImageService
   - `ProjectService.php` - Uses ImageService
   - `TestimonialService.php` - Uses ImageService

5. **Custom Artisan Command** (`app/Console/Commands/LinkStorage.php`)
   - Safely creates symlink on local dev
   - Detects shared hosting mode automatically

## Storage Locations

### Local Development
- **Primary**: `storage/app/public/` with symlink at `public/storage/`
- **Access URL**: `/storage/services/image.jpg`
- **Created by**: `php artisan storage:link`

### Shared Hosting (Live)
- **Primary**: `public/uploads/` (direct access, no symlink needed)
- **Access URL**: `/uploads/services/image.jpg`
- **Setup**: Manually create directories via cPanel

## How It Works

### Automatic Detection

The `ImageHelper` class automatically detects which environment is running:

```php
// Check if running on shared hosting (public/uploads exists)
$publicPath = public_path('uploads/' . $path);
if (file_exists($publicPath)) {
    return asset('uploads/' . $path);
}

// Fall back to storage symlink if it exists (local development)
$storagePath = storage_path('app/public/' . $path);
if (file_exists($storagePath)) {
    return asset('storage/' . $path);
}
```

### Image Upload Flow

1. Controller receives uploaded file
2. Passes file to Service class (e.g., `ServiceService`)
3. Service uses `ImageService` to upload
4. `ImageService` detects available storage location
5. Image stored in appropriate directory
6. Relative path returned and saved to database

### Image Display Flow

1. Blade template requests image URL via `imageUrl()` helper
2. `ImageHelper` detects which storage location has the image
3. Returns correct URL for current environment
4. Image displays in browser

## Setup Instructions

### Local Development Setup

```bash
# Create storage symlink
php artisan storage:link

# This will create: public/storage -> storage/app/public

# You should see: ✓ Symbolic link created successfully!
```

### Shared Hosting Setup

```bash
# 1. Connect via cPanel File Manager or SSH
# 2. Navigate to public_html/
# 3. Create directories (if not already created)
mkdir -p public/uploads/{services,projects,testimonials,about}

# 4. Set proper permissions
chmod -R 755 public/uploads/
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

## Usage in Blade Templates

### Simple Image Display

```blade
@if($service->image)
    <img src="{{ imageUrl($service->image) }}" alt="{{ $service->title }}">
@endif
```

### With Fallback

```blade
<img src="{{ imageUrl($project->image) ?: asset('images/placeholder.jpg') }}" 
     alt="{{ $project->title }}">
```

### Checking Image Existence

```blade
@if(imageExists($testimonial->avatar))
    <img src="{{ imageUrl($testimonial->avatar) }}" alt="{{ $testimonial->name }}">
@else
    <div class="placeholder-avatar">{{ substr($testimonial->name, 0, 1) }}</div>
@endif
```

## Usage in Controllers

### Uploading Images

```php
use App\Services\ImageService;

class ServiceController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        // Upload image
        $imagePath = $this->imageService->uploadImage(
            $request->file('image'),
            'services'  // subdirectory
        );

        // Save to database
        Service::create([
            'image' => $imagePath,
            // ... other data
        ]);
    }

    public function update(Request $request, Service $service)
    {
        // Replace image (deletes old, uploads new)
        if ($request->hasFile('image')) {
            $imagePath = $this->imageService->replaceImage(
                $request->file('image'),
                $service->image,
                'services'
            );
            
            $service->update(['image' => $imagePath]);
        }
    }

    public function destroy(Service $service)
    {
        // Delete image automatically when deleting service
        if ($service->image) {
            $this->imageService->deleteImage($service->image);
        }
        
        $service->delete();
    }
}
```

## File Structure

```
project-root/
├── app/
│   ├── Helpers/
│   │   └── ImageHelper.php          # Core image helper
│   ├── Services/
│   │   ├── ImageService.php         # Image upload/delete service
│   │   ├── ServiceService.php       # Uses ImageService
│   │   ├── ProjectService.php       # Uses ImageService
│   │   └── TestimonialService.php   # Uses ImageService
│   ├── Console/
│   │   └── Commands/
│   │       └── LinkStorage.php      # Symlink command
│   └── helpers.php                  # Helper functions
├── storage/
│   └── app/
│       └── public/                  # Local storage (with symlink)
│           ├── services/
│           ├── projects/
│           ├── testimonials/
│           └── about/
├── public/
│   ├── uploads/                     # Shared hosting storage (direct)
│   │   ├── services/
│   │   ├── projects/
│   │   ├── testimonials/
│   │   └── about/
│   └── storage -> ../storage/app/public  # Symlink (local only)
└── config/
    └── filesystems.php              # Filesystem configuration
```

## Environment-Specific Notes

### Local Development

- **Advantages**:
  - Symlink provides clean URLs
  - Storage outside web root (better security)
  - Easy testing

- **Setup**:
  ```bash
  php artisan storage:link
  ```

- **Accessing images**:
  - URL: `http://localhost:8000/storage/services/image.jpg`

### Shared Hosting (Live)

- **Advantages**:
  - Direct file access (no symlink required)
  - Simple deployment
  - Works with shared hosting restrictions

- **Setup** (via cPanel):
  1. Create `public/uploads/` directories
  2. Set permissions to 755
  3. Upload Laravel project
  4. Run migrations

- **Accessing images**:
  - URL: `https://rollershuttersingapore.com/uploads/services/image.jpg`

## Troubleshooting

### Images Not Displaying

**Problem**: Images show 404 errors

**Solutions**:
1. Check if `public/uploads/` directory exists
2. Check if `storage/app/public/` has images (local dev)
3. Verify permissions are correct
4. Run `php artisan storage:link` on local dev
5. Check file paths in database

### Storage Link Fails

**Problem**: `php artisan storage:link` fails

**Solution** (Windows):
```bash
# Run as administrator
mklink /D "path\to\public\storage" "path\to\storage\app\public"
```

**Solution** (Linux/Mac):
```bash
# Check permissions
chmod -R 755 storage/
chmod -R 755 public/
```

### Upload Failures

**Problem**: Images won't upload

**Checks**:
1. Directory permissions: `chmod -R 777 storage/`
2. Disk space available
3. File size within limits (5MB default)
4. Supported formats: JPG, PNG, GIF, WebP
5. Check Laravel logs: `storage/logs/laravel.log`

### Shared Hosting Upload Issues

**Problem**: Upload works locally but not on live

**Solutions**:
1. Verify `public/uploads/` exists on server
2. Set directory permissions: `chmod -R 755 public/uploads/`
3. Set storage permissions: `chmod -R 777 storage/`
4. Check server PHP memory limit
5. Check max upload size in php.ini

## Deployment Checklist

### Before Going Live

- [ ] Create `public/uploads/` directories on server
- [ ] Set permissions: `755` for uploads, `777` for storage
- [ ] Run migrations on live server
- [ ] Test image upload in admin panel
- [ ] Verify images display on homepage
- [ ] Check all image paths in database

### After Deployment

- [ ] Monitor error logs for file permission issues
- [ ] Test image uploads
- [ ] Test image deletion
- [ ] Verify images load from CDN/cache if applicable
- [ ] Monitor storage space usage

## Performance Optimization

### Image Optimization

```php
// In ImageService, consider adding:
public function optimizeImage($filePath)
{
    // Use ImageMagick or GD to optimize
    // Resize to reasonable dimensions
    // Compress for web
}
```

### Caching

Images are referenced through models that are cached. Update cache when images change:

```php
// Already implemented in Service classes
Cache::forget('services.active');
Cache::forget('projects.featured');
Cache::forget('testimonials.active');
```

## Security Considerations

### File Upload Security

1. **Validation** - `ImageService` validates:
   - File size (max 5MB)
   - MIME types (JPG, PNG, GIF, WebP)
   - File extension

2. **File Naming** - Unique names generated:
   ```php
   $filename = uniqid() . '_' . time() . '.' . $extension;
   ```

3. **Directory Protection**:
   ```php
   // .htaccess in uploads directory (Apache)
   <FilesMatch "\.php$">
       Deny from all
   </FilesMatch>
   ```

4. **Symlink Security** - `storage/app/public/` is protected by Laravel
   - Files outside web root (local dev)
   - Protected by `.htaccess` and permissions

## Additional Resources

- [Laravel File Storage Documentation](https://laravel.com/docs/10.x/filesystem)
- [Laravel Configuration Documentation](https://laravel.com/docs/10.x/configuration)
- [Image Intervention Package](http://image.intervention.io/) - For advanced image manipulation

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review file permissions
3. Verify database image paths
4. Check `ImageHelper` and `ImageService` classes
5. Test with `php artisan tinker`

---

**Last Updated**: November 29, 2025
**Version**: 1.0
**Compatibility**: Laravel 12, PHP 8.2+
