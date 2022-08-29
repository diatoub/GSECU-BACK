<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Model\DossierManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class DossierController extends BaseController {

    protected $em;
    protected $dossierManager;
    public function __construct(ManagerRegistry $doctrine, DossierManager $dossierManager) 
    {
        $this->em = $doctrine->getManager();
        $this->dossierManager = $dossierManager;
    }


    /**
     * @Rest\Get("/les_dossiers", name="les_dossiers")
     * @QMLogger(message="listes dossiers")
     */
    public function listeDossier(Request $request) {
        $categorie = $request->query->get('categorie');
        $codeDossier = $request->query->get('codeDossier');
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('DateFin');
        $page=$request->query->get('page',1);
        $limit=$request->query->get('limit',$_ENV['LIMIT']);
        $filtre=$request->query->get('filtre','');
        $etat=$request->query->get('etat','');
        $site=$request->query->get('site','');
        return $this->dossierManager->lesDossiers($this->getUser(), $categorie, $codeDossier, $dateDebut, $dateFin, $page,$limit,$filtre, $etat, $site);
    }
    /**
     * @Rest\Get("/dossier/{id}", name="dossier/{id}")
     * @QMLogger(message="details dossiers")
     */
    public function detailDossier($id) {
        return $this->dossierManager->detailDossier($this->getUser(),$id);
    }

    /**
     * @Rest\Post("/check_dossier", name="check_dossier")
     * @QMLogger(message="recherche dossier par codeDossier")
     */
    public function check_dossier(Request $request) {
        $codeDossier = json_decode($request->getContent(), true);
        return $this->dossierManager->check_dossier($this->getUser(),$codeDossier);
    }
    
    /**
     * @Rest\Post("/close_dossier/{id}", name="cloture_dossier")
     * @QMLogger(message="Cloturer une demande/signalisation")
     */
    public function clotureAction($id) {
        return $this->dossierManager->clotureAction($this->getUser(), $id);
    }
    
    /**
     * @Rest\Post("/nouvelleSignalisation", name="nouvelleSignalisation")
     * @QMLogger(message="Ajouter une signalisation")
     */
    public function nouvelleSignalisation(Request $request) {
        $post = $request->request->all();
        $post['file'] = $request->files->get('file');
        $post['images_directory'] = $this->getParameter('images_directory');
        $post['document_directory'] = $this->getParameter('document_directory');
        return $this->dossierManager->nouvelleSignalisation($this->getUser(),$post);
    }
    /**
     * @Rest\Post("/nouvelleDemande", name="nouvelleDemande")
     * @QMLogger(message="Ajouter une demande")
     */
    public function nouvelleDemande(Request $request) {
        $post = $request->request->all();
        $post['logoFile'] = $request->files->get('logo');
        $post['ninea'] = $request->files->get('ninea');
        $post['cnidg'] = $request->files->get('cnidg');
        $post['rccm'] = $request->files->get('rccm');
        $post['contrat'] = $request->files->get('contrat');
        $codeDossier = json_decode($request->getContent(), true);
        return $this->dossierManager->nouvelleDemande($this->getUser(),$post);
    }
}

?>