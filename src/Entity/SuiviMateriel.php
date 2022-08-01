<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuiviMaterielRepository")
 */
class SuiviMateriel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Materiel
     *
     * @ORM\ManyToOne(targetEntity="Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materiel_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $Materiel;

    /**
     * @var EtatMateriel
     *
     * @ORM\ManyToOne(targetEntity="EtatMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etat_materiel_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $etatMateriel;

    /**
     * @var SuiviMateriel
     *
     * @ORM\ManyToOne(targetEntity="Dossier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $dossier;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Materiel
     */
    public function getMateriel(): ?Materiel
    {
        return $this->Materiel;
    }

    /**
     * @param Materiel $Materiel
     */
    public function setMateriel(Materiel $Materiel): void
    {
        $this->Materiel = $Materiel;
    }

    /**
     * @return EtatMateriel
     */
    public function getEtatMateriel(): ?EtatMateriel
    {
        return $this->etatMateriel;
    }

    /**
     * @param EtatMateriel $etatMateriel
     */
    public function setEtatMateriel(EtatMateriel $etatMateriel): void
    {
        $this->etatMateriel = $etatMateriel;
    }

    /**
     * @return SuiviMateriel
     */
    public function getDossier(): ?SuiviMateriel
    {
        return $this->dossier;
    }

    /**
     * @param SuiviMateriel $dossier
     */
    public function setDossier(?SuiviMateriel $dossier): void
    {
        $this->dossier = $dossier;
    }

    /**
     * @return int
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }


}
