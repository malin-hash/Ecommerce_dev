<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/client', name: 'app_admin_client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository, OrdersRepository $ordersRepository): Response
    {
        $users = $userRepository->findBy([], ['id' => 'desc']);


        return $this->render('admin/client/index.html.twig', compact('users'));
    }

    #[Route('/client/actif', name: 'useractif')]
    public function useractif(UserRepository $userRepository, OrdersRepository $ordersRepository): Response
    {
        $users = $ordersRepository->getProductByOrder();
        return $this->render('admin/client/clientactif.html.twig', compact('users'));
    }

    #[Route('/delete/{id}', name: 'deleteclient')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
            $em->remove($user);
            $em->flush();
            $this->addFlash('danger', 'Le Client est bien supprimé(e)');
            return $this->redirectToRoute('app_admin_client_index');
    }
}
