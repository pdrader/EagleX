<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
  
class SigninController extends Controller
{
    public function index()
    {
        $session = session();
        $isLoggedIn = $session->get('isLoggedIn');
        if( $isLoggedIn)
        {

            if($session->get('type')=='Admin'){
                return redirect()->to('/admin/statement-list');
            }
            else
            {
                return redirect()->to('driver/statement-list');  
            }
           



          




        }

        helper(['form']);
        echo view('signin');
    } 
  
    public function loginAuth()
    {
        
        $session = session();
        $userModel = new UserModel();
        $user_name = $this->request->getVar('user_name');
        $password = $this->request->getVar('password');
        if($user_name=="" || $password=="")
        {
            $session->setFlashdata('error', 'Please enter valid username or password.');
            return redirect()->to('/signin');  
        }



        $data = $userModel->where('user_name', $user_name)->where('password', md5($password))->first();

       
       
        if($data){

             
          
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'user_name' => $data['user_name'],
                    'email' => $data['email'],
                    'type' => $data['type'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                if($session->get('type')=='Admin'){
                    return redirect()->to('/admin/statement-list');
                }
                else
                {
                    return redirect()->to('driver/statement-list');  
                }
                
            
             
        }else{
            $session->setFlashdata('error', 'Invalid username or password.');
            return redirect()->to('/signin');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/signin');
    }
}