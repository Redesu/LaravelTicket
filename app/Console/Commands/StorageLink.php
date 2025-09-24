<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class StorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:vercel-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a storage link for Vercel deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');

        if (File::exists($publicPath)) {
            File::deleteDirectory($publicPath);
        }

        File::makeDirectory($publicPath, 0755, true);

        if (File::exists($storagePath)) {
            File::copyDirectory($storagePath, $publicPath);
        }

        $this->info('Storage link created successfully.');
        return 0;
    }
}
