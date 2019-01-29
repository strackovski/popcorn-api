<?php

namespace App\Form;

use App\Entity\NotificationSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NotificationSettingsType
 *
 * @package      App\Form
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class NotificationSettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enableNotifications', TextType::class, ['required' => false])->add(
            'emailNotifications',
            TextType::class,
            ['required' => false]
        )->add('deviceNotifications', TextType::class, ['required' => false])->add(
            'browserNotifications',
            TextType::class,
            ['required' => false]
        )->add('notificationFrequency', TextType::class, ['required' => false])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => NotificationSettings::class,
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
