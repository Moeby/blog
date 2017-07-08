<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Entity\Post;
use AppBundle\Entity\Blog;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller {
    /**
     * @Route("/newpost", name="newpost")
     */    
    public function addPostAction(Request $request){
        $post = new Post();
        $form = $this->createFormBuilder($post)
                ->add('title', TextType::class)
                ->add('text', TextareaType::class)
                ->add('add', SubmitType::class, array('label' => 'AddPost'))                
                ->getForm()
        ;
        $form->handleRequest($request);
        $errors = $form->getErrors(true);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $post = $form->getData();
            //TODO GetBlog ändern
            $blog = new Blog();
            $post->setBlog($blog);
            $em->persist($post);
            $em->flush();

            exit;

            return $this->redirectToRoute('task_success');
        }

        return $this->render('addPost.html.twig', [
                    'title' => "Add Post",
                    'form' => $form->createView(),
                    'errors' => $errors,
        ]);
    }
    
    /**
     * @Route("/editpost", name="editpost")
     */        
    public function editPostAction(Request $request /*, POSTID*/){
        //TODO: how do I get the right Post
        $post = new Post();
        $form = $this->createFormBuilder($post)
                ->add('title', TextType::class, array('data' => $post->getTitle()))
                ->add('text', TextareaType::class, array('data' => $post->getText()))
                ->add('save', SubmitType::class, array('label' => 'EditPost'))                
                ->getForm()
        ;
        $form->handleRequest($request);
        $errors = $form->getErrors(true);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $post = $form->getData();

            //TODO: check login
            var_dump($post);
            exit;

            return $this->redirectToRoute('task_success');
        }

        return $this->render('editPost.html.twig', [
                    'title' => "Edit Post",
                    'form' => $form->createView(),
                    'errors' => $errors,
        ]);
    }
 
    /**
     * @Route("/showmyblog", name="delete_post")
     */        
    public function deletePostAction(Post $dPost){
        $em->remove($dPost);
    }

}
