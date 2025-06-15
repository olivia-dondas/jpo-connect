<?php
namespace App\Core;

use PDO;
use PDOException;


class Database {
  
    private static ?PDO $instance = null;

    private function __construct() {}

    
    private function __clone() {}

    /**
     * Méthode statique qui crée l'instance de la connexion si elle n'existe pas,
     * ou la retourne si elle existe déjà.
     *
     * @return PDO L'instance de la connexion PDO.
     */
    public static function getConnection(): PDO {
        // Si l'instance n'a pas encore été créée...
        if (self::$instance === null) {
            // ... on charge la configuration de la base de données.
            require_once __DIR__ . '/../../config/database.php';

            try {
                // On crée le DSN (Data Source Name)
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                
                // On crée l'instance de PDO
                self::$instance = new PDO($dsn, DB_USER, DB_PASS);

                // On configure les attributs de PDO pour un fonctionnement optimal et sécurisé
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Lève des exceptions en cas d'erreur SQL
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Récupère les résultats en tableaux associatifs par défaut
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Utilise de vraies requêtes préparées pour plus de sécurité

            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
                exit();
            }
        }
        
        // On retourne l'instance (qu'elle vienne d'être créée ou qu'elle existait déjà)
        return self::$instance;
    }

    public static function getInstance(): \PDO {
        static $pdo = null;
        if ($pdo === null) {
            $pdo = new \PDO('mysql:host=localhost;dbname=jpo-connect;charset=utf8', 'root', 'root');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $pdo;
    }
}
