<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController 
{

    public function __construct(PropertyRepository $repository, ManagerRegistry $doctrine)
    {

        $this->repository = $repository; 
        $this -> doctrine = $doctrine;       

    }

    //affiche tout les biens
    #[Route('/admin', name:"admin.property.index")]
    public function index(): Response
    {

        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig',compact('properties'));

    }
    //permet de créer un bien
    #[Route('/admin/property/create', name:"admin.property.new")]
    public function new(Request $request)
    {

        $property = new Property();
        $form = $this->createForm(PropertyType::class,$property); // créé le formulaire
        $form->handleRequest($request);// le créer vraiment

        if($form->isSubmitted() && $form->isValid())// dans ce sens car dans l'autre ça créée une erreur, il ne sait pas si un form non submit est valid ou pas
        {
            $em = $this -> doctrine ->getManager();
            $em -> persist($property); // persist() va dire à l'ORM de sauvegarder l'objet en base de données lors du prochain flush(). flush() permet de reporter ls modifications faites au sein des objets dans la base de données. 
            
            $em -> flush();
            $this -> addFlash('success','Bien créé avec succès');
            return $this->redirectToRoute('admin.property.index');

        }
        return $this->render('admin/property/new.html.twig',
        [
            "property" => $property,
            "form" => $form->createView()
        ]);

    }

    //permet d'éditer un bien
    #[Route('/admin/property/{id}', name:"admin.property.edit", methods:"GET|POST")]
    public function edit(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class,$property);//SF comprend tout seul quel est l'id du bien
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())// dans ce sens car dans l'autre ça créée une erreur, il ne sait pas si un form non submit est valid ou pas
        {
            $em = $this -> doctrine ->getManager();
            $em -> flush();
            $this -> addFlash('success','Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');

        }

        return $this->render('admin/property/edit.html.twig',
    [
        "property" => $property,
        "form" => $form->createView()
    ]);
    }


    //permet de supprimer un bien
    #[Route('/admin/property/{id}', name:"admin.property.delete", methods:"DELETE")]
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token')))
        {
            $em = $this->doctrine->getManager();
            $em -> remove($property);
            $em -> flush();
            $this -> addFlash('success','Bien supprimé avec succès');

        }

        return $this->redirectToRoute('admin.property.index');   

    }

}