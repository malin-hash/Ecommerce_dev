<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $client = $userRepository->findBy(['roles' => 'ROLE_USER']);
        $clients = 0;
        foreach ($client as $item){
            $clients = array_sum([$item]);
        }
        return $this->render('admin/admin/index.html.twig', compact('clients'));
    }
}
