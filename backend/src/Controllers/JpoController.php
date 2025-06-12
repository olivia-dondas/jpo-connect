<?php
namespace App\Controllers;

// On importe notre nouveau JpoManager
use App\Models\JpoManager;

class JpoController {
    
    /**
     * Gère la route : GET /api/jpos
     */
    public static function getAllJpos(): void {
        try {
            $jpoManager = new JpoManager();
            $jpos = $jpoManager->findAllUpcoming();
            echo json_encode($jpos);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]); // <-- Affiche le vrai message d'erreur
        }
    }
    
    /**
     * Gère la route : GET /api/jpos/{id}
     */
    public static function getJpoById(string $id): void {
        try {
            $jpoManager = new JpoManager();
            $jpo = $jpoManager->findById((int)$id); // On convertit l'ID en entier
            
            if ($jpo) {
                echo json_encode($jpo);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'JPO not found']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching the open day.']);
        }
    }
    
    /**
     * Gère la route : POST /api/jpos
     */
    public static function createJpo(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }
        
        // Validation des données
        if (
            empty($data['location_id']) ||
            empty($data['title']) ||
            empty($data['event_date']) ||
            empty($data['capacity']) ||
            empty($data['status'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $jpo = new \App\Models\Jpo();
        $jpo->location_id = $data['location_id'];
        $jpo->title = $data['title'];
        $jpo->description = $data['description'];
        $jpo->event_date = $data['event_date'];
        $jpo->capacity = $data['capacity'];
        $jpo->status = $data['status'];
        $result = \App\Models\JpoManager::createJpo($jpo);
        echo json_encode(['success' => $result]);
    }
    
    /**
     * Gère la route : DELETE /api/jpos/{id}
     */
    public static function deleteJpo($id): void {
        $result = \App\Models\JpoManager::deleteJpo((int)$id);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'JPO not found or could not be deleted']);
        }
    }
    
    /**
     * Gère la route : PUT /api/jpos/{id}
     */
    public static function updateJpo($id): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }
        
        // Validation des données
        if (
            empty($data['location_id']) ||
            empty($data['title']) ||
            empty($data['event_date']) ||
            empty($data['capacity']) ||
            empty($data['status'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $jpo = new \App\Models\Jpo();
        $jpo->id = (int)$id;
        $jpo->location_id = $data['location_id'];
        $jpo->title = $data['title'];
        $jpo->description = $data['description'];
        $jpo->event_date = $data['event_date'];
        $jpo->capacity = $data['capacity'];
        $jpo->status = $data['status'];
        $result = \App\Models\JpoManager::updateJpo($jpo);
        echo json_encode(['success' => $result]);
    }
}
