<?php

namespace App\Controller;

use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function plusVendusAction(LigneCommandeRepository $repoLigne, ProduitRepository $repoProduit)
    {
        $lignes = $repoLigne->findProduitsPlusVendus();
        $topVentes = [];
        foreach ($lignes as $ligne){
            $topVentes[] = ["produit" => $repoProduit->find($ligne['produit']), "quantite" => $ligne['quantite']];
        }

        return $this->render('Article/plusVendus.html.twig', array('produits' => $topVentes));
    }
}