<?php

namespace App\Form;

use App\Dto\BorrowDto;
//use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('borrowedAt', DateType::class, [
//                'widget' => 'single_text',
//                'input' => 'datetime',
//                'label' => 'Borrowed on',
//                'constraints' => [
//                    new NotBlank(['message' => 'Please select a borrowed date.']),
//                    new Callback(function ($borrowedAt, ExecutionContextInterface $context) {
//                        if (!$borrowedAt) return;
//
//                        $today = new \DateTimeImmutable('today');
//
//                        // 1. Cannot be in the future
//                        if ($borrowedAt > $today) {
//                            $context->buildViolation('Borrowed date cannot be in the future.')
//                                ->atPath('borrowedAt')
//                                ->addViolation();
//                        }
//
//                        // 2. Cannot be older than 1 day
//                        $minDate = (clone $today)->modify('-1 day');
//                        if ($borrowedAt < $minDate) {
//                            $context->buildViolation('Borrowed date cannot be more than 1 day in the past.')
//                                ->atPath('borrowedAt')
//                                ->addViolation();
//                        }
//                    }),
//                ],
//            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'constraints' => [
                    new Callback(function ($dueDate, ExecutionContextInterface $context) {
                        $formData = $context->getRoot()->getData();
                        $borrowedAt = $formData->getBorrowedAt();
                        $today = new \DateTimeImmutable('today');

                        if (!$dueDate || !$borrowedAt) {
                            return;
                        }

                        // 1. Due date must be after borrowedAt
                        if ($dueDate <= $borrowedAt) {
                            $context->buildViolation('Due date must be after the borrowed date.')
                                ->atPath('dueDate')
                                ->addViolation();
                        }

                        // 2. Due date must be in the future (>= today)
                        if ($dueDate < $today) {
                            $context->buildViolation('Due date cannot be in the past.')
                                ->atPath('dueDate')
                                ->addViolation();
                        }

                        // 3. Due date must be within 14 days of borrowedAt
                        $maxDueDate = (clone $borrowedAt)->modify('+21 days');
                        if ($dueDate > $maxDueDate) {
                            $context->buildViolation('Due date cannot be more than 3 weeks after the borrowed date.')
                                ->atPath('dueDate')
                                ->addViolation();
                        }
                    }),
                ],
            ]);


    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BorrowDto::class,
        ]);
    }
}
