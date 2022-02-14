<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProduitRepository;

// Service pour manipuler le panier et le stocker en session
class PanierService
{

    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier
    private $session;
    private $boutique;
    private $panier;

    public function __construct(RequestStack $requestStack,ProduitRepository $boutique)
    {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(PanierService::PANIER_SESSION,array());
 }

    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu()
    {
        if ($this->panier) {
            $produits = [];
            // On crée un tableau d'éléments [ "produit" => produit, "quantite" => quantite ]
            foreach ($this->panier as $id => $quantite) {
                $produits[] = [
                    'produit' => $this->boutique->find($id),
                    'quantite' => $quantite,
                ];
            }

            return $produits;
        }
    }

    public function getTotal()
    {
        // Récupération du contenu du panier en session
        // Si le panier n'est pas vide
        if ($this->panier) {
            $total = 0;
            // On crée un tableau d'éléments [ "product" => product, "quantity" => quantity ]
            foreach ($this->panier as $id => $quantite) {
                $produit = $this->boutique->find($id);
                $total += $produit->getPrix() * $quantite;
            }
            return $total;
        }
    }

    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits()
    {
        // Récupération du contenu du panier en session
        // Si le panier n'est pas vide
        if ($this->panier) {
            $nbProducts = 0;
            // On crée un tableau d'éléments [ "product" => product, "quantity" => quantity ]
            foreach ($this->panier as $quantite) {
                $nbProducts += $quantite;
            }
            return $nbProducts;
        }
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite)
    {
        // Si le panier n'est pas vide
        if ($this->panier) {
            // Si le produit est déjà dans le panier
            if (array_key_exists($idProduit, $this->panier)) {
                // On ajoute la quantité
                $this->panier[$idProduit] += $quantite;
            } else {
                // Sinon on ajoute le produit
                $this->panier[$idProduit] = $quantite;
            }
        } else {
            // Sinon on crée le panier
            $this->panier = [$idProduit => $quantite];
        }
        // On enregistre le panier en session
        $this->session->set(PanierService::PANIER_SESSION, $this->panier);
    }

    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1)
    {
        // Si le panier n'est pas vide
        if ($this->panier) {
            // Si le produit est dans le panier
            if (array_key_exists($idProduit, $this->panier)) {
                // On enlève la quantité
                $this->panier[$idProduit] -= $quantite;
                // Si la quantité est nulle ou négative
                if ($this->panier[$idProduit] <= 0) {
                    // On supprime le produit
                    unset($this->panier[$idProduit]);
                }
            }
        }
        // On enregistre le panier en session
        $this->session->set(PanierService::PANIER_SESSION, $this->panier);
    }

    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit)
    {
        // Si le panier n'est pas vide
        if ($this->panier) {
            // Si le produit est dans le panier
            if (array_key_exists($idProduit, $this->panier)) {
                // On supprime le produit
                unset($this->panier[$idProduit]);
            }
        }
        // On enregistre le panier en session
        $this->session->set(PanierService::PANIER_SESSION, $this->panier);
    }

    // vider vide complètement le panier
    public function vider()
    {
        // Si le panier n'est pas vide
        if ($this->panier) {
            // On vide le panier
            $this->panier = [];
        }
        // On enregistre le panier en session
        $this->session->set(PanierService::PANIER_SESSION, $this->panier);
    }
}