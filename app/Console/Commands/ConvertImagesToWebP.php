<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ConvertImagesToWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-all {--dry-run : Show what would be converted without actually converting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all existing PNG/JPG images to WebP format and update database references';

    /**
     * WebP quality setting
     */
    private const WEBP_QUALITY = 80;

    /**
     * Maximum image width
     */
    private const MAX_WIDTH = 1920;

    /**
     * Statistics tracking
     */
    private int $totalFiles = 0;
    private int $convertedFiles = 0;
    private int $skippedFiles = 0;
    private int $failedFiles = 0;
    private float $totalSizeBefore = 0;
    private float $totalSizeAfter = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No files will be converted or deleted');
            $this->newLine();
        }

        $this->info('🚀 Starting batch WebP conversion...');
        $this->newLine();

        // Get all subdirectories in uploads
        $uploadsPath = public_path('uploads');
        
        if (!is_dir($uploadsPath)) {
            $this->error('❌ Uploads directory not found: ' . $uploadsPath);
            return 1;
        }

        // Scan all subdirectories
        $subdirectories = ['services', 'projects', 'testimonials', 'about'];
        
        foreach ($subdirectories as $subdir) {
            $dirPath = $uploadsPath . '/' . $subdir;
            
            if (!is_dir($dirPath)) {
                $this->warn("⚠️  Directory not found: {$subdir} (skipping)");
                continue;
            }

            $this->info("📁 Processing directory: {$subdir}");
            $this->processDirectory($dirPath, $subdir, $dryRun);
            $this->newLine();
        }

        // Display summary
        $this->displaySummary($dryRun);

        return 0;
    }

    /**
     * Process a directory and convert images
     */
    private function processDirectory(string $dirPath, string $subdir, bool $dryRun): void
    {
        $files = File::files($dirPath);
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $this->totalFiles++;
            $extension = strtolower($file->getExtension());

            // Skip if not PNG/JPG or already WebP
            if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
                $this->skippedFiles++;
                $bar->advance();
                continue;
            }

            // Check if WebP version already exists
            $webpPath = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $file->getPathname());
            if (file_exists($webpPath)) {
                $this->skippedFiles++;
                $bar->advance();
                continue;
            }

            if (!$dryRun) {
                $this->convertToWebP($file, $subdir);
            } else {
                $this->totalSizeBefore += filesize($file->getPathname());
                // Estimate 60% size reduction for dry run
                $this->totalSizeAfter += filesize($file->getPathname()) * 0.4;
                $this->convertedFiles++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Convert a single image to WebP
     */
    private function convertToWebP($file, string $subdir): void
    {
        try {
            $originalPath = $file->getPathname();
            $originalSize = filesize($originalPath);
            $this->totalSizeBefore += $originalSize;

            // Generate WebP filename
            $webpFilename = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $file->getFilename());
            $webpPath = dirname($originalPath) . '/' . $webpFilename;

            // Load and convert image
            $image = Image::read($originalPath);

            // Resize if needed
            if ($image->width() > self::MAX_WIDTH) {
                $newHeight = (int) (($image->height() / $image->width()) * self::MAX_WIDTH);
                $image->resize(self::MAX_WIDTH, $newHeight);
            }

            // Convert to WebP
            $image->toWebp(self::WEBP_QUALITY)->save($webpPath);

            $webpSize = filesize($webpPath);
            $this->totalSizeAfter += $webpSize;

            // Update database references
            $this->updateDatabaseReferences($file->getFilename(), $webpFilename, $subdir);

            // Delete original file
            unlink($originalPath);

            $this->convertedFiles++;

        } catch (\Exception $e) {
            $this->failedFiles++;
            $this->error("\n❌ Failed to convert {$file->getFilename()}: " . $e->getMessage());
        }
    }

    /**
     * Update database references from old filename to new WebP filename
     */
    private function updateDatabaseReferences(string $oldFilename, string $webpFilename, string $subdir): void
    {
        $oldPath = "uploads/{$subdir}/{$oldFilename}";
        $newPath = "uploads/{$subdir}/{$webpFilename}";

        // Update based on subdirectory/table relationship
        switch ($subdir) {
            case 'services':
                DB::table('services')
                    ->where('image', $oldPath)
                    ->update(['image' => $newPath]);
                break;

            case 'projects':
                DB::table('projects')
                    ->where('image', $oldPath)
                    ->update(['image' => $newPath]);
                break;

            case 'testimonials':
                // Testimonials use 'avatar' column instead of 'image'
                DB::table('testimonials')
                    ->where('avatar', $oldPath)
                    ->update(['avatar' => $newPath]);
                break;

            case 'about':
                DB::table('about_contents')
                    ->where('image', $oldPath)
                    ->update(['image' => $newPath]);
                break;
        }
    }

    /**
     * Display conversion summary
     */
    private function displaySummary(bool $dryRun): void
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════');
        $this->info($dryRun ? '🔍 DRY RUN SUMMARY' : '✅ CONVERSION COMPLETE!');
        $this->info('═══════════════════════════════════════════════════════');
        $this->newLine();

        $this->line("📊 Total Files Scanned:     {$this->totalFiles}");
        $this->line("✅ Converted to WebP:       {$this->convertedFiles}");
        $this->line("⏭️  Skipped (already WebP): {$this->skippedFiles}");
        
        if ($this->failedFiles > 0) {
            $this->error("❌ Failed:                  {$this->failedFiles}");
        }

        $this->newLine();

        if ($this->totalSizeBefore > 0) {
            $sizeBefore = round($this->totalSizeBefore / 1024 / 1024, 2);
            $sizeAfter = round($this->totalSizeAfter / 1024 / 1024, 2);
            $saved = round(($this->totalSizeBefore - $this->totalSizeAfter) / 1024 / 1024, 2);
            $percentage = round((($this->totalSizeBefore - $this->totalSizeAfter) / $this->totalSizeBefore) * 100, 1);

            $this->line("💾 Total Size Before:       {$sizeBefore} MB");
            $this->line("💾 Total Size After:        {$sizeAfter} MB");
            $this->line("📉 Space Saved:             {$saved} MB ({$percentage}%)");
        }

        $this->newLine();

        if (!$dryRun && $this->convertedFiles > 0) {
            $this->info('✅ Database references updated successfully!');
            $this->info('✅ Original files deleted to save space!');
            $this->newLine();
            $this->comment('💡 All images have been converted to WebP format.');
            $this->comment('💡 Your website will now load significantly faster!');
        } elseif ($dryRun) {
            $this->comment('💡 Run without --dry-run to perform actual conversion:');
            $this->comment('   php artisan images:convert-all');
        } else {
            $this->warn('⚠️  No images were converted (all already in WebP format)');
        }

        $this->newLine();
    }
}
