<?php

namespace Tests\Unit;

use App\Exceptions\Custom\DirectoryNotFoundException;
use App\Exceptions\Custom\FileNotFoundException;
use App\Exceptions\Custom\InvalidFileException;
use App\Services\FilesUploader;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use TypeError;

class FilesUploaderTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Storage::fake();
    }

    /** @test */
    public function it_throw_directory_not_found_exception_if_directory_is_missing_deleting_directory(): void
    {

        $path  = "someFilePath";

        $this->expectException(DirectoryNotFoundException::class);
        Storage::shouldReceive('directoryExists')->with($path)->andReturn(false);

        app()->make(FilesUploader::class)->deleteDirectory($path);
    }

    /** @test */
    public function it_can_delete_a_directory(): void
    {

        $path  = "someFilePath";

        Storage::shouldReceive('directoryExists')
            ->with($path)
            ->andReturn(true);

        Storage::shouldReceive('deleteDirectory')
            ->once()
            ->with($path)
            ->andReturn(true);


        app()->make(FilesUploader::class)->deleteDirectory($path);
    }


    /** @test */
    public function it_throw_not_found_exception_if_file_is_missing_when_download(): void
    {

        $path  = "someFilePath";

        $this->expectException(FileNotFoundException::class);

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(true);


        app()->make(FilesUploader::class)->get($path);
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
            ->with($path)
            ->andReturn(new \Symfony\Component\HttpFoundation\StreamedResponse());

        app()->make(FilesUploader::class)->download($path);
    }



    /** @test */
    public function it_throw_not_found_exception_if_file_is_missing_when_retrieve(): void
    {

        $path  = "someFilePath";

        $this->expectException(FileNotFoundException::class);

        Storage::shouldReceive('missing')
            ->once()
            ->with($path)
            ->andReturn(true);


        app()->make(FilesUploader::class)->get($path);
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
            ->with($path)
            ->andReturn('someFileName');

        app()->make(FilesUploader::class)->get($path);
    }

    /** @test */
    public function it_can_upload_one_file(): void
    {
        $file = UploadedFile::fake()->image('test1.jpg');

        Storage::shouldReceive('putFile')
            ->once()
            ->with('path', $file);

        app()->make(FilesUploader::class)->upload('path', $file);
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

        app()->make(FilesUploader::class)->upload('path', [$file1, $file2]);
    }

    /** @test */
    public function file_exists_after_uploading(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');
        app()->make(FilesUploader::class)->upload('path', $file1);
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
            ->with(['path/' . $file1->hashName()])
            ->andReturn(true);


        app()->make(FilesUploader::class)->delete('path/' . $file1->hashName());
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
            ->with(['path/' . $file1->hashName(), 'path/' . $file2->hashName()])
            ->andReturn(true);


        app()->make(FilesUploader::class)->delete(['path/' . $file1->hashName(), 'path/' . $file2->hashName()]);
    }
    /** @test */
    public function it_throw_an_exception_if_the_data_is_not_a_valid_file(): void
    {
        $this->expectException(InvalidFileException::class);
        app()->make(FilesUploader::class)->upload('path', [UploadedFile::fake()->image('test1.jpg'), 'test', 12]);
    }
    /** @test */
    public function it_throw_an_exception_if_the_data_passed_to_upload_method_not_array_or_collection_or_a_file_or_null(): void
    {
        $this->expectException(TypeError::class);
        app()->make(FilesUploader::class)->upload('path', 'test');
    }

    /** @test */
    public function it_upload_method_can_take_null_or_empty_array_or_nothing_instead_of_file_param(): void
    {
        $this->expectNotToPerformAssertions();
        app()->make(FilesUploader::class)->upload('path');
        app()->make(FilesUploader::class)->upload('path', []);
        app()->make(FilesUploader::class)->upload('path', null);
    }
}
