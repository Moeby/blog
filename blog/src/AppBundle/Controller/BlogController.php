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

class BlogController extends Controller {
    
    /**
     * @Route("/myblog", name="myblog")
     */        
    public function printMyBlog(Request $request){
        $blog = new Blog();
        
        $em = $this->get('doctrine')->getManager();
        $user = $form->getData();

        $query = $em->createQuery('SELECT p FROM AppBundle:Post p WHERE p.blog = ?1');
        $query->setParameter(1, $blog);
        $results = $query->getResult();

        return $this->render('editPost.html.twig', [
                    'title' => "Edit Post",
                    'result' => $results
        ]);
    }
 
    /**
     * @Route("/showmyblog", name="delete_post")
     */        
    public function deletePostAction(Post $dPost){
        $em->remove($dPost);
    }

}
