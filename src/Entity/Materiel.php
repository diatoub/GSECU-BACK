<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielRepository")
 */
class Materiel
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var TypeMateriel
     *
     * @ORM\ManyToOne(targetEntity="TypeMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_materiel_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $typeMateriel;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $site;

    /**
     * @var EtatMateriel
     *
     * @ORM\ManyToOne(targetEntity="EtatMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etat_materiel_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $etatMateriel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Set site
     *
     * @param Site $site
     * @return Materiel
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set typeMateriel
     *
     * @param TypeMateriel $typeMateriel
     * @return Materiel
     */
    public function setTypeMateriel(TypeMateriel $typeMateriel = null)
    {
        $this->typeMateriel = $typeMateriel;

        return $this;
    }

    /**
     * Get typeMateriel
     *
     * @return TypeMateriel
     */
    public function getTypeMateriel()
    {
        return $this->typeMateriel;
    }

    /**
     * Set etatMateriel
     *
     * @param EtatMateriel $etatMateriel
     * @return Materiel
     */
    public function setEtatMateriel(EtatMateriel $etatMateriel = null)
    {
        $this->etatMateriel = $etatMateriel;
        return $this;
    }

    /**
     * Get etatMateriel
     *
     * @return EtatMateriel
     */
    public function getEtatMateriel()
    {
        return $this->etatMateriel;
    }

    public function __toString()
    {
        return " [". $this->site . "]";
    }
}
