<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get the image URL that works in both local and live environments
     * 
     * @param string $path Relative path to image (e.g., 'services/image.jpg')
     * @return string Full URL to the image or empty string if not found
     */
    public static function getImageUrl($path)
    {
        if (!$path) {
            return '';
        }

        // Check if running on shared hosting (public/uploads) or local (storage/app/public)
        $publicPath = public_path('uploads/' . $path);
        $storagePath = storage_path('app/public/' . $path);

        // Prefer public/uploads if it exists (for shared hosting)
        if (file_exists($publicPath)) {
            return asset('uploads/' . $path);
        }

        // Fall back to storage symlink if it exists (for local development)
        if (file_exists($storagePath)) {
            return asset('storage/' . $path);
        }

        // Return empty string if file doesn't exist
        return '';
    }

    /**
     * Get the full file path for storing images
     * 
     * @param string $subdirectory Subdirectory like 'services', 'projects', etc.
     * @return string Path to store files
     */
    public static function getStoragePath($subdirectory = 'uploads')
    {
        // Check if public/uploads exists (shared hosting)
        $publicPath = public_path('uploads');
        if (is_dir($publicPath) && is_writable($publicPath)) {
            return 'uploads/' . $subdirectory;
        }

        // Fall back to storage/app/public (local development)
        return 'public/' . $subdirectory;
    }

    /**
     * Get the file system disk for image storage
     * 
     * @return string Disk name ('public' for both local and shared hosting)
     */
    public static function getStorageDisk()
    {
        return 'public';
    }

    /**
     * Delete an image file
     * 
     * @param string $path Relative path to image
     * @return bool True if deleted successfully
     */
    public static function deleteImage($path)
    {
        if (!$path) {
            return false;
        }

        // Try to delete from public/uploads first
        $publicPath = public_path('uploads/' . $path);
        if (file_exists($publicPath)) {
            return unlink($publicPath);
        }

        // Try to delete from storage symlink
        $storagePath = storage_path('app/public/' . $path);
        if (file_exists($storagePath)) {
            return unlink($storagePath);
        }

        return false;
    }

    /**
     * Check if image exists
     * 
     * @param string $path Relative path to image
     * @return bool True if image exists
     */
    public static function imageExists($path)
    {
        if (!$path) {
            return false;
        }

        return file_exists(public_path('uploads/' . $path)) || 
               file_exists(storage_path('app/public/' . $path));
    }

    /**
     * Get image dimensions
     * 
     * @param string $path Relative path to image
     * @return array|false Array with 'width' and 'height' or false if not found
     */
    public static function getImageDimensions($path)
    {
        $publicPath = public_path('uploads/' . $path);
        $storagePath = storage_path('app/public/' . $path);

        $filePath = file_exists($publicPath) ? $publicPath : $storagePath;

        if (!file_exists($filePath)) {
            return false;
        }

        $size = getimagesize($filePath);
        if ($size === false) {
            return false;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
        ];
    }
}
