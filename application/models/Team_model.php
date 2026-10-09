<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_model extends CI_Model {

    public function get_teams() {
        $this->db->select('team.*, users.name as team_head_name');
        $this->db->from('team');
        $this->db->join('users', 'users.id = team.thead', 'left');
        $this->db->where('team.status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_team($data) {
        $this->db->where('tname', $data['tname']);
        $query = $this->db->get('team');
        
        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->insert('team', $data);
            return $this->db->affected_rows() > 0 ? 'success' : 'error';
        }
    }

    public function update_team($id, $data) {
        $this->db->where('tname', $data['tname']);
        $this->db->where('id !=', $id);
        $query = $this->db->get('team');

        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->where('id', $id);
            $this->db->update('team', $data);
            return 'success';
        }
    }

    public function get_team_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('team')->row_array();
    }
}
