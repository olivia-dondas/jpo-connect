<?php
namespace App\Models;

class User {
    public int $id;
    public ?string $first_name;
    public ?string $last_name;
    public string $email;
    public string $password_hash;
    public ?string $phone_number;
    public string $role;
    public ?string $google_id;
    public ?string $linkedin_id;
    public string $created_at;
}
