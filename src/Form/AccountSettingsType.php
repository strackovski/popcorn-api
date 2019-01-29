<?php

namespace App\Form;

use App\Entity\AccountSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserProfileType
 *
 * @package      App\Form
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class AccountSettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, ['required' => false])->add(
            'lastname',
            TextType::class,
            ['required' => false]
        )->add('dateOfBirth', TextType::class, ['required' => false])->add(
            'country',
            TextType::class,
            ['required' => false]
        )->add('language', TextType::class, ['required' => false])->add(
            'timezone',
            TextType::class,
            ['required' => false]
        )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AccountSettings::class,
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return "settings";
    }
}
