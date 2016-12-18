<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'Display name'])
        ->add('route', EntityType::class, ['class' => 'AppBundle:Route', 'choice_label' => 'name', 'label' => 'Route', 'required' => false])
        ->add('displayFor', ChoiceType::class, [
            'label' => 'Display for',
            'choices' => ['Everyone' => 'everyone', 'Logged users' => 'user', 'Admins' => 'admin', 'Redactors' => 'redact', 'Not logged' => 'nologin']
            ])
        ->add('submit', SubmitType::class, ['label' => 'Add link']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Menu'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_menu';
    }


}
