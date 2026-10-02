<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VCall extends CI_Controller {
    function __construct(){
	    parent::__construct();
	    $this->load->database();
        $this->db2 = $this->load->database('sr_trak', TRUE);
        $this->load->library('session');
        $this->load->model('Report_model');
	    //$this->load->model('Front_model');
	    //$this->load->helper('fee_helper','form','url','common');
	    // $this->load->library('form_validation','upload','session');
	    // date_default_timezone_set('Asia/Kolkata');
	    $this->load->model('VC_model');
        $this->load->helper('form');
        // $this->load->helper('time');
	}

	public function index()
	{
		// SEO Variables
        $data['title'] = 'Sign In | VCall - Admin & Dashboard';
        $data['description'] = 'Sing In | VCall - Admin & Dashboard';
        
        $this->load->view('common/head', $data);
        $this->load->view('login');
	}
    
    public function dashboard1()
	{
              // SEO Variables
        $data['title'] = 'Dashboard | VCall - Admin & Dashboard';
        $data['description'] = 'Dashboard | VCall - Admin & Dashboard';
        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));

        // Database Variables
        $data['calls'] = $this->VC_model->get_all_calls($user, $sd, $ed);
        $data['in_calls'] = $this->VC_model->get_in_calls($user, $sd, $ed);
        $data['out_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        $data['unq_calls'] = $this->VC_model->get_unq_calls($user, $sd, $ed);
        // $data['cnctd_calls'] = $this->VC_model->get_cnctd_calls($user, $sd, $ed);
        $data['rjctd_calls'] = $this->VC_model->get_rjctd_calls($user, $sd, $ed);
        $data['msd_calls'] = $this->VC_model->get_msd_calls($user, $sd, $ed);
        $data['nvratnd_calls'] = $this->VC_model->get_nvr_atnd_calls($user, $sd, $ed);
        $data['ntpkclnt_calls'] = $this->VC_model->get_not_picked_client_calls($user, $sd, $ed);
        $data['out_cnctd_calls'] = $this->VC_model->get_out_cnctd_calls($user, $sd, $ed);
        $data['in_cnctd_calls'] = $this->VC_model->get_in_cnctd_calls($user, $sd, $ed);
        // $data['duration'] = $this->VC_model->get_calls_duration($user, $sd, $ed);
        $data['out_duration'] = $this->VC_model->get_outcalls_duration($user, $sd, $ed);
        $data['in_duration'] = $this->VC_model->get_incalls_duration($user, $sd, $ed); 
        $data['agt'] = $this->VC_model->agent();       

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('dashboard', $data);
        $this->load->view('common/footer');
	}

    public function dashboard()
	{
        // echo "testing";
        // exit;
        // SEO Variables
        $data['title'] = 'Dashboard | VCall - Admin & Dashboard';
        $data['description'] = 'Dashboard | VCall - Admin & Dashboard';
        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));

		$startSecond = htmlspecialchars($_GET['ss']);
		$endSecond = htmlspecialchars($_GET['es']);

        $tid = $this->session->userdata("team");

        // Database Variables
        $dashboard = $this->VC_model->dashboard_data($user, $sd, $ed, $tid,$startSecond,$endSecond);
        $data['dsh'] = $this->VC_model->dashboard_data($user, $sd, $ed, $tid,$startSecond,$endSecond);
        $data['agt'] = $this->VC_model->agent($tid);
        
        $data['pie_series'] = json_encode([
            (int)$dashboard['total_outbound_calls'],
            (int)$dashboard['total_inbound_calls'],
            (int)$dashboard['total_connected_calls'],
            (int)$dashboard['total_unique_calls'],
            (int)$dashboard['total_rejected_calls'],
            (int)$dashboard['total_missed_calls'],
            (int)$dashboard['total_not_picked_client_calls']
        ]);
        
        $data['pie_labels'] = json_encode([
            'Outgoing Calls',
            'Incoming Calls',
            'Connected Calls',
            'Unique Calls',
            'Rejected Calls',
            'Missed Calls',
            'Not Picked by Client'
        ]);
        // print_r($data['dsh']);exit();
        // $this->db->last_query();exit;
		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('dashboard1', $data);
        $this->load->view('common/footer');
	}
    
    public function analytics()
	{
		$this->load->view('common/head');
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('analytics');
        $this->load->view('common/footer');
	}

    public function tcalls()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  
        
        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $uri[2] . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['call_details'] = $this->VC_model->get_all_calls_details($user, $sd, $ed, $tid);
        $data['calls'] = $this->VC_model->get_all_calls($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();
//print_r($data['call_details']); exit;
	$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
	}

    public function outbound()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['out_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed, $tid);
        $data['out_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
	}
    
    public function inbound()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['in_details'] = $this->VC_model->get_in_calls_details($user, $sd, $ed, $tid);
        $data['in_calls'] = $this->VC_model->get_in_calls($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
	}
	
    public function outbound_auto()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['in_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed);
        $data['in_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
	}
	
    public function outbound_manual()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['out_man_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed);
        $data['out_man_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
	}
	
    public function unique()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['unq_calls'] = $this->VC_model->get_unq_calls($user, $sd, $ed, $tid);
        $data['unq_calls_details'] = $this->VC_model->get_unq_calls_details($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ucalls', $data);
        $this->load->view('common/footer');
	}

    public function con_calls()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['cnctd_calls'] = $this->VC_model->get_cnctd_calls($user, $sd, $ed, $tid);
        $data['cnctd_calls_details'] = $this->VC_model->get_cnctd_calls_details($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ocalls', $data);
        $this->load->view('common/footer');
	}

    public function rej_calls()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['rjctd_calls'] = $this->VC_model->get_rjctd_calls($user, $sd, $ed, $tid);
        $data['rjctd_calls_details'] = $this->VC_model->get_rjctd_calls_details($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ocalls', $data);
        $this->load->view('common/footer');
	}

    public function msd_calls()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['msd_calls'] = $this->VC_model->get_msd_calls($user, $sd, $ed, $tid);
        $data['msd_calls_details'] = $this->VC_model->get_msd_calls_details($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ocalls', $data);
        $this->load->view('common/footer');
	}

    public function not_pick_calls()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));
        $tid = $this->session->userdata("team");

        $user = htmlspecialchars($_GET['agent']);
        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));
        $data['agt'] = $this->VC_model->agent($tid);  

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['not_pick_client_calls'] = $this->VC_model->get_not_picked_client_calls($user, $sd, $ed, $tid);
        $data['not_pick_client_calls_details'] = $this->VC_model->get_not_picked_client_calls_details($user, $sd, $ed, $tid);
        // $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ocalls', $data);
        $this->load->view('common/footer');
	}

    public function aprod()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));

        $sd = date('Y-m-d', strtotime(htmlspecialchars($_GET['sd'])));
        $ed = date('Y-m-d', strtotime(htmlspecialchars($_GET['ed'])));

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['teams'] = $this->VC_model->team();
        
        $data['team_details'] = $this->VC_model->team_details();

        
        $data['homeVisit1'] = $this->VC_model->GetHomeVisitTotal($sd,$ed);
        $data['homeVisit'] = array_column($data['homeVisit1'], 'total_count', 'name');
        
         /***********Back Office Data Start***********/  
         $data['bookingFileCV'] = $this->VC_model->highrise_app_back_office_productivity_table_Count($sd,$ed);

         /***********All Calling Data Start***********/    
         $report = $this->Report_model->total_call_optimised($sd, $ed);

        //  echo "<pre>";
        //  print_r($report);exit;

         $data['dialedCall'] = array_column($report, 'total_calls', 'sender_phone_number');
         $data['ConCall'] = array_column($report, 'total_connected_calls', 'sender_phone_number');
         $data['total_unique_calls'] = array_column($report, 'total_unique_calls', 'sender_phone_number');
          /***********All Calling Data End***********/    
        
        
          $data['bookingDataCount'] = $this->VC_model->bookingDataCount($sd,$ed);
        //    echo "<pre>";
        //      print_r($data['bookingDataCount']);exit;
          $data['all_bookingDataCount'] = array_column($data['bookingDataCount'], 'total_bookings', 'sales_person');


          $data['total_visit_count'] = $this->VC_model->total_visit_count($sd,$ed);
          $data['all_total_visit_count'] = array_column($data['total_visit_count'], 'total_visits', 'sales_name');
               


        /************getVisitCountByDate************/  
        
        $data['getVisitCountByDate'] = $this->VC_model->getVisitCountcorpByDate($sd,$ed);
        $data['all_total_visit_corp_count'] = array_column($data['getVisitCountByDate'], 'total_corp_visits', 'person_name');

              
        
		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('aproductivity', $data);
        $this->load->view('common/footer');
	}

    // Masters Pages
    public function team()
	{
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['teams'] = $this->VC_model->team();
        $data['team_details'] = $this->VC_model->team_details();
        
        
       

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('teams', $data);
        $this->load->view('common/footer');
	}

    public function teamsList(){

	    $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', $uri[2]));

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

	    $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('teamsList', $data);
        $this->load->view('common/footer');

     }





/***End****/

}
