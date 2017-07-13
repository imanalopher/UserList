<?php


namespace AppBundle\Entity;

use DateTime;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    const MALE = 'male';
    const FEMALE = 'female';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string")
     */
    protected $lastName;

    /**
     * @ORM\Column(name="gender", type="string")
     */
    protected $gender;

    /**
     * @var datetime
     *
     * @ORM\Column(name="birthday", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $birthday;

    public function __construct()
    {
        parent::__construct();
    }

    public function setUsername($username)
    {
        $this->setFirstName('');
        $this->setLastName('');
        return parent::setUsername($username);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */

    public function prePersist()
    {
        $this->birthday = new \DateTime;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        if (!in_array($gender, array(self::MALE, self::FEMALE))) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->gender = $gender;
    }
}