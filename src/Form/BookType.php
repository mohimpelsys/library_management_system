<?php

namespace App\Form;

use App\Dto\BookFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('isbn', TextType::class, [
                'disabled' => $options['is_edit'],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 5, 'placeholder' => 'Write a short description...'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookFormDto::class, //DTO
            'is_edit' => false, // default: false (so 'Add' form enables ISBN)
        ]);
    }
}
