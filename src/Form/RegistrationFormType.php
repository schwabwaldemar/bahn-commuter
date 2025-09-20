<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', options: [
                'label' => 'registration.form.email',
                'row_attr' => ['class' => 'form-control'],
            ])
            ->add('locale', ChoiceType::class, [
                'label' => 'registration.form.locale',
                'choices' => [
                    'registration.form.locale_en' => 'en',
                    'registration.form.locale_de' => 'de',
                ],
                'data' => $options['current_locale'],
                'row_attr' => ['class' => 'form-control'],
            ])
            ->add('acceptTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'registration.form.error.accept_terms',
                    ]),
                ],
                'label' => 'registration.form.accept_terms',
                'label_html' => true,
                'row_attr' => ['class' => 'checkbox-control'],
                'label_attr' => ['class' => 'checkbox-label'],
            ])
            ->add('acceptPrivacy', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'registration.form.error.accept_privacy',
                    ]),
                ],
                'label' => 'registration.form.accept_privacy',
                'label_html' => true,
                'row_attr' => ['class' => 'checkbox-control'],
                'label_attr' => ['class' => 'checkbox-label'],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.form.error.password_blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'registration.form.error.password_length',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'registration.form.password',
                'row_attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'current_locale' => 'en',
        ]);

        $resolver->setAllowedValues('current_locale', ['en', 'de']);
    }
}
