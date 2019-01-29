<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class UserDevice
 *
 * @ORM\Entity
 * @ORM\Table(name="user_devices")
 *
 * @package App\Entity
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class UserDevice implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * Manufacturer-issued device identifier.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"notifications", "device"})
     */
    private $deviceId;

    /**
     * Manufacturer-issued device token.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"notifications", "device"})
     */
    private $deviceToken;

    /**
     * Time when device was last seen running the app.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"notifications", "device"})
     */
    private $lastSeen;

    /**
     * Device capabilities.
     *
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     * @Groups({"notifications", "device"})
     */
    private $capabilities;

    /**
     * Extra data from device.
     *
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     * @Groups({"notifications", "device"})
     */
    private $extra;

    /**
     * The user owns the device.
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="devices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * UserDevice constructor.
     *
     * @param string $deviceId
     * @param string $deviceToken
     * @param User   $user
     */
    public function __construct(?string $deviceId = null, ?string $deviceToken = null, ?User $user = null)
    {
        $this->deviceId = $deviceId;
        $this->deviceToken = $deviceToken;
        $this->user = $user;
    }

    /**
     * @return string
     * @Groups({"public"})
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * @param  string $deviceId
     *
     * @return $this
     */
    public function setDeviceId(?string $deviceId = null): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceToken(): ?string
    {
        return $this->deviceToken;
    }

    /**
     * @param  string $deviceToken
     *
     * @return $this
     */
    public function setDeviceToken(?string $deviceToken = null): self
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param  User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function getCapabilities(): ?array
    {
        return $this->capabilities;
    }

    /**
     * @param  array $capabilities
     *
     * @return $this
     */
    public function setCapabilities(?array $capabilities = []): self
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastSeen(): ?\DateTime
    {
        return $this->lastSeen;
    }

    /**
     * @param \DateTime $lastSeen
     *
     * @return $this
     */
    public function setLastSeen(?\DateTime $lastSeen = null): self
    {
        $this->lastSeen = $lastSeen;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getOperatingSystem()
    {
        if ($this->getExtra() !== null && !empty($this->getExtra()) && array_key_exists('type', $this->getExtra())) {
            return $this->getExtra()['type'];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @param  array $extra
     *
     * @return $this
     */
    public function setExtra(?array $extra = []): self
    {
        $this->extra = $extra;

        return $this;
    }
}
