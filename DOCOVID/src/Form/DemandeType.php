<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Livraison;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;




class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('materiel', ChoiceType::class, [
                'choices'  => [
                    'Blouse' => 'Blouse',
                    'Charlotte' => 'Charlotte',
                    'Surchaussure' => 'Surchaussure',
                    'Masque Chirurgical' => 'Masque Chirurgical',
                    'Masque FFP2'=>'Masque FFP2',
                    'Gants à usage unique' => 'Gants à usage unique',
                    'Lunettes de protection'=> 'Lunettes de protection',
                    'Gel hydroalcoolique'=> 'Gel hydroalcoolique'

                ], ])
            ->add('quantite')
            ->add('date_demande', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('temps_demande' , TimeType::class , [
                'widget' => 'single_text'
            ])
            ->add('message' , TextareaType::class)
            ->add('livraison',EntityType::class,array('class'=> Livraison::class,'choice_label'=>'nom_receveur'))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
