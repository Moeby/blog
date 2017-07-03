<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use AppBundle\Entity\User;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->getForm()
            ;

        
        return $this->render('login.html.twig', [
            'title' => "Login",
            'form' => $form->createView(),
        ]);
    }
}
