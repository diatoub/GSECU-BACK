<?php

namespace App\Entity;

use App\Repository\MotifRemplacementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MotifRemplacementRepository::class)
 */
class MotifRemplacement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docsACharger;

    /**
     * @ORM\OneToMany(targetEntity=Dossier::class, mappedBy="motifRemplacement")
     */
    private $dossiers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection();
    }

    
    public function __toString()
    {
        return $this->libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDocsACharger(): ?string
    {
        return $this->docsACharger;
    }

    public function setDocsACharger(?string $docsACharger): self
    {
        $this->docsACharger = $docsACharger;

        return $this;
    }

    /**
     * @return Collection<int, Dossier>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setMotifRemplacement($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossiers->removeElement($dossier)) {
            // set the owning side to null (unless already changed)
            if ($dossier->getMotifRemplacement() === $this) {
                $dossier->setMotifRemplacement(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
