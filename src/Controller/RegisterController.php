<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid())) {
            $em = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $form['password']->getData());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('success',User::REGISTRO_EXITOSO);

            return $this->redirectToRoute('register');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
