<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'wish_list', methods: ['GET'])]
    public function list(WishRepository $wishrepository): Response
    {
        //Récupére les wish publiés, du plus récent au plus ancien
        $wishes = $wishrepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', ["wishes"=>$wishes]);
    }

    #[Route('/wishes/{id}', name: 'wish_detail',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishrepository): Response
    // public function detail(Wish $wish, WishRepository $wishrepository): Response
    {
        //Récupère ce wish en fonction de l'id présent dans l'url
        $wish = $wishrepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        return $this->render('wish/detail.html.twig', ["wish"=>$wish]);
    }
}
