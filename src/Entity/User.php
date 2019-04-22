<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * @var \Ramsey\Uuid\Uuid
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @Groups({"public", "requests"})
     */
    protected $id;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user_profile_public", "requests"})
     */
    protected $firstName;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user_profile_public", "requests"})
     */
    protected $lastName;

    /**
     * @var string
     * @Assert\Country()
     * @ORM\Column(name="country", type="text", nullable=true)
     * @Groups({"user_profile_public", "requests"})
     */
    protected $country;

    /**
     * @Groups({"list", "settings_account", "settings", "user_search", "user_profile_public"})
     */
    protected $email;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\Url(
     *    checkDNS = "ANY"
     * )
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user_profile_public"})
     */
    protected $avatarUrl;

    /**
     * @var PrivacySettings
     *
     * @ORM\ManyToOne(targetEntity="PrivacySettings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="privacy_id", referencedColumnName="id")
     * @Groups({"settings"})
     */
    private $privacy;

    /**
     * @var NotificationSettings
     *
     * @ORM\ManyToOne(targetEntity="NotificationSettings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="ntf_settings_id", referencedColumnName="id")
     * @Groups({"settings"})
     */
    private $notificationSettings;

    /**
     * @var AccountSettings
     *
     * @ORM\ManyToOne(targetEntity="AccountSettings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * @Groups({"settings"})
     */
    private $account;

    /**
     * @var ArrayCollection|UserDevice[]
     *
     * @ORM\OneToMany(targetEntity="UserDevice", mappedBy="user", cascade={"persist"})
     * @Groups({"user_devices"})
     */
    private $devices;

    // ...
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="author")
     */
    private $articles;

    // ...

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->devices = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    /**
     * @param string $avatarUrl
     *
     * @return $this
     */
    public function setAvatarUrl(string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * @return UserDevice[]|ArrayCollection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param UserDevice $device
     *
     * @return $this
     */
    public function addDevice(UserDevice $device): self
    {
        $this->devices->add($device);

        return $this;
    }

    /**
     * @param UserDevice $device
     *
     * @return $this
     */
    public function removeDevice(UserDevice $device): self
    {
        $this->devices->removeElement($device);

        return $this;
    }

    /**
     * @return PrivacySettings
     */
    public function getPrivacy(): ?PrivacySettings
    {
        return $this->privacy;
    }

    /**
     * @param PrivacySettings $privacy
     *
     * @return $this
     */
    public function setPrivacy(?PrivacySettings $privacy = null): self
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * @return NotificationSettings
     */
    public function getNotificationSettings(): ?NotificationSettings
    {
        return $this->notificationSettings;
    }

    /**
     * @param NotificationSettings $notificationSettings
     *
     * @return $this
     */
    public function setNotificationSettings(?NotificationSettings $notificationSettings = null): self
    {
        $this->notificationSettings = $notificationSettings;

        return $this;
    }

    /**
     * @return AccountSettings
     */
    public function getAccount(): ?AccountSettings
    {
        return $this->account;
    }

    /**
     * @param AccountSettings $account
     *
     * @return $this
     */
    public function setAccount(?AccountSettings $account = null): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param Article $article
     *
     * @return User
     */
    public function removeArticle(Article $article)
    {
        $this->articles->remove($article);
        $article->setAuthor(null);

        return $this;
    }

    /**
     * @param Article $article
     *
     * @return User
     */
    public function addArticle(Article $article)
    {
        $this->articles->add($article);
        $article->setAuthor($this);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}