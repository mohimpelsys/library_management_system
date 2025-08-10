<?php

namespace App\Tests\Controller;

use App\Dto\BookFormDto;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookFormDtoTest extends BaseTestCase
{
    public function testValidBookFormDto(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'The Great Gatsby';
        $dto->author = 'F. Scott Fitzgerald';
        $dto->isbn = '1234567890123';
        $dto->setDescription('A classic novel.');

        $tempImage = tempnam(sys_get_temp_dir(), 'test_img_');
        imagejpeg(imagecreatetruecolor(10, 10), $tempImage);
        $dto->image = new UploadedFile($tempImage, 'cover.jpg', 'image/jpeg', null, true);

        $errors = $this->validateDto($dto);
        $this->assertCount(0, $errors, 'Valid BookFormDto should have no validation errors.');
    }

    public function testTitleIsBlank(): void
    {
        $dto = new BookFormDto();
        $dto->title = '';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';

        $errors = $this->validateDto($dto);

        $this->assertGreaterThan(0, count($errors));
        $this->assertEquals('Title is required.', $errors[0]->getMessage());
    }

    public function testTitleTooShort(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Abc';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Title must be at least 4 characters.', $messages);
    }

    public function testAuthorIsBlank(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = '';
        $dto->isbn = '1234567890123';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Author is required.', $messages);
    }

    public function testAuthorTooShort(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Abc';
        $dto->isbn = '1234567890123';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Author must be at least 4 characters.', $messages);
    }

    public function testIsbnIsBlank(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('ISBN is required.', $messages);
    }

    public function testIsbnTooShort(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '12';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('ISBN must be at least 3 characters.', $messages);
    }

    public function testIsbnTooLong(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '12345678901234';

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('ISBN must not exceed 13 characters.', $messages);
    }

    public function testInvalidImageMimeType(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';

        $tempFile = tempnam(sys_get_temp_dir(), 'test_txt_');
        file_put_contents($tempFile, 'This is a plain text file.');
        $dto->image = new UploadedFile($tempFile, 'test.txt', 'text/plain', null, true);

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Please upload a valid image (JPEG, PNG, or WebP).', $messages);
    }

    public function testImageFileTooLarge(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';

        $largeFilePath = tempnam(sys_get_temp_dir(), 'large_img_');
        file_put_contents($largeFilePath, str_repeat('0', 11 * 1024 * 1024)); // 11MB
        $dto->image = new UploadedFile($largeFilePath, 'big_image.jpg', 'image/jpeg', null, true);

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Image size must be under 10MB.', $messages);
    }

    public function testDescriptionTooLong(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';
        $dto->setDescription(str_repeat('a', 1001));

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($this->validateDto($dto)));
        $this->assertContains('Description must not exceed 1000 characters.', $messages);
    }

    public function testImageIsNull(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';
        $dto->image = null;

        $this->assertCount(0, $this->validateDto($dto));
    }

    public function testDescriptionIsNull(): void
    {
        $dto = new BookFormDto();
        $dto->title = 'Valid Title';
        $dto->author = 'Valid Author';
        $dto->isbn = '1234567890123';
        $dto->setDescription(null);

        $this->assertCount(0, $this->validateDto($dto));
    }
}
