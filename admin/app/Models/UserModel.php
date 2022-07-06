<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class UserModel extends Model{
    protected $table = 'users';
    
    protected $allowedFields = [
        'name',
        'user_name',       
        'email',
        'password',
        'type',
        'driver_number',
        'created_at'
    ];
}