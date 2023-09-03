<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Services\FilesUploader;
use App\Services\UploadFileService;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use TypeError;

class FilesUploaderTest extends TestCase
{


    /** @test */
    public function it_throw_not_found_exception_if_file_is_missing_when_download(): void
    {

        $path  = "someFilePath";

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("File does not exist at path {$path}.");

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(true);


        (new FilesUploader())->get($path);
    }

    /** @test */
    public function it_can_download_a_file(): void
    {

        $path  = "someFilePath";

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(false);


        Storage::shouldReceive('download')
            ->once()
            ->with($path);

        (new FilesUploader())->download($path);
    }



    /** @test */
    public function it_throw_not_found_exception_if_file_is_missing_when_retrieve(): void
    {

        $path  = "someFilePath";

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("File does not exist at path {$path}.");

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(true);


        (new FilesUploader())->get($path);
    }

    /** @test */
    public function it_can_retrieve_file(): void
    {

        $path  = "someFilePath";

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(false);


        Storage::shouldReceive('get')
            ->once()
            ->with($path);

        (new FilesUploader())->get($path);
    }

    /** @test */
    public function it_can_upload_one_file(): void
    {
        $file = UploadedFile::fake()->image('test1.jpg');

        Storage::shouldReceive('putFile')
            ->once()
            ->with('path', $file);

        (new FilesUploader($file))->upload('path');
    }

    /** @test */
    public function it_can_upload_multiple_files(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');


        Storage::shouldReceive('putFile')
            ->once()
            ->with('path', $file1);


        Storage::shouldReceive('putFile')
            ->once()
            ->with('path', $file2);

        (new FilesUploader([$file1, $file2]))->upload('path');
    }

    /** @test */
    public function file_exists_after_uploading(): void
    {
        Storage::fake();
        $file1 = UploadedFile::fake()->image('test1.jpg');
        (new FilesUploader($file1))->upload('path');
        Storage::assertExists('path/' . $file1->hashName());
    }
    /** @test */
    public function it_can_delete_one_file(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');

        Storage::shouldReceive('missing')
            ->once()
            ->with('path/' . $file1->hashName())
            ->andReturn(false);

        Storage::shouldReceive('delete')
            ->once()
            ->with(['path/' . $file1->hashName()]);


        (new FilesUploader())->delete('path/' . $file1->hashName());
    }

    /** @test */
    public function it_can_delete_multiple_files(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');

        Storage::shouldReceive('missing')
            ->once()
            ->with('path/' . $file1->hashName())
            ->andReturn(false);

        Storage::shouldReceive('missing')
            ->once()
            ->with('path/' . $file2->hashName())
            ->andReturn(false);

        Storage::shouldReceive('delete')
            ->once()
            ->with(['path/' . $file1->hashName(), 'path/' . $file2->hashName()]);


        (new FilesUploader())->delete(['path/' . $file1->hashName(), 'path/' . $file2->hashName()]);
    }
    /** @test */
    public function it_throw_an_exception_if_the_data_is_not_a_valid_file(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The data should be a valid file');
        (new FilesUploader([UploadedFile::fake()->image('test1.jpg'), 'test', 12]));
    }
    /** @test */
    public function it_throw_an_exception_if_the_data_passed_not_array_or_collection_or_a_SplFileInfo(): void
    {
        $this->expectException(TypeError::class);
        (new FilesUploader('test'));
    }

    /** @test */
    public function it_constructor_can_take_empty_var_or_empty_array_or_null_without_rising_exception(): void
    {
        $this->expectNotToPerformAssertions();
        (new FilesUploader([]));
        (new FilesUploader(null));
        (new FilesUploader());
    }
}
