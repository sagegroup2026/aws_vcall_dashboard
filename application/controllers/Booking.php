<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Booking_model');
    }

    public function booking_report() {
        $data['title'] = 'All Bookings Report | VCall - Admin & Dashboard';
        
        $data['teams'] = $this->db->where('status', 1)->get('team')->result_array(); 
        $data['users'] = $this->db->where('status', 1)->get('users')->result_array(); 
        $data['projects'] = $this->Booking_model->get_projects();

        $this->load->view('common/head', $data);
        $this->load->view('common/menu');
        $this->load->view('common/sidemenu');
        $this->load->view('booking_report_view', $data);
        $this->load->view('common/footer');
    }

    // AJAX: Fetch bookings for pagination & AJAX table load
    public function fetch_bookings_ajax() {
        $limit = $this->input->post('limit') ? intval($this->input->post('limit')) : 10;
        $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $search = $this->input->post('search') ? trim($this->input->post('search')) : '';

        $start = ($page - 1) * $limit;

        $bookings = $this->Booking_model->get_paginated_bookings($limit, $start, $search);
        $total_records = $this->Booking_model->get_bookings_count($search);
        $total_pages = ceil($total_records / $limit);

        echo json_encode([
            'status' => 'success',
            'data' => $bookings,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'per_page' => $limit
        ]);
    }

    // AJAX: Add ya Update Booking with Duplicate Validation
    // AJAX: Add ya Update Booking with Duplicate Validation
    public function save_booking_ajax() {
        $id = $this->input->post('booking_id');
        $project = trim($this->input->post('project'));
        $unit = trim($this->input->post('unit'));

        // Validation check for duplicate project and unit
        $existing_booking = $this->Booking_model->check_unit_exists($project, $unit, $id);
        
        if(!empty($existing_booking)) {
            $msg = "Yeh Unit (" . $unit . ") project '" . $project . "' ke liye already entered hai!<br><br>" .
                   "• <b>Customer:</b> " . $existing_booking['customer_name'] . "<br>" .
                   "• <b>Sales Person:</b> " . $existing_booking['sales_person'] . "<br>" .
                   "• <b>Team:</b> " . $existing_booking['team'] . "<br>" .
                   "• <b>Date:</b> " . $existing_booking['booking_date'];

            echo json_encode(['status' => 'exists', 'message' => $msg]);
            return;
        }

        // Date ko properly standard format (YYYY-MM-DD) mein convert karna
        $raw_date = $this->input->post('booking_date');
        $formatted_date = !empty($raw_date) ? date('Y-m-d', strtotime($raw_date)) : '';

        $data = [
            'booking_date'   => $formatted_date, // Yahan standard format save hoga
            'booking_day'    => !empty($formatted_date) ? date('D', strtotime($formatted_date)) : '',
            'booking_week'   => !empty($formatted_date) ? date('W', strtotime($formatted_date)) : '',
            'booking_month'  => $this->input->post('booking_month'),
            'booking_year'   => $this->input->post('booking_year'),
            'project'        => $project,
            'unit'           => $unit,
            'type'           => $this->input->post('type'),
            'sales_person'   => $this->input->post('sales_person'),
            'team'           => $this->input->post('team'),
            'source'         => $this->input->post('source'),
            'bookings'       => $this->input->post('bookings'),
            'sub_source'     => $this->input->post('sub_source'),
            'customer_name'  => $this->input->post('customer_name'),
            'DGM_Name'       => $this->input->post('DGM_Name'),
            'highrise'       => $this->input->post('highrise')
        ];

        if(empty($id)) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $insert = $this->Booking_model->insert_booking($data);
            if($insert) {
                echo json_encode(['status' => 'success', 'message' => 'Booking added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add booking!']);
            }
        } else {
            $update = $this->Booking_model->update_booking($id, $data);
            if($update) {
                echo json_encode(['status' => 'success', 'message' => 'Booking updated successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update booking!']);
            }
        }
    }

    // AJAX: Single Booking data fetch for Edit popup
    public function get_booking_data() {
        $id = $this->input->post('id');
        $booking = $this->Booking_model->get_booking_by_id($id);
        if($booking) {
            echo json_encode(['status' => 'success', 'data' => $booking]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Booking not found!']);
        }
    }
}
