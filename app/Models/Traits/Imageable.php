<?php
namespace App\Models\Traits;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Imageable
{
    /**
     * Define the polymorphic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Save multiple images and attach them to the model.
     *
     * @param array $images Array of UploadedFile instances.
     * @param string $modelName Name of the model, used for folder creation.
     * @return void
     */
    public function saveImages(array $images, string $modelName)
    {
        $imageInstances = [];

        foreach ($images as $image) {
            // Ensure the image is an instance of UploadedFile
            if ($image instanceof UploadedFile) {
                // Generate a unique filename for the image
                $filename = $image->hashName();
                // Folder name as `monthYear` format
                $folderName = Carbon::now()->format('FY');
                // Define the path to save the image
                $path = $image->storeAs("images/{$modelName}/{$folderName}", $filename, 'public');

                // Create the image record in the database
                $imageInstances[] = Image::create([
                    'filename' => $filename,
                    'url' => Storage::url($path),
                ]);
            }
        }

        // Attach the images to the model
        if (!empty($imageInstances)) {
            $this->images()->saveMany($imageInstances);
        }
    }
}
