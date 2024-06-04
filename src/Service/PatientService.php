<?php
namespace App\Service;

use App\Entity\Patient;
use App\DTO\PatientRequest;
use App\DTO\PatientResponse;
use App\Repository\PatientRepository;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\ORMException;
use App\Exception\NotFoundException;


class PatientService
{
    private EntityManagerInterface $entityManager;
    private PatientRepository $patientRepository;
    private ValidatorInterface $validator;
    private Patient $patient;
    private PatientResponse $patientResponse;
    private NotFoundException $notFoundException;

    
    public function __construct(
        EntityManagerInterface $entityManager,
        PatientRepository $patientRepository,
        ValidatorInterface $validator,
        Patient $patient,
        PatientResponse $patientResponse,
        NotFoundException $notFoundException
    ){
        $this->entityManager = $entityManager;
        $this->patientRepository = $patientRepository;
        $this->validator = $validator;
        $this->patient = $patient;
        $this->patientResponse = $patientResponse;
        $this->notFoundException = $notFoundException;
    }
    
    public function getAllPatients(){
        return $this->patientRepository->findAll();
    }
    
    public function createPatient(PatientRequest $request): array {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();
        
        try {
            $patient = $this->patient;
            $patient = $this->populatePatient($patient, $request);
            $this->validateAndPersist($patient);
            $entityManager->commit();
            return ['patient' => $this->patientResponse->fromEntity($patient)];
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function updatePatient(int $id, PatientRequest $request): array {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();
        
        try {
            $patient = $this->findPatientById($id);
            $patient = $this->populatePatient($patient, $request);
            $this->validateAndPersist($patient);
            $entityManager->commit();
            return ['patient' => $this->patientResponse->fromEntity($patient)];
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function deletePatient(int $id): void {
        $entityManager = $this->entityManager;
        $entityManager->beginTransaction();
        
        try {
            $patient = $this->findPatientById($id);
            $entityManager->remove($patient);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }
    public function findPatientById(int $id): Patient
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            throw $this->notFoundException; // Utiliser l'exception injectée
        }
        return $patient;
    }
    private function populatePatient(Patient $patient, PatientRequest $request): Patient {
        $patient->setNumeroPatient($this->generateNumeroPatient());
        $patient->setNom($request->nom);
        $patient->setPrenom($request->prenom);
        $patient->setAdresse($request->adresse);
        $patient->setTelephone($request->telephone);
        $patient->setAge($request->age);
        return $patient;
    }
    
    private function validateAndPersist(Patient $patient): void {
        $errors = $this->validator->validate($patient);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        $this->entityManager->persist($patient);
        $this->entityManager->flush();
    }
    private function generateNumeroPatient(): string
    {
        $timestamp = date('dmyHis');
        return 'PAT-' . $timestamp;
    }
    

}
