<?php

namespace App\Tests\Controller;

use App\Dto\UserRegistrationDto;
use App\Tests\BaseTestCase;

class UserRegistrationDtoTest extends BaseTestCase
{
    public function testValidDto(): void
    {
        $dto = new UserRegistrationDto('valid@example.com', 'Valid@123');
        $errors = $this->validateDto($dto);

        $this->assertCount(0, $errors);
    }
    public function testBlankEmail(): void
    {
        $dto = new UserRegistrationDto('', 'Valid@123');
        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('Email should not be blank.', $errors[0]->getMessage());
    }

    public function testInvalidEmail(): void
    {
        $dto = new UserRegistrationDto('invalid-email', 'Valid@123');
        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
        $this->assertStringContainsString('The email', $errors[0]->getMessage());
        $this->assertStringContainsString('is not a valid email', $errors[0]->getMessage());
    }


    public function testBlankPassword(): void
    {
        $dto = new UserRegistrationDto('user@example.com', '');
        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('Password should not be blank.', $errors[0]->getMessage());
    }

    public function testShortPassword(): void
    {
        $dto = new UserRegistrationDto('user@example.com', 'A@1a');
        $errors = $this->validator->validate($dto);

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($errors));
        $this->assertContains('Password must be at least 8 characters long.', $messages);
    }

    public function testPasswordMissingCharacterTypes(): void
    {
        $dto = new UserRegistrationDto('user@example.com', 'password'); // lowercase only
        $errors = $this->validator->validate($dto);

        $messages = array_map(fn($e) => $e->getMessage(), iterator_to_array($errors));
        $this->assertContains(
            'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 digit, and 1 special character.',
            $messages
        );
    }
}
