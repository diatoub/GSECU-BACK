<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class RechercheDossier
{
    private $libelle;

    private $codeDossier;

    private $site;

    private $typeDossier;

    private $etat;

    private $typeMateriel;

    private $etatMateriel;

    private $dateDebut;

    private $dateFin;

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getTypeMateriel()
    {
        return $this->typeMateriel;
    }

    public function setTypeMateriel($typeMateriel)
    {
        $this->typeMateriel = $typeMateriel;
        return $this;
    }

    public function getEtatMateriel()
    {
        return $this->etatMateriel;
    }

    public function setEtatMateriel($etatMateriel)
    {
        $this->etatMateriel = $etatMateriel;
        return $this;
    }

    public function getCodeDossier()
    {
        return $this->codeDossier;
    }

    public function setCodeDossier($codeDossier)
    {
        $this->codeDossier = $codeDossier;
        return $this;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    public function getTypeDossier()
    {
        return $this->typeDossier;
    }

    public function setTypeDossier($typeDossier)
    {
        $this->typeDossier = $typeDossier;
        return $this;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }
}
