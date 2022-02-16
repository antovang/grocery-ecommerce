<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Usager::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $usager;

    #[ORM\Column(type: 'date')]
    private $date_commande;

    #[ORM\Column(type: 'string', length: 255)]
    private $statut;

    #[ORM\OneToMany(mappedBy: 'id_commande', targetEntity: LigneCommande::class)]
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsager(): ?Usager
    {
        return $this->usager;
    }

    public function setUsager(?Usager $usager): self
    {
        $this->usager = $usager;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->date_commande = $dateCommande;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(LigneCommande $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setIdCommande($this);
        }

        return $this;
    }

    public function removeProduit(LigneCommande $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdCommande() === $this) {
                $produit->setIdCommande(null);
            }
        }

        return $this;
    }
}
