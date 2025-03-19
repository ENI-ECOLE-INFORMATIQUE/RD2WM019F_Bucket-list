<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'wish_list', methods: ['GET'])]
    public function list(WishRepository $wishrepository): Response
    {

        //Récupére les wish publiés, du plus récent au plus ancien
        $wishes = $wishrepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', ["wishes" => $wishes]);
    }

    #[Route('/wishes/{id}', name: 'wish_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishrepository): Response
        // public function detail(Wish $wish, WishRepository $wishrepository): Response
    {
        //Récupère ce wish en fonction de l'id présent dans l'url
        $wish = $wishrepository->find($id);
        if (!$wish) {
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        return $this->render('wish/detail.html.twig', ["wish" => $wish]);
    }

    #[Route('/wishes/create', name: 'wish_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        //Création de l'entité vide
        $wish = new Wish();
        $wish->setAuthor($this->getUser()->getUserIdentifier());
        //Création du formulaire et association de l'entité vide.
        $wishForm = $this->createForm(WishType::class, $wish);
        //Récupère les données du formulaire et on les injecte dans notre $wish.
        $wishForm->handleRequest($request);
        //On vérifie si le formulaire a été soumis et que les données soumises sont valides.
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            //Hydrater les propriétés absentes du formulaire
//            $wish->setIsPublished(true);
            //Sauvegarde dans la Bdd
            //ajout de la relation avec le user
            $wish->setUser($this->getUser());
            $em->persist($wish);
            $em->flush();
            //Affiche un message à l'utilisateur sur la prochaine page.
            $this->addFlash('success', 'Your wish has been created!');
            //Redirige vers la page de detail du wish
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
        //Affiche le formulaire
        return $this->render('wish/create.html.twig', ["wishForm" => $wishForm]);
    }


    #[Route('/wishes/{id}/update', name: 'wish_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('WISH_EDIT', 'wish')]
    public function update(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
        //Récupération de l'entité wish  en fonction de son id.

        //s'il n'existe pas dans la bdd, on lance une erreur 404
        if (!$wish) {
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        //Création du formulaire et association de l'entité .
        $wishForm = $this->createForm(WishType::class, $wish);
        //Récupère les données du formulaire et on les injecte dans notre $wish.
        $wishForm->handleRequest($request);
        //On vérifie si le formulaire a été soumis et que les données soumises sont valides.
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            //Hydrater les propriétés absentes du formulaire
            $wish->setDateUpdated(new \DateTimeImmutable());
            //Sauvegarde dans la Bdd
            $em->flush();
            //Affiche un message à l'utilisateur sur la prochaine page.
            $this->addFlash('success', 'Your wish has been updated!');
            //Redirige vers la page de detail du wish
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
        //Affiche le formulaire
        return $this->render('wish/create.html.twig', ["wishForm" => $wishForm]);
    }

    #[Route('/wishes/{id}/delete', name: 'wish_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(int $id, WishRepository $wishrepository, Request $request, EntityManagerInterface $em): Response
    {
        $wish = $wishrepository->find($id);
        //s'il n'existe pas dans la bdd, on lance une erreur 404
        if (!$wish) {
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }

        //si je ne suis pas le proprio et que je ne suis pas admin alors je ne peux pas y accéder
//        if(!($wish->getUser() === $this->getUser() || $this->isGranted("ROLE_ADMIN"))){
//            throw $this->createAccessDeniedException("Pas possible gamin !");
//        }
        if (!$this->isGranted('WISH_DELETE', $wish)) {
            throw $this->createAccessDeniedException("Pas possible gamin !");
        }
        if ($this->isCsrfTokenValid('delete' . $wish->getId(), $request->query->get('_token'))) {
            $em->remove($wish, true);
            $em->flush();
            $this->addFlash('success', 'Your wish has been deleted!');
        } else {
            $this->addFlash('danger', 'Your wish cannot be deleted!');
        }
        return $this->redirectToRoute('wish_list');
    }
}
