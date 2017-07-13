<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Entity\User;
use AppBundle\Entity\Blog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SignupController extends Controller {

    /**
     * @Route("/signup", name="signup")
     */
    public function signupAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $user = new User();
        $error = "";
        $form = $this->createFormBuilder($user)
                ->setMethod('POST')
                ->add('username', TextType::class)
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat password')))
                ->add('save', SubmitType::class, array('label' => 'Signup'))
                ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $user = $form->getData();
            // check if username is already taken
            $query = $em->createQuery('SELECT u FROM AppBundle:User u WHERE u.username = ?1');
            $query->setParameter(1, $user->getUsername());
            $results = $query->getResult();

            if (empty($results)) {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setBlog($this->createBlogAction());
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('login');
            }
            $error = "Username already taken";
        }
        return $this->render('signup.html.twig', [
                    'title' => "Sign Up",
                    'form' => $form->createView(),
                    'error' => $error
        ]);
    }

    /**
     * @Route("/add", name="addBlog")
     */
    public function createBlogAction() {
        $em = $this->get('doctrine')->getManager();
        $blog = new Blog();
        $blog->setBlogDescription("Default description");
        $blog->setBlogName("Default blog name");
        $em->persist($blog);
        $em->flush();

        return $blog;
    }

}
