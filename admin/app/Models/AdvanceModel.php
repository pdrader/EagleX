<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class AdvanceModel extends Model{
    protected $table = 'advance';
    
    protected $allowedFields = [
        'driver_advance',
        'misc',       
        'advance_repayment',
        'occupational_insurance',
        'advance_comment',
        'total_comment',
        'driver_id',
        'check_date',
        'truck_id',         
        'created_at',
    ];
    
  



}