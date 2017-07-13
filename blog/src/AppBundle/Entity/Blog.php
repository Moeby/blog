<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
     * @ORM\Column(name="blog_name", type="string", length=45, nullable=false)
     */
    private $blogName;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_description", type="string", length=45, nullable=false)
     */
    private $blogDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}

