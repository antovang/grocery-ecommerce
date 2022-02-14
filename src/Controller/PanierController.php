<?php

namespace App\Controller;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;

class PanierController extends AbstractController
{
    public function index(PanierService $panier){
        $title = 'Panier';
        return $this->render('panier.html.twig',
            array(
                'title' => $title,
                'contenu' => $panier->getContenu(),
                'total' => $panier->getTotal(),
            )
        );
    }

    public function ajouter(PanierService $panier, $idProduit, $quantite, LoggerInterface $logger){
        $panier->ajouterProduit($idProduit, $quantite);
        $logger->debug(json_encode($panier->getContenu()));
        return $this->redirectToRoute('panier');
    }

    public function enlever(PanierService $panier, int $idProduit){
        $panier->enleverProduit($idProduit, 1);
        return $this->redirectToRoute('panier');
    }

    public function supprimer(PanierService $panier, int $idProduit){
        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier');
    }

    public function vider(PanierService $panier){
        $panier->vider();
        return $this->redirectToRoute('panier');
    }
}