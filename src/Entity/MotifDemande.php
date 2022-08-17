<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MotifDemandeRepository")
 */
class MotifDemande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dossier", mappedBy="motifDemande", orphanRemoval=true)
     */
    private $dossier;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $docsACharger;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

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

    public function __toString()
    {
        return $this->libelle;
    }

    /**
     * @return mixed
     */
    public function getDocsACharger()
    {
        return $this->docsACharger;
    }

    /**
     * @param mixed $docsACharger
     */
    public function setDocsACharger($docsACharger): void
    {
        $this->docsACharger = $docsACharger;
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
