<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('nom_receveur')
            ->add('prenom_receveur')
            ->add('telephone')
            ->add('adresse')
            ->add('ville', ChoiceType::class, [
                'choices'  => [
                    'Paris' => 'Paris',
                    'Marseille' => 'Marseille',
                    'Lille' => 'Lille',
                    'Nice' => 'Nice',
                    'Bordeaux'=>'Bordeaux',
                    'Strasbourg' => 'Strasbourg',
                    'Montpellier'=> 'Montpellier',
                    'Lyon'=> 'Lyon',
                    'Toulouse' => 'Toulouse',
                    'Rennes' => 'Rennes',
                    'Grenoble' => 'Grenoble',
                    'Angers' => 'Angers',
                    'Le Havre'=>'Le Havre',
                    'Nîmes' => 'Nîmes',
                    'Toulon'=> 'Toulon',
                    'Nancy'=> 'Nancy',
                    'Reims' => 'Reims',
                    'Brest' => 'Brest',
                    'AixEnProvence' => 'AixEnProvence',
                    'Saint-Etiennel' => 'Saint-Etienne',
                    'Dijon'=>'Dijon',
                    'Le Mans' => 'Le Mans',
                    'Besançon'=> 'Besançon',
                    'Limoges'=> 'Limoges',
                    'Orléans' => 'Orléans',
                    'Metz' => 'Metz',
                    'Tours' => 'Tours',
                    'Clermont-Ferrand' => 'Clermont-Ferrand',
                    'Rouen'=>'Rouen',
                    'Avignon' => 'Avignon',
                    'Amiens'=> 'Amiens',
                    'Perpignan'=> 'Perpignan',
                    'Mulhouse'=>'Mulhouse',
                    'Dunkerque' => 'Dunkerque',
                    'GrandPoitiers'=> 'GrandPoitiers',
                    'Annecy'=> 'Annecy',
                    'LaRochelle' => 'LaRochelle',
                    'SaintDenis' => 'Metz',
                    'Villeurbanne' => 'Villeurbanne',
                    'Nanterre' => 'Nanterre',
                    'Créteil'=>'Créteil',
                    'Roubaix' => 'Roubaix',
                    'VitrySurSein'=> 'VitrySurSein',
                    'Tourcoing'=> 'Tourcoing',
                    'Valenciennes'=>'Valenciennes',
                    'Bérziers' => 'Bérziers',
                    'Colmar'=> 'Colmar',
                    'Pau'=> 'Pau',

                ], ])
            ->add('cite')
            ->add('code_postal')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
