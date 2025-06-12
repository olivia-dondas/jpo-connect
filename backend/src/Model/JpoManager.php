<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class JpoManager {
    private PDO $pdo;

    public function __construct() {
        // On récupère la connexion PDO à la création de l'objet
        $this->pdo = Database::getConnection();
    }

   
    public function findAllUpcoming(): array {
        $stmt = $this->pdo->query("SELECT * FROM open_days WHERE status = 'upcoming' ORDER BY event_date ASC");
        
        // On configure PDO pour qu'il crée directement des objets de notre classe Jpo
        $stmt->setFetchMode(PDO::FETCH_CLASS, Jpo::class);
        
        return $stmt->fetchAll();
    }

    public function findById(int $id): Jpo|false {
        $stmt = $this->pdo->prepare("SELECT * FROM open_days WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Jpo::class);

        return $stmt->fetch();
    }
}
