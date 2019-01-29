<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class PrivacySettings
 *
 * @ORM\Entity
 * @ORM\Table(name="privacy_settings")
 */
class PrivacySettings implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : true})
     * @Groups({"settings_privacy", "settings"})
     */
    private $showActivityStatus = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     * @Groups({"settings_privacy", "settings"})
     */
    private $privateAccount = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" : true})
     * @Groups({"settings_privacy", "settings"})
     */
    private $enableAccountDiscovery = true;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     * @Groups({"settings_privacy", "settings"})
     */
    private $acceptsNewsletter = false;

    /**
     * @return bool
     */
    public function isShowActivityStatus()
    {
        return $this->showActivityStatus ?? false;
    }

    /**
     * @param  bool $showActivityStatus
     *
     * @return $this
     */
    public function setShowActivityStatus(?bool $showActivityStatus = true): self
    {
        $this->showActivityStatus = $showActivityStatus;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivateAccount()
    {
        return $this->privateAccount ?? false;
    }

    /**
     * @param  bool $privateAccount
     *
     * @return $this
     */
    public function setPrivateAccount(?bool $privateAccount = false): self
    {
        $this->privateAccount = $privateAccount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableAccountDiscovery()
    {
        return $this->enableAccountDiscovery ?? false;
    }

    /**
     * @param  bool $enableAccountDiscovery
     *
     * @return $this
     */
    public function setEnableAccountDiscovery(?bool $enableAccountDiscovery = true): self
    {
        $this->enableAccountDiscovery = $enableAccountDiscovery;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAcceptsNewsletter()
    {
        return $this->acceptsNewsletter ?? false;
    }

    /**
     * @param  bool $acceptsNewsletter
     *
     * @return $this
     */
    public function setAcceptsNewsletter(?bool $acceptsNewsletter = false): self
    {
        $this->acceptsNewsletter = $acceptsNewsletter;

        return $this;
    }
}
