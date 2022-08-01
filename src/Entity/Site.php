<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    const ROLE_SIGNALEUR     		 = 'SIGNALEUR';
    const ROLE_EXECUTEUR      		 = 'EXECUTEUR';
    const ROLE_ADMINISTRATEUR		 = 'ADMIN';
    const ROLE_SUPER_ADMINISTRATEUR  = 'SUPER_ADMINISTRATUER';
    const ROLE_DEMANDEUR  			 = 'DEMANDEUR';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Dossier", mappedBy="siteAutorisation")
     */
    protected $dossier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle ? $this->libelle:'';
    }
}
