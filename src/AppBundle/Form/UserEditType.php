<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', FileType::class, ['label' => 'User avatar', 'required' => false])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'E-mail']])
            ->add('about', TextareaType::class, ['required' => false, 'label' => 'Something about you', 'attr' => ['rows' => 6]])
            ->add('role', EntityType::class, ['class' => 'AppBundle:Role', 'choice_label' => 'name', 'label' => "Role"])
            ->add('submit', SubmitType::class, ['label' => 'Edit']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}
?>