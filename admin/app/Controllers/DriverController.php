<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProModel; 
use App\Models\AdvanceModel;
use App\Models\UserModel;




class DriverController extends Controller
{


  public  function statementList()
  {

      $proModel = new ProModel();

      $statement_details = $proModel->getstatementlist(25,session()->get('type'));

  

      $data = [];
      $data['title']         = 'Statement List';

      $data['main_content']    = 'statementlist';
      $data['statement_details']    =  $statement_details;
      $data['pager']    = $proModel->pager ;

      echo view('includes/template', $data);
  }


  public function viewreport($page_num)
  {
    $proModel = new ProModel();
    $session = session();
    $driver_id = $session->get('id');


    $paystatement = $proModel->getpaystatementfordriver($driver_id);



    $data = [];

    $data['page_num'] = $page_num;
    $data['total_record'] = count($paystatement);


    if (count($paystatement) > 0) {




      $check_date = $paystatement[$page_num]['check_date'];
      $truck_id = $paystatement[$page_num]['truck_id'];
      $userModel = new UserModel();

      $user = $userModel->find($driver_id);
      
      $proModel = new ProModel();
     
      $advanceModel = new AdvanceModel();

      $runreport_details = $proModel->getrunreport($driver_id, $check_date, $truck_id);
      

      $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();




      if (empty($runreport_details)) {
        $session->setFlashdata('error', 'Something is missing. Please try again later.');


        return redirect()->back();
      }


      $data['runreport_details']    =  $runreport_details;

      

      $data['advance_details']    =  $advance_details;


      $data['driver_name']=ucwords($user['name']);
      $data['driver_email']= ($user['email']);
      $data['driver_id']    =  $driver_id;
      $data['check_date']    =  $check_date;
      $data['truck_id']    =  $truck_id;
    }


    $data['title']         = 'Pay Statement';

    //$data['main_content']    = 'driverviewreport';
    $data['main_content']    = 'viewreport';

    echo view('includes/template', $data);
  }
 

  public  function sendreport($driver_id, $check_date, $truck_id)
  {

    $userModel = new UserModel();

    $user = $userModel->find($driver_id);




    $email = \Config\Services::email(); // loading for use
    $session = session();
  
    $email->setFrom("noreply@eaglex.llc",'Eagle Expedited');

    $email->setSubject("Eagle Expedited, LLC - Settlement Statement");

    
if($user['email']=="")
{
   
      $session->setFlashdata('error', "Email not configured for this driver.");
      return redirect()->back();
}
    
 
$email->setTo($user['email']);


    $proModel = new ProModel();
    
    $advanceModel = new AdvanceModel();
    $check_date = date('Y-m-d',  $check_date);
    $runreport_details = $proModel->getrunreport($driver_id, $check_date, $truck_id);
   

    $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();



    if (empty($runreport_details)) {
      $session->setFlashdata('error', 'Something is missing. Please try again later.');


      return redirect()->back();
    }

    $data = [];
    $data['title']         = 'Settlement Statement';

    $data['main_content']    = 'emailreport';
    $data['runreport_details']    =  $runreport_details;

   

    $data['advance_details']    =  $advance_details;


   $data['driver_name']=ucwords($user['name']);
   $data['driver_email']= ($user['email']);
    $data['driver_id']    =  $driver_id;
    $data['check_date']    =  $check_date;
    $data['truck_id']    =  $truck_id;

    $template =  view('includes/email', $data);

    $email->setMessage($template);

    // Send email
    if ($email->send()) {
      $session->setFlashdata('success', 'Mail sent successfully.');
      return redirect()->back();
    } else {
      $data = $email->printDebugger(['headers']);
      //  print_r($data);

      $session->setFlashdata('error',  $data);
      return redirect()->back();
    }
  }
}
