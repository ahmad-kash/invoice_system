<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use SplFileInfo;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Nette\DirectoryNotFoundException;

class FilesUploader
{

    private array|Collection $files;

    public function __construct(Null|array|Collection|SplFileInfo $files = null)
    {
        $this->files = $this->normalizeFiles($files);
    }
    private function normalizeFiles($files)
    {
        if (is_null($files))
            $files = [];

        if ($files instanceof SplFileInfo)
            $files = [$files];

        foreach ($files as $file) {
            if (!$this->isValidFile($file))
                throw new InvalidArgumentException('The data should be a valid file');
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
            throw new FileNotFoundException("File does not exist at path {$path}.");
    }

    public function upload(string $path)
    {
        $filesPaths = [];

        foreach ($this->files as $file) {
            Storage::putFile($path, $file);
            $filesPaths[] = $path . '/' . $file->hashName();
        }
        return $filesPaths;
    }

    public function get(string $path)
    {
        $this->throwExceptionIfFileNotFound($path);
        return Storage::get($path);
    }

    public function download(string $path)
    {
        $this->throwExceptionIfFileNotFound($path);

        return Storage::download($path);
    }

    public function delete(array|string $paths)
    {
        if (is_string($paths))
            $paths = [$paths];

        foreach ($paths as $path) {
            $this->throwExceptionIfFileNotFound($path);
        }

        return Storage::delete($paths);
    }

    public function deleteDirectory(string $path)
    {
        if (Storage::isDirectory($path))
            throw new DirectoryNotFoundException("Directory not Found in path {$path}.");

        return Storage::deleteDirectory($path);
    }
}
