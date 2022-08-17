<?php
namespace App\Mapping;



class EquipementMapping  {

    public function listeEquipements($les_equipement) {
        $equipements = array();
        foreach ($les_equipement as $equipement) {
            $equipements[] = $this->mappingEquipement($equipement);
        }
        return $equipements;
    }

    public function mappingEquipement($equipement) {
        return array(
            'id' => $equipement['id'],
            'libelle' => $equipement['libelle']?$equipement['libelle']:null,
            'description' =>  $equipement['description']?$equipement['description']:null,
        );
    }

    public function setEquipementData($data, $equipement) {
        return $equipement->setLibelle(isset($data['libelle']) ? $data['libelle'] : $equipement->getLibelle())
                         ->setDescription(isset($data['description']) ? $data['description'] : $equipement->getDescription());
    }
}

?>