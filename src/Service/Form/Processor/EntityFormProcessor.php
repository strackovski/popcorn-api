<?php

namespace App\Service\Form\Processor;

use App\Entity\EntityInterface;
use App\Service\Mutator\MutatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EntityFormProcessor
 *
 * @package      App\Service\Form\Processor
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class EntityFormProcessor
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var MutatorInterface
     */
    private $mutator;

    /**
     * EntityFormProcessor constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param MutatorInterface     $mutator
     */
    public function __construct(FormFactoryInterface $formFactory, MutatorInterface $mutator)
    {
        $this->formFactory = $formFactory;
        $this->mutator = $mutator;
    }

    /**
     *
     *
     * @param EntityInterface        $entity
     * @param Request                $request
     * @param null|FormTypeInterface $type
     * @param array                  $options
     * @param bool                   $returnForm
     *
     * @return EntityInterface|FormInterface
     * @throws \Exception
     */
    public function process(
        EntityInterface $entity,
        Request $request,
        ?FormTypeInterface $type = null,
        array $options = [],
        $returnForm = false
    ) {
        $form = $this->createForm($type ?: $this->getFormType($entity), $entity, $options);
        $data = json_decode($request->getContent(), true);
        $form = $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $this->mutator->save($form->getData());

            return $returnForm ? $form : $object;
        }

        return $form;
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @final
     *
     * @param string $type
     * @param null   $data
     * @param array  $options
     *
     * @return FormInterface
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     * @throws \Exception
     */
    protected function getFormType(EntityInterface $entity): string
    {
        $reflection = new \ReflectionObject($entity);
        $typeClass = sprintf("App\\Form\\%sType", $reflection->getShortName());

        if (!class_exists($typeClass)) {
            throw new \RuntimeException(sprintf('Could not load type "%s": class does not exist.', $typeClass));
        }

        return $typeClass;
    }
}
