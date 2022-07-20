<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\ImportLogModel;
use App\Models\ProModel;
use App\Models\TruckModel; 
use App\Models\AdvanceModel;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
    public function payment()
    {

        helper(['form', 'file']);
        $data = [];
        $data['title']         = 'Payment';

        $data['main_content']    = 'payment';
        echo view('includes/template', $data);
    }

    public function paymentUpload()
    {
        $session = session();
        try {
            $path             = 'public/excel/';
            $json             = [];
            $file_name_main         = $this->request->getFile('upload_file');

            $validated = $this->validate([
                'upload_file' => [
                    'uploaded[upload_file]',
                    'mime_in[upload_file,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]',

                ],
            ]);

            if (!$validated) {
                $session->setFlashdata('error', "Invald File or format.");
                return redirect()->back();
            }






            $file_name         = $this->uploadFile($path, $file_name_main);
            $arr_file         = explode('.', $file_name);
            $extension         = end($arr_file);
            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ('xls' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file_name);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $j = 1;


            $importlog = new ImportLogModel();

            $importlog->insert([
                "file_name" => $file_name,
                "type" => "Pro",

            ]);

            $import_log_id = $importlog->getInsertID();

            $userModel = new UserModel();
            $truckModel = new TruckModel();
            $proModel = new ProModel();

            $errors = array();
            $warings = array();
            foreach ($sheetData as $sheetloop) {

                if ($j > 1) {

                    $truck_id = "";
                    $driver_id = "";
                    $pro_number = trim($sheetloop[0]);
                    $driver_name = trim($sheetloop[1]);
                    $truck = trim($sheetloop[2]);    
                    $rate = trim($sheetloop[3]);
                    $miles = trim($sheetloop[4]);
                    $detention = trim($sheetloop[5]);
                    $stopoff = trim($sheetloop[6]);
                    $layover = trim($sheetloop[7]);
                    $handload = trim($sheetloop[8]);
                    $bonus = trim($sheetloop[9]);
                    $check_date = trim($sheetloop[10]);
                    $email = trim($sheetloop[11]);
                    $factorial = trim($sheetloop[12]);


                    $getuser = $userModel->where('LOWER(name)', strtolower(trim($driver_name)))->first();
                    $gettruck = $truckModel->where('truck_number', trim($truck))->first();

                    if (empty($getuser)) {
                        $user_name = $this->toUserName($driver_name);
                        if ($driver_name != "") {
                            $userModel->insert([
                                "name" =>  $driver_name,
                                "user_name" => $user_name,
                                "email" => $email,
                                "password" => md5('d@123'),
                                "type" => 'Driver',
                                'driver_number' => ''

                            ]);

                            $driver_id = $userModel->getInsertID();


                            // Updated data 
                            $driver_id_unique = 100000000;
                            $userModel->set([
                                "driver_number" => ($driver_id_unique + $driver_id),
                               // "password" => md5($user_name . $driver_id)
                               
                            ]);
                            // where condition
                            $userModel->where([
                                "id" => $driver_id
                            ]);
                            // Calling update() method
                            $userModel->update();
                        }
                    } else {
                        $driver_id = $getuser['id'];
                    }

                    if (empty($gettruck)) {
                        if ($truck != "") {
                            $truckModel->insert([
                                "truck_number" =>  $truck,


                            ]);

                            $truck_id = $truckModel->getInsertID();
                        }
                    } else {
                        $truck_id = $gettruck['id'];
                    }




                    if ($driver_id == "") {
                        $errors[$j]['Driver Name'] = "Driver Name";
                    }
                    if ($truck_id == "") {
                        $errors[$j]['Truck Number'] = "Truck Number";
                    }
                    if ($pro_number == "") {
                        $errors[$j]['Pro Number'] = "Pro Number";
                    }
                    if ($check_date == "") {
                        $errors[$j]['Check Date'] = 'Check Date';
                    }

                    if ($rate == "") {
                        $warings[$j]['Rate'] = 'Rate';
                    }

                    if ($miles == "") {
                        $warings[$j]['Miles'] = 'Miles';
                    }
                    if ($detention == "") {
                        $warings[$j]['Detention'] = 'Detention';
                    }
                    if ($stopoff == "") {
                        $warings[$j]['Stopoff'] = 'Stopoff';
                    }
                    if ($layover == "") {
                        $warings[$j]['Layover'] = 'Layover';
                    }
                    if ($handload == "") {
                        $warings[$j]['Handload'] = 'Handload';
                    }
                    if ($bonus == "") {
                        $warings[$j]['Bonus'] = 'Bonus';
                    }
                    if ($factorial == "") {
                        $warings[$j]['Factorial'] = 'Factorial';
                    }




                    if (!isset($errors[$j])) {
                        $prodata = [
                            "import_log_id" =>  $import_log_id,
                            "driver_id" =>  $driver_id,
                            "truck_id" =>  $truck_id,
                            "pro_number" =>  $pro_number,
                            "check_date" =>  date('Y-m-d', strtotime($check_date)),
                            "rate" =>  $rate,
                            "miles" =>  $miles,
                            "factorial"=>  $factorial,
                            "detention" =>  $detention,
                            "stopoff" =>  $stopoff,
                            "layover" =>  $layover,
                            "handload" =>  $handload,
                            "bonus" =>  $bonus,
                            "status" => "Archived"

                        ];

                        $proModel->insert($prodata);
                    }
                }

                $j++;
            }

            $session->setFlashdata('success', 'Panther data uploaded successfully.');
            $session->setFlashdata('errors', $errors);
            $session->setFlashdata('warings', $warings);
            return redirect()->back();
        } catch (Exception $e) {


            $session->setFlashdata('error', $e->getMessage());
            return redirect()->back();
        }
    }


 



    public function uploadFile($path, $image)
    {
        if (!is_dir($path))
            mkdir($path, 0777, TRUE);
        if ($image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('./' . $path, $newName);
            return $path . $image->getName();
        }
        return "";
    }

    function toUserName($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '_', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '_'));
    }






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


    public  function runreport($driver_id, $check_date, $truck_id)
    {
        $session = session();
        $proModel = new ProModel();
         
        $advanceModel = new AdvanceModel();
        $userModel = new UserModel();

        $user = $userModel->find($driver_id);
        $check_date = date('Y-m-d',  $check_date);
        $runreport_details = $proModel->getrunreport($driver_id, $check_date, $truck_id);
         

        $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();



        if (empty($runreport_details)) {
            $session->setFlashdata('error', 'Error in runreport() around line 307. Tell IT dept.');


            return redirect()->route('admin/statement-list');
        }

        $data = [];
        $data['title']         = 'Settlement Statement';

        $data['main_content']    = 'runreport';
        $data['runreport_details']    =  $runreport_details;

         

        $data['advance_details']    =  $advance_details;

        $data['driver_name']=ucwords($user['name']);
        $data['driver_email']=($user['email']);

        $data['driver_id']    =  $driver_id;
        $data['check_date']    =  $check_date;
        $data['truck_id']    =  $truck_id;

        echo view('includes/template', $data);
    }

    public  function   runreportupdate()
    {
        $session = session();
        try {

            $advanceModel = new AdvanceModel();
           
            $proModel = new ProModel();



            $driver_id =  $this->request->getPost('driver_id');
            $check_date =  $this->request->getPost('check_date');
            $truck_id =  $this->request->getPost('truck_id');
            $driver_advance =  $this->request->getPost('driver_advance');
            $misc =  $this->request->getPost('misc');
            $advance_repayment =  $this->request->getPost('advance_repayment');
            $occupational_insurance =  $this->request->getPost('occupational_insurance');            
            $advance_comment =  $this->request->getPost('advance_comment');
            $total_comment =  $this->request->getPost('total_comment');
            
            $payment =  $this->request->getPost('payment');

            $recalculate =  $this->request->getPost('recalculate');
            $recalculate_approved =  $this->request->getPost('recalculate_approved');





            $getadvance = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();



            if (empty($getadvance)) {


                $advanceModel->insert([
                    "driver_advance" =>  trim($driver_advance),
                    "misc" => trim($misc),
                    "advance_repayment" => trim($advance_repayment),
                    "occupational_insurance" => trim($occupational_insurance),
                    "advance_comment" => trim($advance_comment),
                    "total_comment" => trim($total_comment),
                    "driver_id" => trim($driver_id),
                    "check_date" => trim($check_date),
                    "truck_id" => trim($truck_id),


                ]);
            } else {
                $advanceModel->set([
                    "driver_advance" =>  trim($driver_advance),
                    "misc" => trim($misc),
                    "advance_comment" => trim($advance_comment),
                    "total_comment" => trim($total_comment),
                    "advance_repayment" => trim($advance_repayment),
                    "occupational_insurance" => trim($occupational_insurance),

                ]);
                // where condition
                $advanceModel->where([
                    "id" => $getadvance['id']
                ]);
                // Calling update() method
                $advanceModel->update();
            }


            foreach ($payment as $pro_id => $paymentloop) {


                $payemntdata = [
                    "pro_number" =>  trim($paymentloop['pro_number']),
                    "rate" =>  trim($paymentloop['rate']),
                    "factorial" =>  trim($paymentloop['factorial']),                    
                    "miles" =>  trim($paymentloop['miles']),
                    "detention" =>  trim($paymentloop['detention']),
                    "layover" =>  trim($paymentloop['layover']),
                    "stopoff" =>  trim($paymentloop['stopoff']),
                    "handload" =>  trim($paymentloop['handload']),
                    "deadhead" =>  trim($paymentloop['deadhead']),
                    "bonus" =>  trim($paymentloop['bonus']),



                ];

                if (isset($recalculate_approved)) {
                    $payemntdata['status'] = 'Approved';
                }


                $proModel->set($payemntdata);



                $proModel->where([
                    "id" => $pro_id
                ]);

                $proModel->update();
            }



            if (isset($recalculate_approved)) {

                $userModel = new UserModel();
                $user = $userModel->find($driver_id);
                $runreport_details = $proModel->getrunreport($driver_id, $check_date, $truck_id);
                $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();
                $email = \Config\Services::email(); // loading for use
                $session = session();
                $email->setFrom("noreply@eaglex.llc",'Eagle Expedited');
                $email->setSubject("Eagle Expedited, LLC - Settlement Statement");
                $email->setTo($user['email']);
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
                $email->send();
            }
             
            if (isset($recalculate_approved)) {

                $session->setFlashdata('success', 'Settlement Statement Recalculated & Approved.');
            } else {

                $session->setFlashdata('success', 'Settlement Statement Updated Successfully.');
            }

            return redirect()->back();
        } catch (Exception $e) {


            $session->setFlashdata('error', $e->getMessage());
            return redirect()->back();
        }
    }

  public function viewreport($driver_id, $check_date, $truck_id)
  {
    $proModel = new ProModel();
     
    $advanceModel = new AdvanceModel();
    $userModel = new UserModel();
    $session = session();

    $user = $userModel->find($driver_id);
    $check_date = date('Y-m-d',  $check_date);
    $runreport_details = $proModel->getrunreport($driver_id, $check_date, $truck_id);
    

    $advance_details = $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->first();



    if (empty($runreport_details)) {
        $session->setFlashdata('error', 'Error in AdminController around Line 473. Tell IT dept.');


        return redirect()->back();
    }

    $data = [];
    $data['title']         = 'Pay Statement';

    $data['main_content']    = 'viewreport';
    $data['runreport_details']    =  $runreport_details;

    

    $data['advance_details']    =  $advance_details;


    $data['driver_name']=ucwords($user['name']);
    $data['driver_email']=($user['email']);
    $data['driver_id']    =  $driver_id;
    $data['check_date']    =  $check_date;
    $data['truck_id']    =  $truck_id;

    echo view('includes/template', $data);

  }  

  public function prodelete($pro_id)
  {
    $session = session();
 
        $proModel = new ProModel();

        $proModel->where('id', $pro_id)->delete();
    $session->setFlashdata('error', 'Payment deleted successfully.');


    return redirect()->back();
  }

public function deleterunreport($driver_id, $check_date, $truck_id)
{
    $session = session();
 
    $proModel = new ProModel();
    $advanceModel = new AdvanceModel();
    $check_date = date('Y-m-d',  $check_date);
    $proModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->delete();
  
    $advanceModel->where('driver_id', $driver_id)->where('check_date', $check_date)->where('truck_id', $truck_id)->delete();

$session->setFlashdata('error', 'Statement deleted successfully.');


return redirect()->back();
}


}
