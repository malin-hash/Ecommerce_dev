<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Form\CityFormType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/city', name: 'app_admin_city_')]
class CityController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CityRepository $city): Response
    {
        $cities = $city->findAll();
        return $this->render('admin/city/index.html.twig', compact('cities'));
    }

    #[Route('/add', name: 'addcity')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $city = new City();
        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($city);
            $em->flush();

            $this->addFlash('success', 'La Ville est bien Ajoutée');
            return $this->redirectToRoute('app_admin_city_addcity');
        }
        return $this->render('admin/city/addcity.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatecity')]
    public function update(City $city, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', 'La Ville est bien Modifiée');
            return $this->redirectToRoute('app_admin_city_index');
        }
       
        return $this->render('admin/city/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletecity')]
    public function delete(City $city, EntityManagerInterface $em): Response
    {
            $em->remove($city);
            $em->flush();
            $this->addFlash('danger', 'La Ville est bien Supprimée');
            return $this->redirectToRoute('app_admin_city_index');
    }
}
