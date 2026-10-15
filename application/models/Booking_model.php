<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Saari bookings fetch karne ke liye
    public function get_bookings() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('bookings_data');
        return $query->result_array();
    }

    // ID ke basis par single booking data fetch karne ke liye (Edit ke liye)
    public function get_booking_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('bookings_data');
        return $query->row_array();
    }

    // Nayi booking insert karne ke liye
    public function insert_booking($data) {
        return $this->db->insert('bookings_data', $data);
    }

    // Booking update karne ke liye
    public function update_booking($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bookings_data', $data);
    }

    // Check if Project & Unit already exists
    public function check_unit_exists($project, $unit, $exclude_id = null) {
        $this->db->where('project', $project);
        $this->db->where('unit', $unit);
        if(!empty($exclude_id)) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('bookings_data');
        return $query->row_array();
    }

    // Projects list fetch karne ke liye
    public function get_projects() {
        $this->db->distinct();
        $this->db->select('project');
        $this->db->where('project !=', '');
        $query = $this->db->get('bookings_data');
        return $query->result_array();
    }
}
