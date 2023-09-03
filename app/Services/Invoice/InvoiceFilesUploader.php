<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use SplFileInfo;

class InvoiceFilesUploader
{

    private array|Collection $files;

    public function __construct(array|Collection|SplFileInfo $files)
    {
        if ($files instanceof SplFileInfo)
            $files = [$files];

        if ($files && !$this->isValidFile($files[0]))
            throw new InvalidArgumentException('The data should be a valid file');

        $this->files = $files;
    }

    private function filesExists()
    {
        return !empty((array)$this->files);
    }
    public function execute(Invoice $invoice)
    {
        if ($this->filesExists())
            foreach ($this->files as $file) {
                Storage::put($invoice->section->name . '/' . $invoice->number . '/' . $file->hashName(), $file);
            }
        return true;
    }
    protected function isValidFile($file)
    {
        return $file instanceof SplFileInfo && $file->getPath() !== '';
    }
}
