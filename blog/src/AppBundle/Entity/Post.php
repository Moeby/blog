<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="fk_post_blog1_idx", columns={"blog_id"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="date", nullable=false)
     */
    private $publishedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Blog
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Blog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     * })
     */
    private $blog;

    function getTitle() {
        return $this->title;
    }

    function getText() {
        return $this->text;
    }

    function getPublishedDate(): \DateTime {
        return $this->publishedDate;
    }

    function getId() {
        return $this->id;
    }

    function getBlog(): \AppBundle\Entity\Blog {
        return $this->blog;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setPublishedDate(\DateTime $publishedDate) {
        $this->publishedDate = $publishedDate;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setBlog(\AppBundle\Entity\Blog $blog) {
        $this->blog = $blog;
    }


}

