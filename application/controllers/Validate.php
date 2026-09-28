<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validate extends CI_Controller {
    function __construct(){
	    parent::__construct();
	    $this->load->database();
        $this->load->library('session');
	    //$this->load->model('Front_model');
	    //$this->load->helper('fee_helper','form','url','common');
	    // $this->load->library('form_validation','upload','session');
	    date_default_timezone_set('Asia/Kolkata');
	    $this->load->model('VC_model');
        // $this->load->helper('time');
	}

	public function index(){
        $username = $this->input->post("username");
        $password = $this->input->post('password'); 
            // $password = md5($this->input->post('password')); 

        $login_status = $this->validate_login($username,$password);

        if ($login_status == "success") {
                
                date_default_timezone_set("Asia/Kolkata");
                $d = date("Y/m/d h:i:s");
                $data = array(
                    'last_login' => $d
                );
                $this->db->where('id', $this->session->userdata('id'));
                $this->db->update('users', $data);

                $currentDate = new DateTime();
                $curd = $currentDate->format('Y-m-d');
                redirect(base_url() . 'dashboard?agent=&sd=' . $curd . '&ed=' . $curd);
        } else {
            $this->session->set_flashdata("error","Please enter a valid Username & Password.");
            // $msg = "Please enter a valid Username & Password.";
            // redirect(base_url() . '?msg=' . $msg);
            redirect(base_url());
        }
    }

    // check valid login
    function validate_login($username = "", $password = ""){
        $credential = ["username" =>$username, "password" =>$password];

        $query = $this->db->get_where("users", $credential);
        
        $row = $query->row();
        
        if (!empty($row)){
            $this->session->set_userdata("id", $row->id);
            $this->session->set_userdata("name", $row->name);
            $this->session->set_userdata("gender", $row->gender);
            $this->session->set_userdata("username", $row->username);
            $this->session->set_userdata("team", $row->team);
            $this->session->set_userdata("email", $row->email);
            $this->session->set_userdata("mobile", $row->mobile);
            $this->session->set_userdata("designation", $row->designation);
            $this->session->set_userdata("lastlogin", $row->last_login);
            $this->session->set_userdata("user_role", $row->user_role);
            $this->session->set_userdata("login_status", "1");
            
            return "success";
        }
        else{
            return "invalid";
        }
    }

    // logout function
    function logout() {
        $this->session->unset_userdata('');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out successfully..!');
        redirect(base_url() , 'refresh');
    }
}