<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtatMaterielRepository")
 */
class EtatMateriel
{
    const MATERIEL_FONCTIONNEL 		= 'FONCTIONNEL';
    const MATERIEL_DEFECTUEUX 		= 'DEFECTUEUX';
    const MATERIEL_REPARATION 		= 'REPARATION';
    const MATERIEL_IRREPARABLE 		= 'IRREPARABLE';
    const MATERIEL_REPARE	 		= 'REPARE';

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
