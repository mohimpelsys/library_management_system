<?php
//
//namespace App\Tests\Form;
//
//use App\Dto\BorrowDto;
//use App\Form\BorrowType;
//use Symfony\Component\Form\Test\TypeTestCase;
//use Symfony\Component\Form\PreloadedExtension;
//use Symfony\Component\Validator\Validation;
//
//class BorrowTypeTest extends TypeTestCase
//{
//    protected function getExtensions(): array
//    {
//        $validator = Validation::createValidator();
//
//        return [
//            new PreloadedExtension([new BorrowType()], []),
//        ];
//    }
//
//    public function testDueDateBeforeBorrowedAtIsInvalid(): void
//    {
//        $formData = [
//            'borrowedAt' => '2025-08-07',
//            'dueDate' => '2025-08-01', // ❌ Invalid: due date before borrowed
//        ];
//
//        $model = new BorrowDto(
//            id: 1,
//            title: 'Test Book',
//            userEmail: 'test@example.com',
//            borrowedAt: new \DateTime($formData['borrowedAt']),
//            dueDate: new \DateTime($formData['dueDate']),
//            returnedAt: null,
//            fine: null
//        );
//
//        // Create the form
//        $form = $this->factory->create(BorrowType::class, $model);
//
//        // Submit the form
//        $form->submit($formData);
//
//        // ✅ Create a validator and validate the DTO
//        $validator = Validation::createValidatorBuilder()
//            ->enableAnnotationMapping()
//            ->getValidator();
//
//        $violations = $validator->validate($model);
//
//        // We expect the form to be invalid because dueDate < borrowedAt
//        $this->assertGreaterThan(0, count($violations), 'Form should be invalid when due date is before borrowed date');
//
//        // Optionally check that the violation message matches
//        $this->assertSame('Due date cannot be before borrowed date.', $violations[0]->getMessage());
//    }
//
//
//
//}
