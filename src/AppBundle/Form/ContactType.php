<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', Type\TextType::Class, array('label' => 'Prénom'))
                ->add('nom', Type\TextType::Class, array('label' => 'Nom'))
                ->add('mail', Type\EmailType::Class, array('label' => 'Adresse email'))
                ->add('objet', Type\TextType::Class, array('label' => 'Objet du mail'))
                ->add('contenu',
                        Type\TextareaType::Class,
                        array('label' => 'Contenu du mail',
                               'attr' => array('rows' => '10'),
                        ))
                ->add('Envoyer', Type\SubmitType::class, array('label' => 'Envoyer'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
