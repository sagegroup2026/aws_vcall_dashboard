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
        return $this->db->insert('users', $data);
    }

    public function update_member($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function get_member_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row_array();
    }
}
