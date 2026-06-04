<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageAssetController extends Controller
{
    public function portfolioImage(string $filename): BinaryFileResponse|Response
    {
        $filename = basename($filename);
        $candidates = [
            storage_path('app/public/portfolios/'.$filename),
            storage_path('app/public/'.$filename),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function companyLogo(string $filename): BinaryFileResponse|Response
    {
        $filename = basename($filename);
        $path = storage_path('app/public/company-logos/'.$filename);

        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
