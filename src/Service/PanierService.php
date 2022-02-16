<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProduitRepository;
use App\Entity\Usager;
use App\Entity\Commande;
use App\Entity\LigneCommande;

// Service pour manipuler le panier et le stocker en session
class PanierService
{

    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier

    private $session;
    private $boutique;
    private $panier;

    /**
     * @param RequestStack $requestStack
     * @param ProduitRepository $boutique
     */
    public function __construct(RequestStack $requestStack, ProduitRepository $boutique)
    {
        $this->boutique = $boutique;
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(PanierService::PANIER_SESSION,array());
 }

    /**
     * @return array|void
     */
    public function getContenu()
    {
        if ($this->panier) {
            $produits = [];
            foreach ($this->panier as $id => $quantite) {
                $produits[] = [
                    'produit' => $this->boutique->find($id),
                    'quantite' => $quantite,
                ];
            }
            // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
            return $produits;
        }
    }

    /**
     * @return float|int|void
     */
    public function getTotal()
    {
        // Récupération du contenu du panier en session
        // Si le panier n'est pas vide
        if ($this->panier) {
            $total = 0;
            foreach ($this->panier as $id => $quantite) {
                $produit = $this->boutique->find($id);
                $total += $produit->getPrix() * $quantite;
            }
            return $total;
        }
    }

    /**
     * @return int|mixed|void
     */
    public function getNbProduits()
    {
        // Récupération du contenu du panier en session
        // Si le panier n'est pas vide
        if ($this->panier) {
            $nbProducts = 0;
            foreach ($this->panier as $quantite) {
                $nbProducts += $quantite;
            }
            return $nbProducts;
        }
    }

    /**
     * @param int $idProduit
     * @param int $quantite
     */
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

    /**
     * @param int $idProduit
     * @param int $quantite
     */
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

    /**
     * @param int $idProduit
     */
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

    /**
     *
     */
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


    /**
     * @param Usager $usager
     * @param EntityManagerInterface $em
     * @return Commande
     */
    public function panierToCommande(Usager $usager, EntityManagerInterface $em)
    {
        $commande = new Commande();
        $commande->setUsager($usager);
        $commande->setDateCommande(new \DateTime('now'));
        $commande->setStatut("En cours");
        $em->persist($commande);
        $em->flush();

        foreach($this->getContenu() as $item){
            //$id_commande, $id_produit, $quantite, $prix
            $ligne = new LigneCommande
            (
                $commande,$item['produit'],$item['quantite'],$item['produit']->getPrix() * $item['quantite']
            );
            $em->persist($ligne);
            $commande->addProduit($ligne);
        }
        $em->flush();
        $this->vider();

        return $commande;
    }
}