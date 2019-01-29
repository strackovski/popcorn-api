<?php

namespace App\Service\Query;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Psr\Log\LoggerInterface;

/**
 * Class PropertyAnnotationMatcher
 *
 * @package Service\Query
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class PropertyAnnotationMatcher
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PropertyAnnotationMatcher constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Tries to resolve an entity association target class from a s
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param \ReflectionProperty $property
     */
    public function matchAnnotation(\ReflectionProperty $property): void
    {
        try {
            $reader = new AnnotationReader();
//            $myAnnotation = $reader->getPropertyAnnotation($property, Annotation::class);
        } catch (AnnotationException $e) {
            $this->logger->error($e->getMessage());
        }

//        if ($myAnnotation instanceof OneToOne) {
//            // @todo
//        }
    }
}
