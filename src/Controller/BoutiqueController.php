<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;

class BoutiqueController extends AbstractController
{

    public function index(CategorieRepository $categorieRepository)
    {
        $title = 'Boutique';
        $categories = $categorieRepository->findAll();
        return $this->render('boutique.html.twig', [
                'title' => $title,
                'categories' => $categories,
                ]);
    }

    public function rayon($idCategorie, ProduitRepository $produitRepository, CategorieRepository $categoryRepository)
    {
        $title = 'Boutique';
        $categorie = $categoryRepository->findOneById($idCategorie);
        $produits = $produitRepository->findProduitsByCategory($idCategorie);
        return $this->render('rayon.html.twig', [
                'title' => $title,
                'produits' => $produits,
                'nbProduits' => count($produits),
                'categoryLabel' => $categorie->getLibelle(),
        ]);
    }
}

