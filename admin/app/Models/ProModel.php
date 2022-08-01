<?php 
namespace App\Models;  
use CodeIgniter\Model;
use App\Models\AdvanceModel;
  
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
    
    public function getstatementlist($display_per_page,$user_type,$start_date,$end_date)
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

    $this->where('pro.check_date >=', $start_date);
    $this->where('pro.check_date <=', $end_date);
    $this->orderBy('truck.truck_number',"asc");    
    $this->groupBy(array('pro.driver_id','pro.check_date','pro.truck_id'));

    $result = $this->findAll();
   // $result = $this->paginate($display_per_page );

   $this->db->getLastQuery();
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

        $this->join('vdeadhead', ' vdeadhead.pro_number = pro.pro_number', 'LEFT');
        $this->join('users', ' users.id = pro.driver_id', 'LEFT');    
        $this->join('truck', ' truck.id = pro.truck_id', 'LEFT');
        $this->select('vdeadhead.dh_amount'); 
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

public function gettotalsofStatement($driver_id,$check_date,$truck_id)
{
    $advanceModel = new AdvanceModel();
    $runreport_details = $this->getrunreport($driver_id, $check_date, $truck_id);
     $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();

     $advance_details['driver_advance']=isset($advance_details['driver_advance'])?$advance_details['driver_advance']:'0.00';
$advance_details['misc']=isset($advance_details['misc'])?$advance_details['misc']:'0.00';
$advance_details['advance_repayment']=isset($advance_details['advance_repayment'])?$advance_details['advance_repayment']:'0.00';
$advance_details['occupational_insurance']=isset($advance_details['occupational_insurance'])?$advance_details['occupational_insurance']:'0.00';

 
     foreach($runreport_details as $runreport_detail){
        
        $deadhead= number_format((trim($runreport_detail['dh_amount'])),2,'.','');

        $detention=  number_format(($runreport_detail['detention']*trim($runreport_detail['factorial'])),2,'.','');   
        $layover= number_format(($runreport_detail['layover']*trim($runreport_detail['factorial'])),2,'.',''); 
        $stopoff= number_format(($runreport_detail['stopoff']*trim($runreport_detail['factorial'])),2,'.','');
        $bonus= number_format(($runreport_detail['bonus']*trim($runreport_detail['factorial'])),2,'.','');
        
        $total=
            (trim($runreport_detail['miles'])*trim($runreport_detail['rate'])*trim($runreport_detail['factorial']))
            +trim($detention)
            +trim($stopoff)
            +trim($layover)
            +trim($runreport_detail['handload'])
            +trim($deadhead)
            +trim($bonus);
        $payments_subtotal[]=trim($total);

     }
     $data=array('pro_total'=>array_sum($payments_subtotal),'advance_details'=>$advance_details);
     return $data;
}


    private function getStartAndEndDate($week, $year) {
        $dto = new \DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
      }
    public function getdatelist($driver_id=null)
    {
        $proModel = new ProModel();

         $this->select('max(check_date) as max_date')->select('min(check_date) as min_date');   
         if($driver_id!=null) 
         {
            $this->where('driver_id',$driver_id);
            $this->where('status','Approved'); 
         }     
         $minmax_date = $this->first();


 

        $startTime = strtotime($minmax_date['min_date']);
        $endTime = strtotime($minmax_date['max_date']);
        $today = strtotime('today GMT');

        if($minmax_date['min_date']=="")
        {
            $startTime= strtotime('monday this week'); 
        }


        if($endTime < $today)
        {
           $endTime= strtotime('next monday');
        }

        $weeks = array();

        while ($startTime <= $endTime) {  
            $weeks[date('W', $startTime)] =  $this->getStartAndEndDate(date('W', $startTime),date('Y', $startTime)); 
            $startTime += strtotime('+1 week', 0);
            }
            arsort($weeks);
            return ($weeks);
        }
    

}