<?php

namespace App\Services\WorkExperience;

use App\Services\WorkExperience\Contracts\ImageHandlerInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandler implements ImageHandlerInterface
{
    /**
     * Handle company logo upload
     */
    public function uploadLogo(UploadedFile $logo): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
        $path = $logo->storeAs('company-logos', $filename, 'public');
        return $path;
    }

    /**
     * Delete company logo
     */
    public function deleteLogo(string $logoPath): bool
    {
        if ($logoPath) {
            return Storage::disk('public')->delete($logoPath);
        }
        return true;
    }
}
