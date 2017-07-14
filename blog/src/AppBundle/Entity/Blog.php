<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity
 */
class Blog
{
    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="blog_name", type="string", length=45, nullable=false)
     */
    private $blogName;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="blog_description", type="text",  nullable=false)
     */
    private $blogDescription;

    /**
     * @var integer
     *
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    function getBlogName() {
        return $this->blogName;
    }

    function getBlogDescription() {
        return $this->blogDescription;
    }

    function getId() {
        return $this->id;
    }

    function setBlogName($blogName) {
        $this->blogName = $blogName;
    }

    function setBlogDescription($blogDescription) {
        $this->blogDescription = $blogDescription;
    }

    function setId($id) {
        $this->id = $id;
    }

}

