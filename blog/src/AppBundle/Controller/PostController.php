<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PostController extends Controller {

    /**
     * @Route("/newpost", name="newpost")
     * @Security("has_role('ROLE_USER')")
     */
    public function addPostAction(Request $request) {
        $post = new Post();
        $form = $this->createFormBuilder($post)
                ->add('title', TextType::class)
                ->add('text', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'tinymce')
                        )
                )
                ->add('add', SubmitType::class, array('label' => 'Submit'))
                ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $post = $form->getData();
            $post->setBlog($this->getUser()->getBlog());
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('myblog');
        }

        return $this->render('editPost.html.twig', [
                    'title' => "Add Post",
                    'form' => $form->createView(),
                    'username' => $this->getUser()->getUsername(),
        ]);
    }

    /**
     * @Route("/editpost/{id}", name="editpost", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editPostAction($id, Request $request) {
        $em = $this->get('doctrine')->getManager();

        // get post for id
        $query = $em->createQuery('SELECT p FROM AppBundle:Post p WHERE p.id = ?1');
        $query->setParameter(1, $id);
        $results = $query->getResult();

        if (!empty($results)) {
            $form = $this->createFormBuilder($results[0])
                    ->add('title', TextType::class)
                    ->add('text', TextareaType::class, array(
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
                $post = $form->getData();
                $em->persist($post);
                $em->flush();

                return $this->redirectToRoute('myblog');
            }

            return $this->render('editPost.html.twig', [
                        'title' => "Edit Post",
                        'form' => $form->createView(),
                        'username' => $this->getUser()->getUsername(),
            ]);
        }
    }

    /**
     * @Route("/deletepost/{id}", name="deletepost", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function deletePostAction($id) {
        $em = $this->get('doctrine')->getManager();

        // get post for id
        $query = $em->createQuery('SELECT p FROM AppBundle:Post p WHERE p.id = ?1');
        $query->setParameter(1, $id);
        $results = $query->getResult();

        if (!empty($results)) {
            $em->remove($results[0]);
            $em->flush();
        }

        return $this->redirectToRoute('myblog');
    }

}
