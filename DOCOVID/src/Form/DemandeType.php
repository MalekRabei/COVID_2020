<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Livraison;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('materiel')
            ->add('quantite')
            ->add('date_demande')
            ->add('temps_demande')
            ->add('message')
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
