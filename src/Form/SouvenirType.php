<?php

namespace App\Form;

use App\Entity\Souvenir;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SouvenirType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('partenaire', EntityType::class,
                [
                    'class' => User::class,
                    'multiple' => true,
                    'choice_label' => function ($user) {
                        return $user->getFirstname() . " " . strtoupper($user->getLastName());
                    }
                ])
            /*  ->add('partenaire', CollectionType::class,
                  [
                      'choice_label' => function ($user) {
                          return $user->getFirstname() . " " . strtoupper($user->getLastName());
                      },
                      'entry_type' => User::class,
                      'allow_add' => true,
                      'allow_delete' => true
                  ]
              )*/
            ->add('resume', TextType::class, $this->getConfiguration("Résumé", "Quelques mots sur votre réservation"))
            ->add('anecdote', TextareaType::class, $this->getConfiguration("Anecdote", "Donnez nous une anecdote sur votre réservation"))
            ->add('photos', CollectionType::class,
                [
                    'entry_type' => PhotoType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Souvenir::class,
        ]);
    }
}
