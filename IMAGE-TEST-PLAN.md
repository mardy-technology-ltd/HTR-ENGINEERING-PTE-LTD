# Image Management System - Test Plan & Verification

## Pre-Testing Checklist

- [ ] Laravel project running: `php artisan serve --port=8000`
- [ ] Database migrated: `php artisan migrate`
- [ ] Database seeded: `php artisan db:seed`
- [ ] Storage directories exist: `storage/app/public/` and `public/uploads/`
- [ ] Symlink created: `php artisan storage:link`
- [ ] Admin panel accessible: `http://localhost:8000/admin`

---

## Phase 1: Local Development Testing

### Test 1.1: Directory Structure Verification
**Objective:** Verify all directories exist with correct permissions

**Steps:**
```bash
# Check storage directories
ls -la storage/app/public/
ls -la storage/app/public/services/
ls -la storage/app/public/projects/
ls -la storage/app/public/testimonials/
ls -la storage/app/public/about/

# Check public directories
ls -la public/uploads/
ls -la public/uploads/services/
ls -la public/uploads/projects/
ls -la public/uploads/testimonials/
ls -la public/uploads/about/

# Check symlink
ls -la public/storage
# Should show: storage -> ../storage/app/public
```

**Expected Result:** ✅ All directories exist, symlink points correctly

---

### Test 1.2: ImageHelper Class Functionality
**Objective:** Verify ImageHelper methods work correctly

**Steps:**
```bash
php artisan tinker

# Test getImageUrl
>>> App\Helpers\ImageHelper::getImageUrl('services/test.jpg')
=> "" (empty string if file doesn't exist, which is correct)

# Test imageExists with non-existent file
>>> App\Helpers\ImageHelper::imageExists('services/nonexistent.jpg')
=> false

# Test getStoragePath
>>> App\Helpers\ImageHelper::getStoragePath('services')
=> "public/services" (or "uploads/services" if on shared hosting)

# Test getStorageDisk
>>> App\Helpers\ImageHelper::getStorageDisk()
=> "public"

# Exit
>>> exit
```

**Expected Result:** ✅ All methods return correct values

---

### Test 1.3: Service Image Upload
**Objective:** Test uploading an image via admin panel

**Steps:**
1. Navigate to: `http://localhost:8000/admin/services`
2. Click "Create New Service" or edit existing
3. Fill in service details
4. Upload an image (JPG, PNG, GIF, or WebP)
5. Save service

**Expected Results:**
- ✅ Image uploaded without errors
- ✅ File appears in `storage/app/public/services/`
- ✅ Database stores relative path (e.g., `services/filename.jpg`)
- ✅ No errors in `storage/logs/laravel.log`

**Verify:**
```bash
# Check file exists
ls -la storage/app/public/services/ | tail -5

# Check database
php artisan tinker
>>> App\Models\Service::where('image', '!=', null)->first()->image
=> "services/1234567890_timestamp.jpg"
```

---

### Test 1.4: Service Image Display on Homepage
**Objective:** Verify images display correctly on home page

**Steps:**
1. Go to: `http://localhost:8000/`
2. Scroll to "Our Services" section
3. Verify service cards with images display

**Expected Results:**
- ✅ Service images visible
- ✅ Images load without 404 errors
- ✅ Slider works (auto-rotate every 5 seconds)
- ✅ Navigation arrows visible on desktop (hidden on mobile)

**Browser DevTools Check:**
- Open: F12 → Network tab
- Verify all images load with 200 status
- Image URLs should be: `/storage/services/filename.jpg`

---

### Test 1.5: Project Image Upload and Display
**Objective:** Test project image upload and display

**Steps:**
1. Admin panel: Upload project image
2. Homepage: Check "Latest Projects" section
3. Verify image displays in slider

**Expected Results:**
- ✅ Image uploads to `storage/app/public/projects/`
- ✅ Images visible on homepage
- ✅ URLs are `/storage/projects/filename.jpg`
- ✅ No console errors

---

### Test 1.6: Testimonial Avatar Upload and Display
**Objective:** Test testimonial avatar upload and display

**Steps:**
1. Admin panel: Upload testimonial with avatar
2. Homepage: Check "What Our Clients Say" section
3. Verify avatar displays

**Expected Results:**
- ✅ Avatar uploaded to `storage/app/public/testimonials/`
- ✅ Avatar visible in testimonial card
- ✅ URL is `/storage/testimonials/filename.jpg`
- ✅ Fallback initial shows if no avatar

