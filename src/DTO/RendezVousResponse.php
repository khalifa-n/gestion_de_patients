<?php

namespace App\DTO;

use App\Entity\RendezVous;

class RendezVousResponse
{
    public $id;
    public $numeroRendezVous;
    public $patientId;
    public  $dossierMedicalId;
    public $date;
    public $type;
    public $etat;
    public function __construct(RendezVous $rendezVous){
        $this->id = $rendezVous->getId();
        $this->patientId = $rendezVous->getPatient() ? $rendezVous->getPatient()->getId() : null;
        $this->dossierMedicalId = $rendezVous->getDossierMedical() ? $rendezVous->getDossierMedical()->getId() : null;
        $this->date = $rendezVous->getDate();
        $this->type = $rendezVous->getType();
        $this->etat = $rendezVous->getEtat();

    }
    public function fromEntity( $rendezVous): self
    {
        return new self($rendezVous);
    }
}
