<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class DefaultController extends AbstractController
{
    private $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @Route("/{lang}", name="index")
     */
    public function index(string $lang): JsonResponse
    {
        
        // Construct file path using the provided language code
        $filePath = $this->getParameter('kernel.project_dir') . "/data/$lang.json";

        // Check if file exists
        if (!$this->fileSystem->exists($filePath)) {
            return new JsonResponse(['error' => 'Language file not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Read file contents
        $data = json_decode(file_get_contents($filePath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format in language file'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Return JSON response
        return new JsonResponse($data);
    }
}
