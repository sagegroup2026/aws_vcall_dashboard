<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpt extends CI_Controller {
    function __construct(){
	    parent::__construct();
	    $this->load->database();
        $this->load->library('session');
	    $this->load->model('Report_model');
        $this->load->helper('form');
	}

    public function team_call_report()
    {
        $agent = $this->input->get('agent');
        $sd = $this->input->get('sd');
        $ed = $this->input->get('ed');

        // $team_id = $this->session->userdata('team');

        $data['sd'] = $sd;
        $data['ed'] = $ed;

        $team_id = $this->Report_model->get_manager_team($agent);
        $report = $this->Report_model->total_call_optimised($sd, $ed, $team_id);
        $total = count($report);

        foreach ($report as $i => &$row) {
            if ($i < 3) {
                // Top 3
                $row['row_color'] = 'top-performer'; // green
            }
            elseif ($i >= $total - 3) {
                // Bottom 3
                $row['row_color'] = 'low-performer'; // red
            }
            else {
                // Middle
                $row['row_color'] = '#ffffff';
            }
        }

        // 3️⃣ Send to view
        $data['total'] = $report;
        $data['agt'] = $this->Report_model->get_agents();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('tcalls', $data);
        $this->load->view('common/footer');
    }

    public function agent_call_report()
    {
        $agent = $this->input->get('agent');
        $sd = $this->input->get('sd');
        $ed = $this->input->get('ed');

        // $team_id = $this->session->userdata('team');

        $data['sd'] = $sd;
        $data['ed'] = $ed;

        $team_id = $this->Report_model->get_manager_team($agent);
		$data['managers'] = $this->Report_model->get_team_managers();
        $report = $this->Report_model->total_call_optimised($sd, $ed, $team_id);
        $total = count($report);

        foreach ($report as $i => &$row) {
            if ($i < 3) {
                // Top 3
                $row['row_color'] = 'top-performer'; // green
            }
            elseif ($i >= $total - 3) {
                // Bottom 3
                $row['row_color'] = 'low-performer'; // red
            }
            else {
                // Middle
                $row['row_color'] = '#ffffff';
            }
        }

        // 3️⃣ Send to view
        $data['total'] = $report;
        $data['agt'] = $this->Report_model->get_agents();

//print_r($report); exit();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('agent_call_report', $data);
        $this->load->view('common/footer');
    }

	
    public function daily_performance_report()
    {
        $agent = $this->input->get('agent');
        $sd = $this->input->get('sd');
        $ed = $this->input->get('ed');

        $team_id = $this->session->userdata('team');

        $data['sd'] = $sd;
        $data['ed'] = $ed;

//        $team_id = $this->Report_model->get_manager_team($agent);
        $report = $this->Report_model->daily_perf_rpt($sd, $ed, $agent);
        $total = count($report);

        foreach ($report as $i => &$row) {
            if ($i < 3) {
                // Top 3
                $row['row_color'] = 'top-performer'; // green
            }
            elseif ($i >= $total - 3) {
                // Bottom 3
                $row['row_color'] = 'low-performer'; // red
            }
            else {
               // Middle
                $row['row_color'] = '#ffffff';
            }
        }

        // 3️⃣ Send to view
        $data['total'] = $report;
        $data['agt'] = $this->Report_model->get_agents();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('dpr', $data);
        $this->load->view('common/footer');
    }

    public function detail_dpr()
    {
	$data['title'] = 'Detailed Report';
	$this->load->view('common/head', $data);
	$this->load->view('common/menu');
	$this->load->view('common/sidemenu');
	$this->load->view('dtl_dpr');
	$this->load->view('common/footer');
    }

	public function never_logged_in_report(){
		  $agent = $this->input->get('agent');
			$sd = $this->input->get('sd');
			$ed = $this->input->get('ed');

			$data['sd'] = $sd;
			$data['ed'] = $ed;
			$data['hd'] = "Never Logged In / Zero Activity Report";
			$data['shd'] = "Agents with no calls today";

			$team_id = !empty($agent) ? $this->Report_model->get_manager_team($agent) : null;
    
			// Logged-in user ki team session se nikalne ke liye
			$sess_name = $this->session->userdata("name");
    
			$data['managers'] = $this->Report_model->get_team_managers();
    
			// Naya Model method call kiya jo 0 calls wale agents laayega
			$report = $this->Report_model->get_never_logged_in_agents($sd, $ed, null);

			// Total array me logged user role aur team ensure karne ke liye saare users pass kiye
			$data['total'] = $report; 
    
			// Agar aapko session find karne ke liye saare users ki zaroorat ho toh yahan saara data merge kar sakte hain
			// Par agar session check keval $total par dependent hai, toh hum users table se bhi data le sakte hain. 
			// Neeche wale code me hum role find karne ke liye saare users ka array bhej rahe hain:
			$this->db->select('name as user_name, team, user_role');
			$data['total'] = array_merge($report, $this->db->get('users')->result_array());

			$this->load->view('common/head', $data);
			$this->load->view('common/menu');
			$this->load->view('common/sidemenu');
			$this->load->view('never_logged_in_report', $data); // Naya ya purana view file
			$this->load->view('common/footer');
	}



/**EEnd**/
}
