<?php
namespace App\Form;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('director')
            ->add('releaseYear')
            ->add('synopsis')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Action' => 'Action',
                    'Science-Fiction' => 'Science-Fiction',
                    'Comedie' => 'Comedie',
                    'Horreur' => 'Horreur',
                    'Drame' => 'Drame',
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}