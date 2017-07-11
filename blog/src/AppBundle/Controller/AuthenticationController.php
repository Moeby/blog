<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthenticationController extends Controller {

    /**
     * @Route("/login_check", name="login_check")
     */
    public function checkLoginAction() {
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authUtils) {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $user = new User();
        $form = $this->createFormBuilder($user)
                ->setAction($this->generateUrl('login_check'))
                ->add('username', TextType::class, array('property_path' => '_username'))
                ->add('password', PasswordType::class, array('property_path' => '_password'))
                ->add('save', SubmitType::class, array('label' => 'Login'))
                ->getForm()
        ;

        return $this->render('login.html.twig', [
                    'title' => "Login",
                    'form' => $form->createView(),
                    'error' => $error,
                    'last_username' => $lastUsername
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}
