<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BlogController extends Controller {

    /**
     * @Route("/myblog", name="myblog")
     * @Security("has_role('ROLE_USER')")
     */
    public function showMyBlog() {

        $em = $this->get('doctrine')->getManager();
        $user = $this->getUser();

        $query = $em->createQuery('SELECT p FROM AppBundle:Post p WHERE p.blog = ?1');
        $query->setParameter(1, $user->getBlog());
        $posts = $query->getResult();

        $query = $em->createQuery('SELECT u FROM AppBundle:User u WHERE u.username != ?1');
        $query->setParameter(1, $user->getUsername());
        $otherBlogs = $query->getResult();

        return $this->render('showBlog.html.twig', [
                    'username' => $user->getUserName(),
                    'posts' => $posts,
                    'searchBlogs' => $otherBlogs,
                    'blog' => $user->getBlog()
        ]);
    }

    /**
     * @Route("/blog/{id}", name="showblog", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function showBlog($id) {

        $em = $this->get('doctrine')->getManager();
        $query = $em->createQuery('SELECT u FROM AppBundle:User u WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $users = $query->getResult();

        if (!empty($users)) {
            $user = $users[0];
            $query = $em->createQuery('SELECT p FROM AppBundle:Post p WHERE p.blog = ?1');
            $query->setParameter(1, $user->getBlog());
            $posts = $query->getResult();

            $query = $em->createQuery('SELECT u FROM AppBundle:User u WHERE u.username != ?1');
            $query->setParameter(1, $user->getUsername());
            $otherBlogs = $query->getResult();

            return $this->render('showBlog.html.twig', [
                        'username' => $user->getUserName(),
                        'posts' => $posts,
                        'searchBlogs' => $otherBlogs,
                        'blog' => $user->getBlog()
            ]);
        }
        return $this->redirectToRoute('myblog');
    }

}
