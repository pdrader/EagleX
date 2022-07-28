<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TruckModel extends Model{
    protected $table = 'truck';
    
    protected $allowedFields = [
        'truck_number',        
    ];
}