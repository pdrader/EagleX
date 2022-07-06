<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ProModel extends Model{
    protected $table = 'pro';
    
    protected $allowedFields = [
        'import_log_id',
        'driver_id',       
        'truck_id',
        'pro_number',
        'check_date',
        'rate',
        'miles',
        'factorial',
        'detention',
        'stopoff',
        'layover',
        'handload',
        'deadhead',
        'bonus',
        'status',
        'created_at',
    ];
    
    public function getstatementlist($display_per_page,$user_type)
    {
        $this->join('users', ' users.id = pro.driver_id', 'LEFT');    
        $this->join('truck', ' truck.id = pro.truck_id', 'LEFT');
    $this->select('users.name');   
    $this->select('truck.truck_number');   
    $this->select('pro.*');
    if($user_type=='Driver')
    {
        $this->where('pro.driver_id',session()->get('id'));
        $this->where('pro.status','Approved');
    }
    $this->orderBy('pro.check_date',"desc");    
    $this->groupBy(array('pro.driver_id','pro.check_date','pro.truck_id'));
   // $result = $this->findAll();
    $result = $this->paginate($display_per_page );

 //echo $this->db->getLastQuery();
 //exit;

    return $result;
    }
    public function getpaystatementfordriver($driver_id)
    {
     
    $this->select('pro.*');
    $this->orderBy('pro.check_date',"desc");
    $this->where('pro.driver_id',$driver_id);    
    $this->where('pro.status','Approved'); 
    $this->groupBy(array('pro.driver_id','pro.check_date','pro.truck_id'));
    $result = $this->findAll();

 //echo $this->db->getLastQuery();
 //exit;

    return $result;
    }

    public function getrunreport($driver_id,$check_date,$truck_id)
    {

        
        $this->join('users', ' users.id = pro.driver_id', 'LEFT');    
        $this->join('truck', ' truck.id = pro.truck_id', 'LEFT');
    $this->select('users.name'); 
    $this->select('users.driver_number');  
    $this->select('truck.truck_number');   
    $this->select('pro.*');
    $this->orderBy('pro.id');
    $this->where('pro.driver_id',$driver_id);
    $this->where('pro.check_date',$check_date);
    $this->where('pro.truck_id',$truck_id);
   // $this->groupBy(array('pro.driver_id','pro.check_date','pro.truck_id'));
    $result = $this->findAll();

 //echo $this->db->getLastQuery();
 //exit;

    return $result;
    }


}