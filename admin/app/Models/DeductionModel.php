<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class DeductionModel extends Model{
    protected $table = 'deduction';
    
    protected $allowedFields = [
        'import_log_id',
        'deduction_date',
        'driver_id',   
        'truck_id',   
        'description',   
        'deduction_amount',
        'deduction_total',           
    ];

public function getdeduction($driver_id,$check_date,$truck_id)
{
 
  
$this->select('*');
$this->orderBy('id');
$this->where('driver_id',$driver_id);
$this->where('deduction_date',$check_date);
$this->where('truck_id',$truck_id);
// $this->groupBy(array('pro.driver_id','pro.check_date','pro.truck_id'));
$result = $this->findAll();

//echo $this->db->getLastQuery();
////exit;

return $result;
}

}