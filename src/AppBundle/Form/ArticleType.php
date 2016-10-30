<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class ArticleType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre')
                ->add('vignette', ElFinderType::class, array(
                    'instance'=>'form',
                    'enable'=>true,
                    'required' => false
                ))
                ->add('sousTitre', CKEditorType::class, array(
                    'config_name' => 'config_light',
                ))
                ->add('contenu', CKEditorType::class, array(
                    'config_name' => 'config_complete',
                ))
                ->add('visible', Type\CheckboxType::class, array(
                    'required' => false,
                ))
                ->add('top', Type\CheckboxType::class, array(
                    'required' => false,
                ))
                ->add('Type', EntityType::class, array(
                    'class' => 'AppBundle:Type',
                    'choice_label' => 'nom',
                ))
                ->add('Categorie', EntityType::class, array(
                    'class' => 'AppBundle:Categorie',
                    'choice_label' => 'nom',
                ))
                ->add('publishedAt', DateTimeType::class, array(
                    'widget' => 'choice'
                ));
        
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

}
