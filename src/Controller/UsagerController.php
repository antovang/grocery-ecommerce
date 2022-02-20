<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsagerController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $title = "Mon Compte";
        return $this->render('usager/index.html.twig', array(
            'title' => $title,
            'user' => $this->getUser()
        ));
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $passwordHasher): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe qui est en clair pour l’instant
            $hashedPassword = $passwordHasher->hashPassword($usager, $usager->getPassword());
            $usager->setPassword($hashedPassword);
            // Définir le rôle de l’usager qui va être créé
            $usager->setRoles(["ROLE_CLIENT"]);
            $entityManager->persist($usager);
            $entityManager->flush();

            return $this->redirectToRoute('usager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form,
        ]);
    }

    /**
     * @return Response
     */
    public function commandes():Response{
        $title =  "Commandes";
        return $this->render("usager/commandes.html.twig", array(
            'title' => $title,
            'commandes' => $this->getUser()->getCommandes(),
        ));
    }
}
