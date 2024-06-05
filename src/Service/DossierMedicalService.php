<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\DossierMedical;
use Doctrine\ORM\ORMException;
use App\DTO\DossierMedicalRequest;
use App\DTO\DossierMedicalResponse;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DossierMedicalRepository;
use App\Repository\PatientRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DossierMedicalService
{
    private EntityManagerInterface $entityManager;
    private DossierMedicalRepository $dossierMedicalRepository;
    private PatientRepository $patientRepository;

    private ValidatorInterface $validator;
    private DossierMedical $dossierMedical;
    private DossierMedicalResponse $dossierMedicalResponse;
    private NotFoundException $notFoundException;


    public function __construct(
        EntityManagerInterface $entityManager,
        DossierMedicalRepository $dossierMedicalRepository,
        PatientRepository $patientRepository,
        ValidatorInterface $validator,
        DossierMedical $dossierMedical,
        DossierMedicalResponse $dossierMedicalResponse,
        NotFoundException $notFoundException

    ){
        $this->entityManager = $entityManager;
        $this->dossierMedicalRepository = $dossierMedicalRepository;
        $this->patientRepository = $patientRepository;
        $this->validator = $validator;
        $this->dossierMedical = $dossierMedical;
        $this->dossierMedicalResponse = $dossierMedicalResponse;
        $this->notFoundException = $notFoundException;
    }
    public function getAllDossiersMedicals()
    {
        return $this->dossierMedicalRepository->findAll();
    }

    public function createDossierMedical(DossierMedicalRequest $request): array
    {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();

        try {
            $dossierMedical = $this->dossierMedical;
            $dossierMedical = $this->populateDossierMedical($dossierMedical, $request);
            $this->validateAndPersist($dossierMedical);
            $entityManager->commit();
            return ['dossierMedical' => $this->dossierMedicalResponse->fromEntity($dossierMedical)];
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function updateDossierMedical(int $id, DossierMedicalRequest $request): array
    {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();

        try {
            $dossierMedical = $this->findDossierMedicalById($id);
            $dossierMedical = $this->populateDossierMedical($dossierMedical, $request);
            $this->validateAndPersist($dossierMedical);
            $entityManager->commit();
            return ['dossierMedical' => $this->dossierMedicalResponse->fromEntity($dossierMedical)];
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function deleteDossierMedical(int $id): void
    {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();

        try {
            $dossierMedical = $this->findDossierMedicalById($id);
            $entityManager->remove($dossierMedical);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function findDossierMedicalById(int $id): DossierMedical
    {
        $dossierMedical = $this->dossierMedicalRepository->find($id);
        if (!$dossierMedical) {
            throw $this->notFoundException;
        }
        return $dossierMedical;
    }

    private function populateDossierMedical(DossierMedical $dossierMedical, DossierMedicalRequest $request): DossierMedical
    {
        $dossierMedical->setNumeroDossier($this->generateNumeroDossier());
        $dossierMedical->setDate(new \DateTime('now'));
        $dossierMedical->setPatient($this->findPatientById($request->patientId));
        $dossierMedical->setGroupeSanguin($request->groupeSanguin);
        $dossierMedical->setPoids($request->poids);
        $dossierMedical->setTaille($request->taille);
        $dossierMedical->setTension($request->tension);
        return $dossierMedical;
    }

    private function validateAndPersist(DossierMedical $dossierMedical): void
    {
        $errors = $this->validator->validate($dossierMedical);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        $this->entityManager->persist($dossierMedical);
        $this->entityManager->flush();
    }

    private function findPatientById(int $id): Patient
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            throw $this->notFoundException;
        }
        return $patient;
    }
    private function generateNumeroDossier(): string
    {
        $timestamp = date('dmyHis');
        return 'DOS-' . $timestamp;
    }
    
}
