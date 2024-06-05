<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class DossierMedicalRequest{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $numeroDossier;

    #[Assert\NotBlank]
    #[Assert\Type("\DateTimeInterface")]
    public $date;

    #[Assert\NotBlank]
    public $patientId;

    #[Assert\Length(max: 255)]
    public $groupeSanguin;

    #[Assert\Length(max: 255)]
    public $poids;

    #[Assert\Length(max: 255)]
    public $taille;

    #[Assert\Length(max: 255)]
    public $tension;

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function setData(array $data): void
    {
        $this->numeroDossier = $data['numeroDossier'] ?? $this->numeroDossier;
        $this->date = isset($data['date']) ? new \DateTime($data['date']) : $this->date;
        $this->patientId = $data['patientId'] ?? $this->patientId;
        $this->groupeSanguin = $data['groupeSanguin'] ?? $this->groupeSanguin;
        $this->poids = $data['poids'] ?? $this->poids;
        $this->taille = $data['taille'] ?? $this->taille;
        $this->tension = $data['tension'] ?? $this->tension;

    }
}
