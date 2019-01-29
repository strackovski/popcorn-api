<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NotificationSettings
 *
 * User's notification settings.
 *
 * @ORM\Entity
 * @ORM\Table(name="notification_settings")
 *
 * @package App\Entity
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class NotificationSettings implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * If this is false, all notifications are disabled and no other flags
     * are checked. Other notification settings are considered only if
     * this flag is set to true.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : true})
     * @Groups({"settings_notifications", "settings"})
     */
    private $enableNotifications = true;

    /**
     * Defines if notifications are sent to user via email and if they are, in what
     * format - HTML or plain text. null value means email notifications are disabled.
     *
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Choice(choices={"html", "text"}, message="Invalid emailNotifications value.")
     *
     * @ORM\Column(type="string", length=5, nullable=true, options={"default" : null})
     * @Groups({"settings_notifications", "settings"})
     */
    private $emailNotifications;

    /**
     * Defines if notifications are sent to user's mobile devices.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     * @Groups({"settings_notifications", "settings"})
     */
    private $deviceNotifications = false;

    /**
     * Defines if notifications are sent to user's web browser.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     * @Groups({"settings_notifications", "settings"})
     */
    private $browserNotifications = false;

    /**
     * Notifications are processed and dispatched on the fly, meaning users
     * receive notifications as frequently as they are generated. The purpose of this
     * property is to allow users to adjust how often they receive notifications.
     *
     * @var int
     *
     * @Assert\Choice({0, 1, 2, 3, 4, 5})
     *
     * @ORM\Column(type="integer", nullable=false, options={"default" : 3}))
     * @Groups({"settings_notifications", "settings"})
     */
    private $notificationFrequency = 3;

    /**
     * @return bool
     */
    public function isBrowserNotifications()
    {
        return $this->browserNotifications ?? false;
    }

    /**
     * @param  bool $browserNotifications
     *
     * @return $this
     */
    public function setBrowserNotifications(?bool $browserNotifications = false): self
    {
        $this->browserNotifications = $browserNotifications;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableNotifications()
    {
        return $this->enableNotifications ?? false;
    }

    /**
     * @param  bool $enableNotifications
     *
     * @return $this
     */
    public function setEnableNotifications(?bool $enableNotifications = true): self
    {
        $this->enableNotifications = $enableNotifications;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailNotifications()
    {
        return $this->emailNotifications ?? "none";
    }

    /**
     * @param  string $emailNotifications
     *
     * @return $this
     */
    public function setEmailNotifications(?string $emailNotifications = "none"): self
    {
        $this->emailNotifications = $emailNotifications;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeviceNotifications()
    {
        return $this->deviceNotifications ?? false;
    }

    /**
     * @param  bool $deviceNotifications
     *
     * @return $this
     */
    public function setDeviceNotifications(?bool $deviceNotifications = true): self
    {
        $this->deviceNotifications = $deviceNotifications;

        return $this;
    }

    /**
     * @return int
     */
    public function getNotificationFrequency()
    {
        return $this->notificationFrequency;
    }

    /**
     * @param  int $notificationFrequency
     *
     * @return $this
     */
    public function setNotificationFrequency(?int $notificationFrequency = 3): self
    {
        $this->notificationFrequency = $notificationFrequency;

        return $this;
    }
}
