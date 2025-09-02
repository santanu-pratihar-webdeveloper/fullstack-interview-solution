<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthUserModel extends Model
{
    protected $table = 'auth_user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'first_name', 'last_name', 'password'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
