<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, ['label' => 'Title', 'attr' => ['placeholder' => 'Title']])
        ->add('content', TextareaType::class, ['label' => "Post content", 'required' => false, 'attr' => ['placeholder' => 'Post content', 'rows' => 15]])
        ->add('category', EntityType::class, ['class' => 'AppBundle:Category', 'choice_label' => 'name', 'label' => "Category"])
        ->add('image', FileType::class, ['label' => 'Post image', 'required' => false])
        ->add('distinguished', CheckboxType::class, ['label' => 'Post distinguished', 'required' => false])
        ->add('submit', SubmitType::class, ['label' => 'Add post']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
