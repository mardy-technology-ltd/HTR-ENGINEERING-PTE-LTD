# Image Management System - Quick Start Guide

## 🚀 30-Second Setup

### Local Development (Windows):
```bash
setup-images.bat
php artisan serve --port=8000
```

### Local Development (Linux/macOS):
```bash
bash setup-images.sh
php artisan serve --port=8000
```

### Manual Setup:
```bash
# Create directories
mkdir -p storage/app/public/{services,projects,testimonials,about}
mkdir -p public/uploads/{services,projects,testimonials,about}

# Create symlink
php artisan storage:link

# Start server
php artisan serve --port=8000
```

---

## ✅ How It Works

### The Magic:
1. **Upload Image** → System detects storage location
2. **Save Path** → Relative path stored in database
3. **Display Image** → `imageUrl()` helper generates correct URL
4. **Works Everywhere** → Same code works on localhost and live server

### No Configuration Needed! ✨

---

## 💡 Usage Examples

### In Blade Templates:
```blade
<!-- Basic -->
<img src="{{ imageUrl($service->image) }}" alt="{{ $service->title }}">

<!-- With fallback -->
<img src="{{ imageUrl($project->image) ?: asset('images/placeholder.jpg') }}" alt="...">

<!-- Check before display -->
@if(imageExists($testimonial->avatar))
    <img src="{{ imageUrl($testimonial->avatar) }}" alt="{{ $testimonial->name }}">
@endif
```

### In Controllers:
```php
// Upload
$image = $this->imageService->uploadImage($request->file('image'), 'services');

// Delete
$this->imageService->deleteImage($service->image);

// Replace
$image = $this->imageService->replaceImage($file, $old_path, 'services');
```

---

## 🌍 Environment Handling

### Local Development:
- Uploads stored in: `storage/app/public/`
- Access via: `/storage/services/image.jpg`
- Protected by symlink

### Shared Hosting (Live):
- Uploads stored in: `public/uploads/`
- Access via: `/uploads/services/image.jpg`
- Direct file access

**No code changes between environments!**

---

## 📋 Deployment Steps

### For Shared Hosting:

1. **Via cPanel or SSH:**
   ```bash
   cd public_html
   mkdir -p public/uploads/{services,projects,testimonials,about}
   chmod -R 755 public/uploads/
   chmod -R 777 storage/
   chmod -R 777 bootstrap/cache/
   ```

2. **Upload project files via FTP**

3. **Run migrations:**
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```

4. **Done!** Images will automatically save to `/public/uploads/` and display via `/uploads/` URL

---

## 🔍 Verify Setup

### Test Local Development:
```bash
# Should show directory contents
ls -la storage/app/public/
ls -la public/uploads/

# Should show symlink
ls -la public/storage
```

### Test Upload:
1. Go to `http://localhost:8000/admin`
2. Upload a service image
3. Check `storage/app/public/services/` for the file
4. Go to homepage and verify image displays

### Test Live Server:
1. Upload via FTP to `public_html/public/uploads/`
2. Try upload via admin panel
3. Verify file appears in `public/uploads/`
4. Verify URL works: `https://yourdomain.com/uploads/services/image.jpg`

---

## 🆘 Troubleshooting

### Images Not Showing:
```bash
# 1. Check if files exist
ls -la storage/app/public/services/
ls -la public/uploads/services/

# 2. Check permissions
chmod -R 777 storage/

# 3. Check database paths
php artisan tinker
>>> App\Models\Service::first()->image

# 4. Check helper function
>>> App\Helpers\ImageHelper::imageUrl('services/test.jpg')
```

### Upload Fails:
```bash
# Check storage permissions
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/

# Check logs
tail -f storage/logs/laravel.log
```

### Symlink Issues (Windows):
```bash
# Run as Administrator:
mklink /D "public\storage" "storage\app\public"

# Or use artisan:
php artisan storage:link
```

---

## 📚 Full Documentation

See these files for complete information:

- **IMAGE-MANAGEMENT-GUIDE.md** - Comprehensive reference
- **IMAGE-IMPLEMENTATION-SUMMARY.md** - Implementation details
- **app/Helpers/ImageHelper.php** - Source code
- **app/Services/ImageService.php** - Service implementation

---

## 🎯 Key Features

✅ Automatic environment detection (local vs hosted)  
✅ Works with zero configuration  
✅ Same code on localhost and live server  
✅ Image validation and error handling  
✅ Unique filename generation  
✅ Centralized image management  
✅ Security best practices  
✅ Production-ready  

---

## 📞 Need Help?

1. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test with Tinker:**
   ```bash
   php artisan tinker
   >>> App\Helpers\ImageHelper::imageUrl('services/image.jpg')
   >>> App\Helpers\ImageHelper::imageExists('services/image.jpg')
   ```

3. **Read Documentation:**
   - IMAGE-MANAGEMENT-GUIDE.md
   - IMAGE-IMPLEMENTATION-SUMMARY.md

---

**Last Updated:** November 29, 2025  
**Status:** Ready to Use ✅
