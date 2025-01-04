<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/category', name: 'app_admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', compact('categories'));
    }

    #[Route('/add', name: 'addcategory')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $color = new Category;
        $form = $this->createForm(CategoryFormType::class, $color);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($color);
            $em->flush();
            $this->addFlash('success', 'La Catégorie est bien ajoutée');
            return $this->redirectToRoute('app_admin_category_addcategory');
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatecategory')]
    public function update(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La Catégorie est bien Modifiée');
            return $this->redirectToRoute('app_admin_category_index');
        }
       
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletecategory')]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
            $em->remove($category);
            $em->flush();
            $this->addFlash('danger', 'La Catégorie est bien supprimée');
            return $this->redirectToRoute('app_admin_category_index');
    }
}
