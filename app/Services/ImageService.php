<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Helpers\ImageHelper;

class ImageService
{
    /**
     * Upload an image file
     * 
     * @param UploadedFile $file The uploaded file
     * @param string $directory Subdirectory to store in (services, projects, testimonials, about)
     * @return string|null Relative path to the uploaded file or null on failure
     */
    public function uploadImage(UploadedFile $file, $directory = 'uploads')
    {
        try {
            $subdirectory = str_replace('uploads/', '', $directory);
            $storagePath = ImageHelper::getStoragePath($subdirectory);
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Determine whether to use public/uploads or storage based on availability
            $publicPath = public_path('uploads/' . $subdirectory);
            if (is_dir($publicPath) && is_writable($publicPath)) {
                // Shared hosting: use public/uploads
                $file->move($publicPath, $filename);
                return $subdirectory . '/' . $filename;
            } else {
                // Local development: use storage/app/public
                return $file->storeAs('public/' . $subdirectory, $filename);
            }
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an image file
     * 
     * @param string $path Relative path to image
     * @return bool True if deleted successfully
     */
    public function deleteImage($path)
    {
        return ImageHelper::deleteImage($path);
    }

    /**
     * Get image URL for use in views
     * 
     * @param string $path Relative path to image
     * @return string Full URL to image or empty string
     */
    public function getImageUrl($path)
    {
        return ImageHelper::getImageUrl($path);
    }

    /**
     * Replace an image (delete old, upload new)
     * 
     * @param UploadedFile $file New image file
     * @param string $oldPath Old image path
     * @param string $directory Directory to store in
     * @return string|null New image path or null on failure
     */
    public function replaceImage(UploadedFile $file, $oldPath, $directory = 'uploads')
    {
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }
        return $this->uploadImage($file, $directory);
    }

    /**
     * Validate image file
     * 
     * @param UploadedFile $file
     * @return array Validation errors or empty array if valid
     */
    public function validateImage(UploadedFile $file)
    {
        $errors = [];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file->getSize() > $maxSize) {
            $errors[] = 'Image size must not exceed 5MB';
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Only JPG, PNG, GIF, and WebP images are allowed';
        }

        return $errors;
    }
}
