<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Productimage;
use App\Models\VariantImage;
use Image; // Intervention Image Facade
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize existing product images: Resize to max 1200px width and convert to WebP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting image optimization...');

        // 1. Optimize Productimage (images)
        $productImages = Productimage::all();
        $this->info("Found " . $productImages->count() . " product images.");
        
        $bar = $this->output->createProgressBar($productImages->count());
        $bar->start();

        foreach ($productImages as $pImage) {
            $relativePath = $pImage->image;
            $updatedPath = $this->processImage($relativePath);
            
            if ($updatedPath && $updatedPath !== $relativePath) {
                $pImage->image = $updatedPath;
                $pImage->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // 2. Optimize VariantImage (image_path)
        $variantImages = VariantImage::all();
        $this->info("Found " . $variantImages->count() . " variant images.");

        $bar = $this->output->createProgressBar($variantImages->count());
        $bar->start();

        foreach ($variantImages as $vImage) {
            $relativePath = $vImage->image_path;
            $updatedPath = $this->processImage($relativePath);
            
            if ($updatedPath && $updatedPath !== $relativePath) {
                $vImage->image_path = $updatedPath;
                $vImage->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $this->info('Image optimization completed successfully.');
        return 0;
    }

    /**
     * Process a single image: Resize and Convert to WebP
     * 
     * @param string $relativePath
     * @return string|null The new relative path, or null if failed/file not found
     */
    private function processImage($relativePath)
    {
        if (empty($relativePath)) return null;

        $absolutePath = public_path($relativePath);
        if (!File::exists($absolutePath)) {
            // $this->warn("File not found: " . $relativePath); 
            // Silent fail to not clutter output, or log it
            return null;
        }

        try {
            // Check if already WebP
            $info = pathinfo($absolutePath);
            $extension = strtolower($info['extension'] ?? '');
            
            // If already webp, just resize if needed, otherwise skip? 
            // The requirement says "Resize... Convert... Compress". 
            // Even if webp, checking dimensions is good.
            
            $img = Image::make($absolutePath);
            
            $needsResize = $img->width() > 1200;
            $needsConversion = $extension !== 'webp';
            
            if (!$needsResize && !$needsConversion) {
                return $relativePath; // No changes needed
            }

            // Resize if needed
            if ($needsResize) {
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing
                });
            }

            // Define new path
            $newFileName = $info['filename'] . '.webp';
            $newRelativePath = dirname($relativePath) . '/' . $newFileName;
            $newAbsolutePath = public_path($newRelativePath);

            // Save as WebP (quality 80)
            $img->encode('webp', 80)->save($newAbsolutePath);

            // Clean up old file if it was different
            if ($newAbsolutePath !== $absolutePath) {
                File::delete($absolutePath);
            }

            return $newRelativePath;

        } catch (\Exception $e) {
            $this->error("Error processing {$relativePath}: " . $e->getMessage());
            return null;
        }
    }
}
