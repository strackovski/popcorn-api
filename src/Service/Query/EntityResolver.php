<?php

namespace App\Service\Query;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Annotation;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestMatcher
 *
 * @package Service\Query
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class EntityResolver
{
    public const ENTITY_NAMESPACE = "App\\Entity\\";

    public const ANNOTATIONS
        = [
            OneToOne::class,
            OneToMany::class,
            ManyToOne::class,
            ManyToMany::class,
        ];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $restful;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EntityResolver constructor.
     *
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param bool            $restful
     */
    public function __construct(Request $request, LoggerInterface $logger, bool $restful = false)
    {
        $this->request = $request;
        $this->logger = $logger;
        $this->restful = $restful;
    }

    /**
     * Tries to resolve entity class from request to map its associations with property names.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param Request $request
     */
    public function getClassName(Request $request): void
    {
    }

    /**
     * @param Request $request
     *
     * @return \ReflectionClass|null
     */
    protected function processRequest(Request $request): ?\ReflectionClass
    {
        $out = null;
        $route = strtolower($request->attributes->get("_route"));
        $candidate = strtolower(
            substr(
                $ctrl = $request->attributes->get("_controller"),
                strrpos($ctrl, "\\") + 1,
                strpos($ctrl, "Controller")
            )
        );

        if (($entity = $this->entityExists($candidate)) !== null) {
            $out = $entity;
        }

        if (\in_array($candidate, $ex = explode("_", $route), true) && \in_array(
                $candidate."s",
                explode("_", $route),
                true
            )) {
            $out = $this->entityExists($candidate);
        }

        return $out;
    }

    /**
     * @param string $className
     *
     * @return null|\ReflectionClass
     */
    protected function entityExists(string $className): ?\ReflectionClass
    {
        try {
            return new \ReflectionClass(self::ENTITY_NAMESPACE.$className);
        } catch (\ReflectionException $e) {
            $this->logger->error($e->getMessage());

            return null;
        }
    }

    /**
     * @param \ReflectionClass $reflection
     *
     * @return array|null
     * @throws AnnotationException
     */
    protected function filterAnnotations(\ReflectionClass $reflection): ?array
    {
        $filtered = [];
        foreach ($reflection->getProperties() as $property) {
            $reader = new AnnotationReader();
            $annotation = $reader->getPropertyAnnotation($property, Annotation::class);

            $class = $annotation === null ?: \get_class($annotation);

            if (!\in_array($class, self::ANNOTATIONS, true)) {
                continue;
            }

            $entity = $annotation === null ?: $annotation->targetEntity;
            $fetch = $annotation === null ?: $annotation->fetch;

            $filtered[$property->getName()] = [
                'entity' => $entity,
                'fetch' => $fetch,
                'type' => get_class_methods($annotation),
            ];
        }

        return $filtered ?? null;
    }
}
