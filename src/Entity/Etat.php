<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtatRepository")
 */
class Etat
{
    const NOUVEAU     		 		 = 'NOUVEAU';
    const OUVERT     		 		 = 'OUVERT';
    const AFFECTE      		 	 	 = 'AFFECTE';
    const TRAITEMENT		 		 = 'TRAITEMENT';
    const CLOTURE  					 = 'CLOTURE';
    const VALIDE					 = 'VALIDE';
    const REJETE					 = 'REJETE';
    const ATTENTE_VALIDATION		 = 'ATTENTE_VALIDATION';
    const TRANSFERE                  = 'TRANSFERE';
    const SIGNE                      = 'SIGNE';

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

    public function __toString()
    {
        return $this->libelle;
    }
}
