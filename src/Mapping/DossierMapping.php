<?php
namespace App\Mapping;



class DossierMapping  {    

    public function mappingInfoUser($infoUser) {
        return array(
            "id"=> $infoUser->getId() ? $infoUser->getId() : null,
            "email"=> $infoUser->getEmail() ? $infoUser->getEmail() : null,
            "nom"=> $infoUser->getNom() ? $infoUser->getNom() : null,
            "prenom"=> $infoUser->getPrenom() ? $infoUser->getPrenom() : null,
            "telephone"=> $infoUser->getTelephone() ? $infoUser->getTelephone() : null,
            "profil"=> $infoUser->getProfil() ? $infoUser->getProfil()->getLibelle() : null
        );
    }
    public function mappingDossier($dossier) {
        return array(
            "id"=> $dossier->getId() ? $dossier->getId() : null,
            "libelle"=> $dossier->getLibelle() ? $dossier->getLibelle() : null,
            "description"=> $dossier->getDescription() ? $dossier->getDescription() : null,
            "codeDossier"=> $dossier->getCodeDossier() ? $dossier->getCodeDossier() : null,
            "etatTraitement"=> $dossier->getEtat() ? $dossier->getEtat()->getLibelle() : null,
            "siteBeneficiaire"=> $dossier->getSiteBeneficiaire() ? $dossier->getSiteBeneficiaire()->getLibelle() : null,
            "site"=> $dossier->getSite() ? $dossier->getSite()->getLibelle() : null,
            "motifDemande"=> $dossier->getMotifDemande() ? $dossier->getMotifDemande()->getLibelle() : null,
            "typeBadge"=> $dossier->getTypeBadge() ? $dossier->getTypeBadge()->getLibelle() : null,
            "typeMateriel"=> $dossier->getTypeMateriel() ? $dossier->getTypeMateriel()->getLibelle() : null,
            "typeDossier"=> $dossier->getTypeDossier() ? $dossier->getTypeDossier()->getLibelle() : null,
            "dateAjout"=> $dossier->getDateAjout() ? date_format($dossier->getDateAjout(), 'd-m-Y'): null,
            "dateDebut"=> $dossier->getDateDebut() ? date_format($dossier->getDateDebut(), 'd-m-Y') : null,
            "dateFin"=> $dossier->getDateFin() ? date_format($dossier->getDateFin(), 'd-m-Y') : null,
            "dureeValidite"=> $dossier->getDureeValidite() ? date_format($dossier->getDureeValidite(), 'd-m-Y') : null
        );
    }

}

?>