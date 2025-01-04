<?php

namespace App\Controller\Admin;

use App\Entity\Size;
use App\Form\SizeFormType;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/size', name: 'app_admin_size_')]
class SizeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SizeRepository $sizeRepository): Response
    {
        
        $sizes = $sizeRepository->findAll();
        return $this->render('admin/size/index.html.twig', compact('sizes'));
    }

    #[Route('/add', name: 'addsize')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $size = new Size;
        $form = $this->createForm(SizeFormType::class, $size);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($size);
            $em->flush();
            $this->addFlash('success', 'La Taille/Pointure est bien Ajoutée');
            return $this->redirectToRoute('app_admin_size_addsize');
        }
        return $this->render('admin/size/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatesize')]
    public function update(Size $size, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(SizeFormType::class, $size);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($size);
            $em->flush();
            $this->addFlash('success', 'La Taille/Pointure est bien Modifiée');
            return $this->redirectToRoute('app_admin_size_index');
        }
       
        return $this->render('admin/size/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletesize')]
    public function delete(Size $size, Request $request, EntityManagerInterface $em): Response
    {
            $em->remove($size);
            $em->flush();
            $this->addFlash('danger', 'La Taille/Pointure est bien Supprimée');
            return $this->redirectToRoute('app_admin_size_index');
    }
}
