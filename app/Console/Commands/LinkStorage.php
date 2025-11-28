<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LinkStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symbolic link from public/storage to storage/app/public (if public/uploads does not exist)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if public/uploads directory exists and is writable (shared hosting)
        $publicUploads = public_path('uploads');
        if (is_dir($publicUploads) && is_writable($publicUploads)) {
            $this->info('✓ public/uploads directory exists and is writable.');
            $this->info('  Images will be stored in public/uploads/ directly (shared hosting mode)');
            $this->line('  No symlink needed for this environment.');
            return 0;
        }

        // For local development, create symlink
        $link = public_path('storage');
        $target = storage_path('app/public');

        // Remove existing link if it points to wrong target
        if (is_link($link)) {
            $currentTarget = readlink($link);
            if ($currentTarget === $target) {
                $this->info('✓ Symlink already exists and is correct.');
                return 0;
            } else {
                $this->warn('⚠ Existing symlink points to: ' . $currentTarget);
                $this->warn('  Removing old symlink...');
                unlink($link);
            }
        } elseif (file_exists($link)) {
            $this->error('✗ File/directory already exists at: ' . $link);
            $this->info('  Please remove it manually and try again.');
            return 1;
        }

        // Create the symlink
        try {
            $this->createSymlink($target, $link);
            $this->info('✓ Symbolic link created successfully!');
            $this->info('  Link: ' . $link);
            $this->info('  Target: ' . $target);
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to create symlink: ' . $e->getMessage());
            $this->info('  You may need to create it manually with:');
            $this->line('  mklink /D "' . str_replace('/', '\\', $link) . '" "' . str_replace('/', '\\', $target) . '"');
            return 1;
        }
    }

    /**
     * Create a symbolic link
     *
     * @param string $target
     * @param string $link
     * @return void
     */
    protected function createSymlink($target, $link)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows symlink command
            $command = sprintf('mklink /D "%s" "%s"', $link, $target);
            exec($command, $output, $returnCode);
            if ($returnCode !== 0) {
                throw new \Exception('Failed to create symlink on Windows');
            }
        } else {
            // Unix/Linux symlink
            if (!symlink($target, $link)) {
                throw new \Exception('Failed to create symlink');
            }
        }
    }
}