---

### Test 1.7: Image Deletion
**Objective:** Verify image files are deleted when record is deleted

**Steps:**
1. Note filename: `storage/app/public/services/` - remember a filename
2. Admin panel: Delete that service
3. Check if file was deleted

**Expected Results:**
- ✅ File deleted from `storage/app/public/services/`
- ✅ No orphaned files remain
- ✅ No errors in logs

```bash
# Verify file deleted
ls -la storage/app/public/services/ | wc -l
# Should be less than before
```

---

### Test 1.8: Image Replacement
**Objective:** Verify image replacement works correctly

**Steps:**
1. Admin panel: Edit service with image
2. Note original filename
3. Upload new image
4. Save

**Expected Results:**
- ✅ Old file deleted
- ✅ New file uploaded
- ✅ Database updated with new path
- ✅ Homepage shows new image

---

### Test 1.9: Image URL Helper in Blade
**Objective:** Test imageUrl() helper function

**Steps:**
Create a test blade file or use tinker:
```bash
php artisan tinker

>>> $service = App\Models\Service::whereNotNull('image')->first()
>>> App\Helpers\ImageHelper::getImageUrl($service->image)
=> "/storage/services/filename.jpg"

# Or test in blade
>>> view('home', ['services' => []])->render()
```

**Expected Results:**
- ✅ Helper returns correct URL
- ✅ URL works in browser (200 status)
- ✅ Image displays

---

### Test 1.10: Mobile Responsiveness
**Objective:** Verify images display correctly on mobile

**Steps:**
1. Open homepage on mobile or use DevTools mobile view
2. Check all image sliders
3. Verify arrows are hidden on mobile
4. Swipe/scroll through sliders

**Expected Results:**
- ✅ Images responsive
- ✅ Arrows hidden on mobile
- ✅ No layout issues
- ✅ Sliders functional

---

## Phase 2: Shared Hosting Simulation

### Test 2.1: Public/Uploads Directory Preference
**Objective:** Verify system prefers public/uploads on shared hosting

**Steps:**
1. Temporarily make `storage/app/public/` unwritable:
   ```bash
   chmod 555 storage/app/public/
   ```

2. Try to upload image via admin panel

3. Restore permissions:
   ```bash
   chmod 777 storage/app/public/
   ```

**Expected Results:**
- ✅ System falls back to `public/uploads/`
- ✅ File uploaded to `public/uploads/services/`
- ✅ Homepage still displays image via `/uploads/` URL
- ✅ URL automatically changes to `/uploads/services/filename.jpg`

---

### Test 2.2: ImageHelper Fallback Logic
**Objective:** Verify ImageHelper correctly detects storage location

**Steps:**
```bash
# Make storage unwritable
chmod 555 storage/app/public/

# Test in tinker
php artisan tinker
>>> App\Helpers\ImageHelper::getStoragePath('services')
=> "uploads/services" (should return this when storage is unavailable)

# Restore
exit
chmod 777 storage/app/public/
```

**Expected Results:**
- ✅ Correct path returned based on availability
- ✅ System adapts automatically

---

## Phase 3: Edge Cases & Error Handling

### Test 3.1: Large Image Upload
**Objective:** Test image size validation

**Steps:**
1. Create a large image > 5MB
2. Try to upload via admin panel

**Expected Results:**
- ✅ Upload rejected with error message
- ✅ File not stored
- ✅ Error logged

---

### Test 3.2: Invalid Image Format
**Objective:** Test MIME type validation

**Steps:**
1. Rename a text file to `.jpg`
2. Try to upload
3. Try to upload a `.exe` file

**Expected Results:**
- ✅ Upload rejected
- ✅ Clear error message
- ✅ No file stored

---

### Test 3.3: Permission Denied
**Objective:** Test handling of permission issues

**Steps:**
1. Make storage unwritable: `chmod 555 storage/app/public/`
2. Make public/uploads unwritable: `chmod 555 public/uploads/`
3. Try to upload

**Expected Results:**
- ✅ Upload fails gracefully
- ✅ Error message shown
- ✅ Error logged

---

### Test 3.4: Missing Directory
**Objective:** Test handling of missing directories

**Steps:**
1. Rename a subdirectory: `mv storage/app/public/services storage/app/public/services_old`
2. Try to upload service image
3. Restore: `mv storage/app/public/services_old storage/app/public/services`

