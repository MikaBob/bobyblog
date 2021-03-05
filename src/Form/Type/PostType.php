<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Post;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'label_attr' => ['class' => 'input-group-text h-100'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('happenedDate', DateType::class, [
                'label_attr' => ['class' => 'input-group-text'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tags', TextType::class, [
                'label_attr' => ['class' => 'input-group-text'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('imageIds', HiddenType::class, ['mapped' => false])
            ->add('photos', FileType::class, [
                'multiple' => true,
                'label' => 'Photos',
                'mapped' => false,
                'required' => false,
                'label_attr' => ['class' => 'input-group-text'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('Post', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}