<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;
use App\Models\Jpo;

class JpoManager
{
    public static function getAllJpos(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM open_days");
            return $stmt->fetchAll(PDO::FETCH_CLASS, Jpo::class);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function getJpoById(int $id): ?Jpo
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM open_days WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchObject(Jpo::class);
            return $result ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function createJpo(Jpo $jpo): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO open_days (location_id, title, description, event_date, capacity, status, created_at) VALUES (:location_id, :title, :description, :event_date, :capacity, :status, NOW())");
            return $stmt->execute([
                ':location_id' => $jpo->getLocationId(),
                ':title' => $jpo->getTitle(),
                ':description' => $jpo->getDescription(),
                ':event_date' => $jpo->getEventDate(),
                ':capacity' => $jpo->getCapacity(),
                ':status' => $jpo->getStatus()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function updateJpo(\App\Models\Jpo $jpo): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE open_days SET location_id = :location_id, title = :title, description = :description, event_date = :event_date, capacity = :capacity, status = :status WHERE id = :id");
            return $stmt->execute([
                ':id' => $jpo->id,
                ':location_id' => $jpo->location_id,
                ':title' => $jpo->title,
                ':description' => $jpo->description,
                ':event_date' => $jpo->event_date,
                ':capacity' => $jpo->capacity,
                ':status' => $jpo->status
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function deleteJpo(int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM open_days WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function findAllUpcoming(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM open_days WHERE event_date >= CURDATE() ORDER BY event_date ASC");
        $jpos = $stmt->fetchAll(PDO::FETCH_CLASS, Jpo::class);

        // Reformate la date pour chaque JPO
        foreach ($jpos as $jpo) {
            if (isset($jpo->event_date)) {
                $jpo->event_date = substr($jpo->event_date, 0, 10); // Garde YYYY-MM-DD
            }
        }

        return $jpos;
    }

    public static function findById(int $id): ?Jpo
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM open_days WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchObject(Jpo::class);
        return $result ?: null;
    }
}
