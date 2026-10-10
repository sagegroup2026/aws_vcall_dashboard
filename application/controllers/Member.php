<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Member_model');
    }

    public function index() {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $url = ucwords(str_replace('-', ' ', isset($uri[2]) ? $uri[2] : 'Team Members'));

        $data['title'] = $url . ' | VCall - Admin & Dashboard';
        $data['description'] = $url . ' | VCall - Admin & Dashboard';
        
        $data['members'] = $this->Member_model->get_all_members();
        $data['teams'] = $this->db->where('status', 1)->get('team')->result_array();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('membersList', $data);
        $this->load->view('common/footer');
    }

    public function save_member_ajax() {
        $id = $this->input->post('member_id');
        
        $data = [
            'name'        => trim($this->input->post('name')),
            'username'    => trim($this->input->post('username')),
            'email'       => trim($this->input->post('email')),
            'password'    => $this->input->post('password'),
            'mobile'      => trim($this->input->post('mobile')),
            'gender'      => $this->input->post('gender'),
            'designation' => $this->input->post('designation'),
            'team'        => $this->input->post('team'),
            'status'      => 1
        ];

        if(empty($data['name']) || empty($data['mobile'])) {
            echo json_encode(['status' => 'error', 'message' => 'Name and Mobile cannot be empty!']);
            return;
        }

        if(empty($id)) {
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->Member_model->insert_member($data);
            
            if($result == 'exists') {
                echo json_encode(['status' => 'exists', 'message' => 'This mobile number is already registered!']);
            } elseif($result == 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Member added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add member!']);
            }
        } else {
            $result = $this->Member_model->update_member($id, $data);
            
            if($result == 'exists') {
                echo json_encode(['status' => 'exists', 'message' => 'This mobile number is already registered with another member!']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Member updated successfully!']);
            }
        }
    }

    public function get_member_data() {
        $id = $this->input->post('id');
        $member = $this->Member_model->get_member_by_id($id);
        if($member) {
            echo json_encode(['status' => 'success', 'data' => $member]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Member not found!']);
        }
    }
}
