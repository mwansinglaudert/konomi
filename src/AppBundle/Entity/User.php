<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User entity class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable {

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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function getUsername()
    {
        return $this->user;
    }

    public function setUsername($username)
    {
        $this->user = $username;
    }

    public function getPassword()
    {
        return $this->pass;
    }

    public function setPassword($password)
    {
        $this->pass = $password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
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

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->user,
            $this->pass,
            $this->role,
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
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}