<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Article
 *
 * @ORM\Entity
 * @ORM\Table(name="articles")
 */
class Article implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"public"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"public"})
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"public"})
     */
    private $ctaLink;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public"})
     */
    private $ctaText;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"public"})
     */
    private $category;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @Groups({"user_profile_public"})
     */
    private $author;

    /**
     * Article constructor.
     *
     * @param string $title
     * @param string $text
     * @param string $ctaText
     * @param User   $author
     */
    public function __construct(?User $author = null, ?string $title = null, ?string $text = null, ?string $ctaText = 'Read More')
    {
        $this->title = $title;
        $this->text = $text;
        $this->ctaText = $ctaText;
        $this->author = $author;
    }

    /**
     * @return \Ramsey\Uuid\Uuid|string
     * @Groups({"public"})
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Article
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getCtaLink(): ?string
    {
        return $this->ctaLink;
    }

    /**
     * @param string $ctaLink
     *
     * @return Article
     */
    public function setCtaLink(?string $ctaLink): self
    {
        $this->ctaLink = $ctaLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getCtaText(): ?string
    {
        return $this->ctaText;
    }

    /**
     * @param string $ctaText
     *
     * @return Article
     */
    public function setCtaText(?string $ctaText): self
    {
        $this->ctaText = $ctaText;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return Article
     * @throws \Exception
     */
    public function setCategory(?string $category): self
    {
        if (!in_array($category, ['neutral', 'danger', 'warning', 'breaking'])) {
            throw new \Exception("Invalid category type.");
        }

        $this->category = $category;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Article
     */
    public function setAuthor(?User $author = null): self
    {
        $this->author = $author;

        return $this;
    }

}
