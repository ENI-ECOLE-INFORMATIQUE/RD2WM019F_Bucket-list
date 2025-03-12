<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'wish_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', []);
    }

    #[Route('/wishes/{id}', name: 'wish_detail',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        return $this->render('wish/detail.html.twig', []);
    }
}
