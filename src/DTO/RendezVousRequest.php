<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RendezVousRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $numeroRendezVous;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public $patientId;
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public $dossierMedicalId;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    public $date;
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $type;
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $etat;
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }
    public function setData(array $data)
    {
        $this->patientId = $data['patientId'] ?? null;
        $this->dossierMedicalId = $data['dossierMedicalId'] ?? null;
        $this->numeroRendezVous = $data['numeroRendezVous'] ?? null;
        $this->date = $data['date'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->etat = $data['etat'] ?? null;
    }
}
