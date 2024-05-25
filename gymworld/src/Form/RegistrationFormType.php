<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please enter a password',]),
                        new Length([
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,]),
                        new Regex('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', "Password must contain 1 uppercase letter,1 lowercase and 1 number and at least 8 characters long.")
                    ]

                ],
                'second_options' => ['label' => 'Confirm Password',
                    'mapped' => false
                ],
            ])
            ->
            add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('Submit', SubmitType::class, [
                    'attr' => ['class' => 'btn']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
