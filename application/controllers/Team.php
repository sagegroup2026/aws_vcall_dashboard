<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Team model load kiya hai
        $this->load->model('Team_model');
    }

    public function teamsList() {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', isset($uri[2]) ? $uri[2] : 'Teams List'));

        // SEO Variables
        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        // Team_model se data fetch karein
        $data['total'] = $this->Team_model->get_teams();
        $data['users'] = $this->db->where('status', 1)->get('users')->result_array();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('teamsList', $data);
        $this->load->view('common/footer');
    }

    // AJAX: Team Add ya Update karne ke liye
    public function save_team_ajax() {
        $id = $this->input->post('team_id');
        $tname = trim($this->input->post('tname'));
        $thead = $this->input->post('thead');

        if(empty($tname)){
            echo json_encode(['status' => 'error', 'message' => 'Team name cannot be empty!']);
            return;
        }

        $data = [
            'tname' => $tname,
            'thead' => $thead,
            'date_created' => date('Y-m-d H:i:s'),
            'status' => 1
        ];

        if(empty($id)) {
            // Insert via Team_model
            $result = $this->Team_model->insert_team($data);
            if($result == 'exists') {
                echo json_encode(['status' => 'exists', 'message' => 'This team already exists!']);
            } elseif($result == 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Team added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add team!']);
            }
        } else {
            // Update via Team_model
            $result = $this->Team_model->update_team($id, $data);
            if($result == 'exists') {
                echo json_encode(['status' => 'exists', 'message' => 'Team name already exists!']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Team updated successfully!']);
            }
        }
    }

    // AJAX: Single Team data fetch for Edit popup
    public function get_team_data() {
        $id = $this->input->post('id');
        $team = $this->Team_model->get_team_by_id($id);
        if($team) {
            echo json_encode(['status' => 'success', 'data' => $team]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Team not found!']);
        }
    }
}
