<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuiviDelaiRepository")
 */
class SuiviDelai
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Dossier", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $dossier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ouverture", type="date", nullable=true)
     */
    private $dateOuverture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cloture", type="date", nullable=true)
     */
    private $dateCloture;

    /**
     *
     * @var integer @ORM\Column(name="is_hors_delai", type="boolean", nullable=true)
     */
    protected $isHorsDelai;

    /**
     * @var integer
     *
     * @ORM\Column(name="temps_traitement", type="integer", nullable=true)
     */
    private $interval;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set dateOuverture
     *
     * @param \DateTime $dateOuverture
     * @return SuiviDelai
     */
    public function setDateOuverture($dateOuverture)
    {
        $this->dateOuverture = $dateOuverture;

        return $this;
    }

    /**
     * Get dateOuverture
     *
     * @return \DateTime
     */
    public function getDateOuverture()
    {
        return $this->dateOuverture;
    }

    /**
     * Set dateCloture
     *
     * @param \DateTime $dateCloture
     * @return SuiviDelai
     */
    public function setDateCloture($dateCloture)
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    /**
     * Get dateCloture
     *
     * @return \DateTime
     */
    public function getDateCloture()
    {
        return $this->dateCloture;
    }

    /**
     * Set dossier
     *
     * @param Dossier $dossier
     * @return SuiviDelai
     */
    public function setDossier(Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return Dossier
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set isHorsDelai
     *
     * @param boolean $isHorsDelai
     * @return SuiviDelai
     */
    public function setIsHorsDelai($isHorsDelai)
    {
        $this->isHorsDelai = $isHorsDelai;

        return $this;
    }

    /**
     * Get isHorsDelai
     *
     * @return boolean
     */
    public function getIsHorsDelai()
    {
        return $this->isHorsDelai;
    }

    /**
     * Set interval
     *
     * @param integer $interval
     * @return SuiviDelai
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Get interval
     *
     * @return integer
     */
    public function getInterval()
    {
        return $this->interval;
    }

}
