<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {

    public function get_all_members() {
        $this->db->select('users.*, team.tname');
        $this->db->from('users');
        $this->db->join('team', 'team.id = users.team', 'left');
        $this->db->order_by('users.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_member($data) {
        // Check if mobile number already exists
        $this->db->where('mobile', $data['mobile']);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->insert('users', $data);
            return $this->db->affected_rows() > 0 ? 'success' : 'error';
        }
    }

   public function update_member($id, $data) {
        // Check if mobile number exists for other users
        $this->db->where('mobile', $data['mobile']);
        $this->db->where('id !=', $id);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->where('id', $id);
            $this->db->update('users', $data);
            return 'success';
        }
    }

    public function get_member_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row_array();
    }
}
