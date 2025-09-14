<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    public static function upload(UploadedFile $file, string $path = 'uploads', string $disk = 'public'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, $disk);
    }

    public static function delete(?string $filepath, string $disk = 'public'): void
    {
        if ($filepath && Storage::disk($disk)->exists($filepath)) {
            Storage::disk($disk)->delete($filepath);
        }
    }

    public static function replace(?string $oldPath, ?UploadedFile $newFile, string $path = 'uploads', string $disk = 'public'): ?string
    {
        if (!$newFile) {
            return $oldPath;
        }

        self::delete($oldPath, $disk);

        return self::upload($newFile, $path, $disk);
    }

    public static function url(?string $filepath, string $disk = 'public'): ?string
    {
        return $filepath ? Storage::disk($disk)->url($filepath) : null;
    }
}