**Expected Results:**
- ✅ System creates directory if missing, OR
- ✅ Clear error message if creation fails

---

### Test 3.5: Null/Empty Image Path
**Objective:** Test handling of missing images

**Steps:**
```blade
<!-- In a blade template -->
<img src="{{ imageUrl(null) }}" alt="test">
<img src="{{ imageUrl('') }}" alt="test">
<img src="{{ imageUrl('nonexistent/file.jpg') }}" alt="test">
```

**Expected Results:**
- ✅ Returns empty string
- ✅ No broken image indicators
- ✅ No console errors

---

## Phase 4: Production Deployment Testing

### Test 4.1: Live Server File Permissions
**Objective:** Verify permissions on shared hosting

**SSH Steps:**
```bash
ssh user@server.com
cd public_html

# Check permissions
ls -la public/uploads/
ls -la storage/

# Should show:
# drwxr-xr-x public/uploads/
# drwxrwxrwx storage/
```

**Expected Results:**
- ✅ public/uploads: 755
- ✅ storage: 777
- ✅ bootstrap/cache: 777

---

### Test 4.2: Live Server Image Upload
**Objective:** Test uploading via live server admin panel

**Steps:**
1. SSH to server
2. Backup database
3. Access live admin panel
4. Upload test image
5. Verify file in `public/uploads/`

**Expected Results:**
- ✅ File uploaded
- ✅ Located at `public_html/public/uploads/services/`
- ✅ Accessible via `https://domain.com/uploads/services/filename.jpg`

---

### Test 4.3: Live Server Image Display
**Objective:** Verify images display on live site

**Steps:**
1. Visit `https://rollershuttersingapore.com/`
2. Check homepage sliders
3. Check other pages with images

**Expected Results:**
- ✅ All images visible
- ✅ URLs are `/uploads/services/...`
- ✅ Network requests return 200
- ✅ No console errors

---

### Test 4.4: Database Consistency
**Objective:** Verify database paths are correct

**SSH Steps:**
```bash
# Connect to database
mysql> SELECT COUNT(*) FROM services WHERE image IS NOT NULL;
mysql> SELECT image FROM services LIMIT 1;
# Should show: services/filename.jpg

# Verify files exist
ls -la /home/user/public_html/public/uploads/services/
# File should exist
```

**Expected Results:**
- ✅ Database paths are relative (services/filename.jpg)
- ✅ Files exist on disk
- ✅ URLs resolve correctly

---

## Test Results Summary

| Test | Local Dev | Shared Hosting | Status |
|------|-----------|-----------------|--------|
| 1.1 Directory Structure | ✅ | ✅ | |
| 1.2 ImageHelper | ✅ | ✅ | |
| 1.3 Upload Service Image | ✅ | ✅ | |
| 1.4 Display on Homepage | ✅ | ✅ | |
| 1.5 Project Images | ✅ | ✅ | |
| 1.6 Testimonial Avatars | ✅ | ✅ | |
| 1.7 Image Deletion | ✅ | ✅ | |
| 1.8 Image Replacement | ✅ | ✅ | |
| 1.9 URL Helper | ✅ | ✅ | |
| 1.10 Mobile Responsive | ✅ | ✅ | |
| 2.1 Public/Uploads Preference | ✅ | ✅ | |
| 2.2 Fallback Logic | ✅ | ✅ | |
| 3.1 Large Image | ✅ | ✅ | |
| 3.2 Invalid Format | ✅ | ✅ | |
| 3.3 Permission Denied | ✅ | ✅ | |
| 3.4 Missing Directory | ✅ | ✅ | |
| 3.5 Null/Empty Paths | ✅ | ✅ | |
| 4.1 Live Permissions | | ✅ | |
| 4.2 Live Upload | | ✅ | |
| 4.3 Live Display | | ✅ | |
| 4.4 DB Consistency | | ✅ | |

---

## Final Verification

- [ ] All tests passed locally
- [ ] All tests passed on shared hosting
- [ ] No errors in logs
- [ ] Images display on all pages
- [ ] Mobile responsive works
- [ ] Performance acceptable
- [ ] Database clean (no orphaned records)
- [ ] Ready for production

---

**Test Date:** _____________  
**Tested By:** _____________  
**Result:** PASS / FAIL  
**Notes:** _________________________________

---

**Reference:** IMAGE-MANAGEMENT-GUIDE.md
