<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCommandeRepository::class)]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $commande;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $produit;

    #[ORM\Column(type: 'integer')]
    private $quantite;

    #[ORM\Column(type: 'float')]
    private $prix;

    /**
     * @param Commande $commande
     * @param Produit $produit
     * @param int $quantite
     * @param float $prix
     */
    public function __construct(Commande $commande, Produit $produit, int $quantite, float $prix)
    {
        $this->commande = $commande;
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prix = $prix;
    }

    /**
     * @return Commande|null
     */
    public function getIdCommande(): ?Commande
    {
        return $this->id_commande;
    }

    /**
     * @param Commande|null $id_commande
     * @return $this
     */
    public function setIdCommande(?Commande $id_commande): self
    {
        $this->id_commande = $id_commande;

        return $this;
    }

    /**
     * @return Produit|null
     */
    public function getIdProduit(): ?Produit
    {
        return $this->id_produit;
    }

    /**
     * @param Produit|null $id_produit
     * @return $this
     */
    public function setIdProduit(?Produit $id_produit): self
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     * @return $this
     */
    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrix(): ?float
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     * @return $this
     */
    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
