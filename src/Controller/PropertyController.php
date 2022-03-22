<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

public function __construct(PropertyRepository $repository,ManagerRegistry $doctrine){

        $this->repository = $repository;
        $this->doctrine = $doctrine;


    }

    #[Route('/biens', name:"property.index")]
    public function index(): Response
    {
        // $em = $this->doctrine->getManager();
        // $property = $this->repository->findAllVisible();
        // $property[0] -> setSold(true);
        // $em->flush();
        // dump($property);




        // $repository = $this->doctrine->getRepository(Property::class);
        // dump($repository);


        // $em = $this->doctrine->getManager(); // Création de l'objet pour discuter avec la bdd
        // $property = new Property();
        // $property
        // ->setTitle("Mon premier bien")
        // ->setPrice(280000)
        // ->setRooms(4)
        // ->setBedrooms(3)
        // ->setDescription("Une description du biens")
        // ->setSurface(60)
        // ->setFloor(3)
        // ->setHeat(1)
        // ->setCity("Quimper")
        // ->setAddress("15 rue Bourgs les Bourgs")
        // ->setPostalCode("29000");

        // $em -> persist($property); // Dire à doctrine qu'on aimerais insérer les données
        // $em->flush(); // Là on insert vraiment les données

        return $this->render('property/index.html.twig',[
            'current_page' => 'properties'
        ]);
    }
    
    #[Route('/biens/{slug}-{id}', name:"property.show", requirements:["slug" => "[A-Za-z0-9\-]*"])]
    public function show(string $slug, Property $property): Response
    {

        if($property->getSlug() !== $slug)
        {
           return $this->redirectToRoute('property.show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
            ],301);
        }
        dump($property);


        return $this->render('property/show.html.twig',[
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }

}