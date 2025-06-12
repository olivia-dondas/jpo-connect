<?php
namespace App\Models;

class Jpo {
    public int $id;
    public int $location_id;
    public string $title;
    public ?string $description; 
    public string $event_date;
    public int $capacity;
    public string $status;
    public string $created_at;

   //les getters et setters
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function getLocationId(): int {
        return $this->location_id;
    }
    public function setLocationId(int $location_id): void {
        $this->location_id = $location_id;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function setDescription(?string $description): void {
        $this->description = $description;
    }
    public function getEventDate(): string {
        return $this->event_date;
    }

    public function setEventDate(string $event_date): void {
        $this->event_date = $event_date;
    }
    public function getCapacity(): int {
        return $this->capacity;
    }
    public function setCapacity(int $capacity): void {
        $this->capacity = $capacity;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function setStatus(string $status): void {
        $this->status = $status;
    }
    public function getCreatedAt(): string {
        return $this->created_at;
    }
    public function setCreatedAt(string $created_at): void {
        $this->created_at = $created_at;
    }
    
    
    
}
