<?php
namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController //AbstractController sert ici à utiliser la méthode render() qui est très utilisée
{
    // Affiche tout les biens
    #[Route('/',name:"home")]
    public function index(PropertyRepository $repository):Response
    {
        $properties = $repository ->findLatestProperty(); //Méthode que j'ai crée moi même qui permet de trouver le dernier bien
        return $this->render('pages/home.html.twig',[
            "properties" => $properties, //un tableau de biens
        ]);
    }
}
