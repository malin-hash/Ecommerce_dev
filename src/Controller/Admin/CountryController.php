<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Form\CountryFormType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/country', name: 'app_admin_country_')]
class CountryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CountryRepository $countryRepository): Response
    {

        $countries = $countryRepository->findAll();
        return $this->render('admin/country/index.html.twig', compact('countries'));
    }

    #[Route('/add', name: 'addcountry')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryFormType::class, $country);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($country);
            $em->flush();
            $this->addFlash('success', 'Le Pays est bien Ajouté');
           return $this->redirectToRoute('app_admin_country_addcountry');
        }

        return $this->render('admin/country/addcountry.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'updatecountry')]
    public function update(Country $country, Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($country);
            $em->flush();
            $this->addFlash('success', 'Le Pays est bien Modifié');
            return $this->redirectToRoute('app_admin_country_index');
        }
       
        return $this->render('admin/country/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deletecountry')]
    public function delete(Country $country, EntityManagerInterface $em): Response
    {
            $em->remove($country);
            $em->flush();
            $this->addFlash('danger', 'Le Pays est bien Supprimé');
            return $this->redirectToRoute('app_admin_country_index');
    }
}
