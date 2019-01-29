<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait EntityTrait
 *
 * @package App\Entity\Traits
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
trait EntityTrait
{
    /**
     * @var \Ramsey\Uuid\Uuid
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string
     *
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
}
