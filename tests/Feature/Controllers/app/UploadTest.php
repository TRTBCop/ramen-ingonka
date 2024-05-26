<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class)->beforeEach(function () {
    Artisan::call('db:seed');
    //Storage::fake('s3');
});


test('fileTempMove 이미지 업로드', function () {
    $response = $this->post(route('app.upload.image'), [
        'file' => UploadedFile::fake()->image('file1.jpg'),
    ])
        ->assertSessionHasNoErrors()
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'path',
                'url',
            ],
        ]);
})->group('api', 'upload');