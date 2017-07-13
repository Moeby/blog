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
                    'title' => $user->getBlog()->getBlogName(),
                    'posts' => $posts,
                    'description' => $user->getBlog()->getDescription(),
                    'searchBlogs' => $otherBlogs
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
                        'title' => $user->getUserName(),
                        'posts' => $posts,
                        'description' => $user->getDescription(),
                        'searchBlogs' => $otherBlogs
            ]);
        }
        return $this->redirectToRoute('myblog');
    }
    
     /**
     * @Route("/editblog/{id}", name="editblog", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editBlogAction($id, Request $request) {
        $em = $this->get('doctrine')->getManager();

        // get blog for userid
        $query = $em->createQuery('SELECT b FROM AppBundle:Blog b WHERE b.id = ?1');
        $query->setParameter(1, $id);
        $results = $query->getResult();
        
        if (!empty($results)) {
            $form = $this->createFormBuilder($results[0])
                    ->add('blogname', TextType::class)
                    ->add('description', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'tinymce')
                    ))
                    ->add('save', SubmitType::class, array('label' => 'Submit'))
                    ->getForm()
            ;

            $form->handleRequest($request);
            $errors = $form->getErrors(true);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $blog = $form->getData();
                $em->persist($blog);
                $em->flush();
                
                return $this->redirectToRoute('myblog');
            }

            return $this->render('editBlog.html.twig', [
                        'title' => "Edit Blog",
                        'form' => $form->createView(),
                        'errors' => null,
            ]);
        }
    }

}
