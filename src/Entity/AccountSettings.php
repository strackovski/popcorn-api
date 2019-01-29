<?php

namespace App\Entity;

use App\Entity\Traits\EntityTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AccountSettings
 *
 * @ORM\Entity
 * @ORM\Table(name="user_accounts")
 *
 * @package App\Entity
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class AccountSettings implements EntityInterface
{
    use EntityTrait, TimestampableTrait;

    /**
     * User's given name.
     *
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"settings_account", "settings"})
     */
    private $firstname;

    /**
     * User's family name.
     *
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"settings_account", "settings"})
     */
    private $lastname;

    /**
     * User's date of birth.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"settings_account", "settings"})
     */
    private $dateOfBirth;

    /**
     * User's FIAT currency.
     *
     * @var string
     *
     * @Assert\Currency
     * @ORM\Column(type="string", nullable=true, options={"default" : "EUR"})
     * @Groups({"settings_account", "settings"})
     */
    private $fiatCurrency;

    /**
     * User's preferred crypto currency.
     *
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Choice(choices={"any", "fiat", "crpt"}, message="Invalid preferredCurrencyType value.")
     * @ORM\Column(type="string", length=5, nullable=true, options={"default" : "none"})
     * @Groups({"settings_account", "settings"})
     */
    private $preferredCurrencyType;

    /**
     * Country
     *
     * @var string
     *
     * @Assert\Country
     * @ORM\Column(name="country", type="string", nullable=true, length=255)
     * @Groups({"settings_account", "settings"})
     */
    private $country;

    /**
     * Language
     *
     * @var string
     *
     * @Assert\Language
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"settings_account", "settings"})
     */
    private $language;

    /**
     * Timezone
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"settings_account", "settings"})
     */
    private $timezone;

    /**
     * @return string
     * @Groups({"personal"})
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param  string $firstname
     *
     * @return AccountSettings
     */
    public function setFirstname(?string $firstname = null): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param  \DateTime $dateOfBirth
     *
     * @return AccountSettings
     */
    public function setDateOfBirth(\DateTime $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return string
     * @Groups({"personal"})
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param  string $lastname
     *
     * @return AccountSettings
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFiatCurrency()
    {
        return $this->fiatCurrency;
    }

    /**
     * @param  string $fiatCurrency
     *
     * @return $this
     */
    public function setFiatCurrency(?string $fiatCurrency = null): self
    {
        $this->fiatCurrency = $fiatCurrency;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreferredCurrencyType()
    {
        return $this->preferredCurrencyType;
    }

    /**
     * @param  string $preferredCurrencyType
     *
     * @return $this
     */
    public function setPreferredCurrencyType(?string $preferredCurrencyType = null): self
    {
        $this->preferredCurrencyType = $preferredCurrencyType;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param  string $language
     *
     * @return $this
     */
    public function setLanguage(?string $language = null): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param  string $timezone
     *
     * @return $this
     */
    public function setTimezone(?string $timezone = null): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param  string $country
     *
     * @return $this
     */
    public function setCountry(?string $country = null): self
    {
        $this->country = $country;

        return $this;
    }
}
