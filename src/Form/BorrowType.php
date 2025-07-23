<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borrowedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Borrowed on'
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Callback(function ($dueDate, ExecutionContextInterface $context) {
                        $formData = $context->getRoot()->getData();
                        $borrowedAt = $formData->getBorrowedAt();

                        if ($dueDate && $borrowedAt) {
                            $maxDueDate = (clone $borrowedAt)->modify('+14 days');
                            if ($dueDate > $maxDueDate) {
                                $context->buildViolation('Due date cannot be more than 2 weeks after borrowed date.')
                                    ->addViolation();
                            }
                        }
                    }),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
