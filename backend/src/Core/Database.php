<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe de connexion à la base de données utilisant le design pattern Singleton.
 * Cela garantit qu'une seule et unique instance de la connexion PDO est utilisée
 * pour toute la durée de la requête, ce qui est plus efficace.
 */
class Database {
    /**
     * @var PDO|null L'unique instance de notre connexion PDO.
     */
    private static ?PDO $instance = null;

    /**
     * Le constructeur est privé pour empêcher l'instanciation directe
     * avec "new Database()".
     */
    private function __construct() {}

    /**
     * Empêche le clonage de l'instance pour préserver le Singleton.
     */
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
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                
                // On crée l'instance de PDO
                self::$instance = new PDO($dsn, DB_USER, DB_PASS);

                // On configure les attributs de PDO pour un fonctionnement optimal et sécurisé
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Lève des exceptions en cas d'erreur SQL
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Récupère les résultats en tableaux associatifs par défaut
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Utilise de vraies requêtes préparées pour plus de sécurité

            } catch (PDOException $e) {
                // En cas d'échec de la connexion, on arrête l'application et on affiche une erreur générique.
                // En mode développement, on pourrait afficher $e->getMessage(), mais jamais en production.
                http_response_code(500);
                echo json_encode(['error' => 'Database connection failed.']);
                exit();
            }
        }
        
        // On retourne l'instance (qu'elle vienne d'être créée ou qu'elle existait déjà)
        return self::$instance;
    }
}
