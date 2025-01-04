<?php

namespace App\Controller\Admin;

use App\Entity\Color;
use App\Form\ColorFormType;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/color', name: 'app_admin_color_')]
class ColorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ColorRepository $colorRepository): Response
    {

        $colors = $colorRepository->findAll();
        return $this->render('admin/color/index.html.twig', compact('colors'));
    }

    #[Route('/add', name: 'addcolor')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $color = new Color;
        $form = $this->createForm(ColorFormType::class, $color);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($color);
            $em->flush();
            $this->addFlash('success', 'La Couleur est bien ajoutée');
            return $this->redirectToRoute('app_admin_color_addcolor');
        }

        return $this->render('admin/color/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatecolor')]
    public function update(Color $color, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(ColorFormType::class, $color);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($color);
            $em->flush();

            $this->addFlash('success', 'La Couleur est bien modifiée');
            return $this->redirectToRoute('app_admin_color_index');
        }
       
        return $this->render('admin/color/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletecolor')]
    public function delete(Color $color, EntityManagerInterface $em): Response
    {
            $em->remove($color);
            $em->flush();
            $this->addFlash('danger', 'La Couleur est bien supprimée');
            return $this->redirectToRoute('app_admin_color_index');
    }
}
