<?php

namespace App\Controller\Admin;

use App\Entity\SubCategory;
use App\Form\SubCategoryFormType;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/subcategory', name: 'app_admin_subcategory_')]
class SubCategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SubCategoryRepository $subCategoryRepository): Response
    {
        $subcategories = $subCategoryRepository->findAll();
        return $this->render('admin/subcategory/index.html.twig', compact('subcategories'));
    }

    #[Route('/add', name: 'addsubcategory')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $SubCategory = new SubCategory();
        $form = $this->createForm(SubCategoryFormType::class, $SubCategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($SubCategory);
            $em->flush();

            $this->addFlash('success', 'La Sous Catégorie est bien Ajoutée');
            return $this->redirectToRoute('app_admin_subcategory_addsubcategory');
        }
        return $this->render('admin/subcategory/addsubcategory.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatesubcategory')]
    public function update(SubCategory $subCategory, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(SubCategoryFormType::class, $subCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($subCategory);
            $em->flush();
            $this->addFlash('success', 'La Sous Catégorie est bien Modifiée');
            return $this->redirectToRoute('app_admin_subcategory_index');
        }
       
        return $this->render('admin/subcategory/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletesubcategory')]
    public function delete(SubCategory $subCategory, EntityManagerInterface $em): Response
    {
            $em->remove($subCategory);
            $em->flush();
            $this->addFlash('danger', 'La Sous Catégorie est bien Supprimée');
            return $this->redirectToRoute('app_admin_subcategory_index');
    }
}
