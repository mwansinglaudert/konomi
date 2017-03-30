<?php
namespace KonomiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Template entity class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 *
 * @ORM\Entity(repositoryClass="KonomiBundle\Repository\TemplateRepository")
 */
class Template {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    public $code;

    /**
     * @ORM\Column(type="string", length=512)
     */
    public $description;

    /**
     * @ORM\Column(type="string", length=11)
     */
    public $sum;

    /**
     * @ORM\Column(type="integer", length=1)
     * @ORM\JoinColumn(nullable=false)
     */
    public $type;

    /**
     * @ORM\Column(type="string", length=128)
     */
    public $user;

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

    /**
     * @ORM\Column(type="string", length=512)
     */
    public $link;

    /**
     * @ORM\Column(type="boolean", length=1)
     * @ORM\JoinColumn(nullable=false)
     */
    public $deleted;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param mixed $sum
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
}