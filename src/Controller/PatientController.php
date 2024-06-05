<?php
namespace App\Controller;

use App\DTO\PatientRequest;
use App\DTO\PatientResponse;
use App\Service\PatientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/patient',name:'api_patient')]
class PatientController extends AbstractController
{
    private PatientService $patientService;
    private PatientResponse $patientResponse;
    private PatientRequest $patientRequest;


    public function __construct
    (PatientService $patientService,
    PatientResponse $patientResponse,
    PatientRequest $patientRequest
    ){
        $this->patientService = $patientService;
        $this->patientResponse = $patientResponse;
        $this->patientRequest = $patientRequest;
    }
    #[Route('/list', name: 'list_patient',methods:['GET'])]
    public function list(): JsonResponse
    {
        $patients = $this->patientService->getAllPatients();
        $data = [];
        foreach ($patients as $patient){
            $patientResponseData = $this->patientResponse->fromEntity($patient);
            $data[] = $patientResponseData;
        }
        return $this->json($data);
      
    }
    #[Route('/add', name: 'add_patient', methods: ['POST'])]
    public function create(Request $request,PatientRequest $patientRequest)
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $patientRequest->setData($data);
        $result = $this->patientService->createPatient($patientRequest);
        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        return $this->json(['message' => 'Patient created successfully.', 'patient' => $result['patient']], JsonResponse::HTTP_OK);
    }

    #[Route('/update/{id}', name: 'update_patient', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    if (!$data) {
        return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $this->patientRequest->setData($data);
    $result = $this->patientService->updatePatient($id, $this->patientRequest);

    if (isset($result['errors'])) {
        return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
    }

    return $this->json(['message' => 'Patient updated successfully.', 'patient' => $result['patient']], JsonResponse::HTTP_OK);
}
#[Route('/delete/{id}', name: 'delete_patient', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->patientService->deletePatient($id);
            return $this->json(['message' => 'Patient deleted successfully.'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

}
