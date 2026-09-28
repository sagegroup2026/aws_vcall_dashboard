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
}
