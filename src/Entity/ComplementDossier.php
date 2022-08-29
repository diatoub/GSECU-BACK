<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplementDossierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ComplementDossier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     * @Assert\NotNull(message="Vous devez joindre la pièce !")
     */
    public $file;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Dossier", mappedBy="complementDossier")
     */
    protected $dossier;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dossier = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir($doc_dir)
    {
        //var_dump($doc_dir);exit('yes');
        //return __DIR__.'/../../../../public/'.$this->getUploadDir();
        return $doc_dir;
        //return $this->get('kernel')->getProjectDir() . '/public/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if(null !== $this->file)
        {
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload($doc_dir)
    {
        if(null === $this->file)
        {
            return;
        }

        $this->file->move($doc_dir, $this->path);

        //unset($this->file);
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($file = $this->getAbsolutePath()){
            unlink($file);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTempPath() {
        return $this->path ? $this->file->getPathname() : null;
    }

    public function getFileName() {
        return $this->path;
    }

    public function getPath(): ?string
    {
        //return $this->path;
        return $this->getWebPath();
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
