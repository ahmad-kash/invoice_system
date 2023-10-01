<?php

namespace App\Services;

use App\Exceptions\Custom\DirectoryNotFoundException;
use App\Exceptions\Custom\FileNotFoundException;
use App\Exceptions\Custom\InvalidFileException;
use App\Services\Interfaces\FilesUploaderInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class FilesUploader implements FilesUploaderInterface
{
    private function normalizeFiles($files)
    {
        if (is_null($files))
            $files = [];

        if ($files instanceof SplFileInfo)
            $files = [$files];

        foreach ($files as $file) {
            if (!$this->isValidFile($file))
                throw new InvalidFileException('البيانات يجب ان تكون ملف صالح');
        }
        return $files;
    }

    private function isValidFile($file)
    {
        return $file instanceof SplFileInfo && $file->getPath() !== '';
    }

    private function throwExceptionIfFileNotFound($path)
    {
        if (Storage::missing($path))
            throw new FileNotFoundException("الملف غير موجود في  {$path}.");
    }

    public function upload(string $dirPath, Null|array|Collection|SplFileInfo $files = null): array
    {
        $files = $this->normalizeFiles($files);

        $filesNames = [];

        foreach ($files as $file) {
            Storage::putFile($dirPath, $file);
            $filesNames[] = ['hash_name' => $file->hashName(), 'name' => $file->getClientOriginalName()];
        }
        return $filesNames;
    }

    public function get(string $path): string
    {
        $this->throwExceptionIfFileNotFound($path);
        return Storage::get($path);
    }

    public function download(string $path): StreamedResponse
    {
        $this->throwExceptionIfFileNotFound($path);

        return Storage::download($path);
    }

    public function delete(array|string $paths): bool
    {
        if (is_string($paths))
            $paths = [$paths];

        foreach ($paths as $path) {
            $this->throwExceptionIfFileNotFound($path);
        }

        return Storage::delete($paths);
    }

    public function deleteDirectory(string $path): bool
    {
        if (!Storage::directoryExists($path))
            throw new DirectoryNotFoundException("المجلد {$path} غير موجود.");

        return Storage::deleteDirectory($path);
    }
}
