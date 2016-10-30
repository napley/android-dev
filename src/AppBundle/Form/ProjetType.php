<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
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
                ->add('contenuFin', CKEditorType::class, array(
                    'config_name' => 'config_complete',
                ))
                ->add('slug')
                ->add('visible', Type\CheckboxType::class, array(
                    'required' => false,
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_projet';
    }


}
