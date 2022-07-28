<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ImportLogModel extends Model{
    protected $table = 'import_log';
    
    protected $allowedFields = [
        'file_name',
        'type',       
        'created_at'
    ];
}