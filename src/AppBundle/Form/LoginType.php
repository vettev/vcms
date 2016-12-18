<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [ 'label' => 'Username', 'attr' => ['placeholder' => 'Username']])
            ->add('password', PasswordType::class,[ 'label' => 'Password', 'attr' => ['placeholder' => 'Password']])
            ->add('submit', SubmitType::class, ['label' => "Login"]);
    }
}
?>