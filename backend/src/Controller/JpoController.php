<?php
namespace App\Controller;

// On importe notre nouveau JpoManager
use App\Model\JpoManager;

class JpoController {
    
    /**
     * Gère la route : GET /api/jpos
     */
    public static function getAllJpos(): void {
        try {
            $jpoManager = new JpoManager();
            $jpos = $jpoManager->findAllUpcoming();
            
            // On renvoie directement le tableau d'objets, PHP le convertira en JSON.
            echo json_encode($jpos);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching open days.']);
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
}
