<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class UserManagementController extends Controller {

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request) {
        $user = new User();
        $form = $this->createFormBuilder($user)
                ->add('username', TextType::class)
                ->add('password', PasswordType::class)
                ->add('save', SubmitType::class, array('label' => 'Login'))
                ->getForm()
        ;
        $form->handleRequest($request);
        $errors = $form->getErrors(true);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            //TODO: check login
            var_dump($user);
            exit;

            return $this->redirectToRoute('task_success');
        }

        return $this->render('login.html.twig', [
                    'title' => "Login",
                    'form' => $form->createView(),
                    'errors' => $errors,
        ]);
    }

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
                ->add('description', TextareaType::class)
                ->add('save', SubmitType::class, array('label' => 'Login'))
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
            
            if ( empty($results) ){
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setBlog($this->createBlogAction());
                $em->persist($user);
                $em->flush();

                return $this->render('login.html.twig', [
                            'title' => "Sign Up",
                            'form' => $form->createView(),
                            'errors' => null
                ]);
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
        $em->persist($blog);
        $em->flush();
        
        return $blog;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}
