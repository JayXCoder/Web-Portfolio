<?php

namespace App\Services\WorkExperience\Contracts;

use Illuminate\Http\UploadedFile;

interface ImageHandlerInterface
{
    public function uploadLogo(UploadedFile $logo): string;
    public function deleteLogo(string $logoPath): bool;
}
