<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User entity class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @ORM\Column(type="string", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $pass;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean", length=4, name="is_active")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     * @ORM\JoinColumn(nullable=false)
     */
    public $createstamp;

    /**
     * @ORM\Column(type="datetime")
     * @ORM\JoinColumn(nullable=false)
     */
    public $timestamp;





    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }





    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->user;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->user = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->pass;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->pass = $password;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getCreatestamp()
    {
        return $this->createstamp;
    }

    /**
     * @param mixed $createstamp
     */
    public function setCreatestamp($createstamp)
    {
        $this->createstamp = $createstamp;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }





    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array( $this->role );
//        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->user,
            $this->pass,
            $this->role,
            $this->isActive,
            $this->createstamp,
            $this->timestamp,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->user,
            $this->pass,
            $this->role,
            $this->isActive,
            $this->createstamp,
            $this->timestamp,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}