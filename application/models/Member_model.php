<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {

    public function get_all_members() {
        $this->db->select('users.*, team.tname');
        $this->db->from('users');
        $this->db->join('team', 'team.id = users.team', 'left');
        $this->db->order_by('users.name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_member($data) {
        $this->db->where('mobile', $data['mobile']);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->insert('users', $data);
            if ($this->db->affected_rows() > 0) {
                $insert_id = $this->db->insert_id();
                
                // Agar designation DGM hai aur Team assigned hai, toh team table me thead update kar do
                if (isset($data['designation']) && $data['designation'] === 'DGM' && !empty($data['team']) && $data['team'] != 0) {
                    $this->db->where('id', $data['team']);
                    $this->db->update('team', ['thead' => $insert_id]);
                }

                return 'success';
            }
            return 'error';
        }
    }

    public function update_member($id, $data) {
        $this->db->where('mobile', $data['mobile']);
        $this->db->where('id !=', $id);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return 'exists';
        } else {
            $this->db->where('id', $id);
            $this->db->update('users', $data);

            // Agar designation DGM hai aur Team assigned hai, toh team table me thead update kar do
            if (isset($data['designation']) && $data['designation'] === 'DGM' && !empty($data['team']) && $data['team'] != 0) {
                $this->db->where('id', $data['team']);
                $this->db->update('team', ['thead' => $id]);
            }

            return 'success';
        }
    }

    public function get_member_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row_array();
    }
}
