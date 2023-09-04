<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FilesUploaderInterface
{
    public function upload(string $path, Null|array|Collection|SplFileInfo $files = null): array;

    public function get(string $path): string;

    public function download(string $path): StreamedResponse;

    public function delete(array|string $paths): bool;

    public function deleteDirectory(string $path): bool;
}
