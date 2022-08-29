<?php 
namespace App\Utils;

use App\Entity\Dossier;

class GenerateUtils {
	
	public static function newKey($entityManager)
	{
		$lastId = $entityManager->getRepository(Dossier::class)
								->getLastDossierId();
		
		if($lastId['last_id'] === NULL){
			$lastId = 0;
		}
		
		$uniqueDossier = '';
		for($i = 0; $i < 9; $i++) {
			$uniqueDossier .= mt_rand(0, 9);
		}
		
		$uniqueDossier = substr($lastId['last_id'].$uniqueDossier, 0, 4);
		
		return array('uniqueDossier' => $uniqueDossier);
	}
}
