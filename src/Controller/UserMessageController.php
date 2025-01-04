<?php

namespace App\Controller;

use App\Entity\UserMessage;
use App\Form\UserMessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/user/message', name: 'app_user_message_')]
class UserMessageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $usermessage = new UserMessage;
        $form = $this->createForm(UserMessageFormType::class, $usermessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($usermessage);
            $em->flush();
            $this->addFlash('success', 'Le message est envoyé avec succès !');
            return $this->redirectToRoute('app_user_message_index');
        }
        return $this->render('user_message/index.html.twig', [
            'form' => $form
        ]);
    }
}
