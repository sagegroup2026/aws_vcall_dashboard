<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VCall extends CI_Controller {
    function __construct(){
	    parent::__construct();
	    $this->load->database();
        $this->load->library('session');
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
    
    public function dashboard()
	{
        // SEO Variables
        $data['title'] = 'Dashboard | VCall - Admin & Dashboard';
        $data['description'] = 'Dashboard | VCall - Admin & Dashboard';
        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);
        
        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $uri[2] . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['call_details'] = $this->VC_model->get_all_calls_details($user, $sd, $ed);
        $data['calls'] = $this->VC_model->get_all_calls($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['out_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed);
        $data['out_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['in_details'] = $this->VC_model->get_in_calls_details($user, $sd, $ed);
        $data['in_calls'] = $this->VC_model->get_in_calls($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['in_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed);
        $data['in_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';

        // Database Variables
        $data['out_man_details'] = $this->VC_model->get_out_calls_details($user, $sd, $ed);
        $data['out_man_calls'] = $this->VC_model->get_out_calls($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['unq_calls'] = $this->VC_model->get_unq_calls($user, $sd, $ed);
        $data['unq_calls_details'] = $this->VC_model->get_unq_calls_details($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['cnctd_calls'] = $this->VC_model->get_cnctd_calls($user, $sd, $ed);
        $data['cnctd_calls_details'] = $this->VC_model->get_cnctd_calls_details($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['rjctd_calls'] = $this->VC_model->get_rjctd_calls($user, $sd, $ed);
        $data['rjctd_calls_details'] = $this->VC_model->get_rjctd_calls_details($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['msd_calls'] = $this->VC_model->get_msd_calls($user, $sd, $ed);
        $data['msd_calls_details'] = $this->VC_model->get_msd_calls_details($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

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

        $user = htmlspecialchars($_GET['agent']);
        $sd = htmlspecialchars($_GET['sd']);
        $ed = htmlspecialchars($_GET['ed']);

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Database Variables
        $data['not_pick_client_calls'] = $this->VC_model->get_not_picked_client_calls($user, $sd, $ed);
        $data['not_pick_client_calls_details'] = $this->VC_model->get_not_picked_client_calls_details($user, $sd, $ed);
        $data['agt'] = $this->VC_model->agent();

		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('ocalls', $data);
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
        
		$this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('teams', $data);
        $this->load->view('common/footer');
	}
}