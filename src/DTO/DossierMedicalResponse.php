<?php
namespace App\DTO;

use App\Entity\DossierMedical;

class DossierMedicalResponse
{
    public $id;
    public $numeroDossier;
    public $date;
    public $patientId;
    public $groupeSanguin;
    public $poids;
    public $taille;
    public $tension;

    public function __construct(DossierMedical $dossierMedical)
    {
        $this->id = $dossierMedical->getId();
        $this->numeroDossier = $dossierMedical->getNumeroDossier();
        $this->date = $dossierMedical->getDate();
        $this->patientId = $dossierMedical->getPatient() ? $dossierMedical->getPatient()->getId() : null;
        $this->groupeSanguin = $dossierMedical->getGroupeSanguin();
        $this->poids = $dossierMedical->getPoids();
        $this->taille = $dossierMedical->getTaille();
        $this->tension = $dossierMedical->getTension();
    }

    public function fromEntity(DossierMedical $dossierMedical): self
    {
        return new self($dossierMedical);
    }
}
