<?php

namespace App\Form;

use App\Entity\User;
use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileType
 *
 * @package      App\Form
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('privacy', PrivacySettingsType::class, ['required' => false])->add(
            'notificationSettings',
            NotificationSettingsType::class,
            ['required' => false]
        )->add('account', AccountSettingsType::class, ['required' => false])->remove('current_password')->remove(
            'email'
        )->remove('username')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ProfileFormType::class;
    }
}
