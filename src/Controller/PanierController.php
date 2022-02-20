<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;

class PanierController extends AbstractController
{
    /**
     * @param PanierService $panier
     * @return mixed
     */
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

    /**
     * @param PanierService $panier
     * @param $idProduit
     * @param $quantite
     * @return mixed
     */
    public function ajouter(PanierService $panier, $idProduit, $quantite){
        $panier->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier');
    }

    /**
     * @param PanierService $panier
     * @param int $idProduit
     * @return mixed
     */
    public function enlever(PanierService $panier, int $idProduit){
        $panier->enleverProduit($idProduit, 1);
        return $this->redirectToRoute('panier');
    }

    /**
     * @param PanierService $panier
     * @param int $idProduit
     * @return mixed
     */
    public function supprimer(PanierService $panier, int $idProduit){
        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier');
    }

    /**
     * @param PanierService $panier
     * @return mixed
     */
    public function vider(PanierService $panier){
        $panier->vider();
        return $this->redirectToRoute('panier');
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param PanierService $panier
     * @return mixed
     */
    public function valider(ManagerRegistry $doctrine,PanierService $panier){

        $em = $doctrine->getManager();

        if($this->getUser()){
            $panier->panierToCommande($this->getUser(),$em);
            return $this->redirectToRoute('usager_orders');
        }else{
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @param PanierService $panier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nbProduits(PanierService $panier)
    {
        $nbProduits = $panier->getContenu() ? $panier->getNbProduits() : 0;

        return $this->render('Article/nbProduits.html.twig', array('nbProduits' => $nbProduits));
    }
}