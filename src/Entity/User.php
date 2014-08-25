<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="salt_UNIQUE", columns={"salt"}), @ORM\UniqueConstraint(name="hash_UNIQUE", columns={"hash"}), @ORM\UniqueConstraint(name="password_UNIQUE", columns={"password"}), @ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class User extends \Entity\AbstractEntity
{
    const ROLE_ADMIN = 'admin';

    const HASH_LEN = 32;
    const SALT_LEN = 16;

    const PASSWORD_HASH_ALGORITHM = 'md5';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    protected $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=16, nullable=false)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", columnDefinition="ENUM('admin') NOT NULL")
     */
    protected $role;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    protected $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    protected $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_login", type="datetime", nullable=true)
     */
    protected $dateLastLogin;

    /**
     * @ORM\PrePersist
     */
    public function preInsert()
    {
        if (!$this->password) {
            $this->setPassword(\Extlib\Generator::generate());
        }

        $this->hash = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'hash', self::HASH_LEN);
        $this->dateCreate = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->salt = \Extlib\Generator::generateDoctrine2($this->getEm(), get_class($this), 'salt', self::SALT_LEN);
        $this->password = hash(self::PASSWORD_HASH_ALGORITHM, $password . $this->salt);

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set create date
     *
     * @param \DateTime $dateCreate
     * @return User
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get date create
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }


    /**
     * Set date last login
     *
     * @param \DateTime $dateLastLogin
     * @return User
     */
    public function setDateLastLogin(\DateTime $dateLastLogin)
    {
        $this->dateLastLogin = $dateLastLogin;

        return $this;
    }

    /**
     * Get date last login
     *
     * @return \DateTime
     */
    public function getDateLastLogin()
    {
        return $this->dateLastLogin;
    }
}
