<?php

namespace App\Form;

use App\Entity\PrivacySettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrivacySettingsType
 *
 * @package      App\Form
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class PrivacySettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('showActivityStatus', TextType::class, ['required' => false])->add(
            'privateAccount',
            TextType::class,
            ['required' => false]
        )->add('enableAccountDiscovery', TextType::class, ['required' => false])->add(
            'acceptsNewsletter',
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
                'data_class' => PrivacySettings::class,
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
