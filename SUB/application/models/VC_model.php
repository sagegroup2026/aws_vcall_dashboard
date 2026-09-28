<?php

    class VC_model extends CI_Model
    {
        function __construct()
        {
            parent:: __construct();
        }
        
       	public function get_all_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type', 'VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type !=', 'UNKNOWN');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_in_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->like('call_type', 'INCOMING', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_out_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->like('call_type', 'OUTGOING', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_unq_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('DISTINCT(receiver_phone_number)');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type !=', 'UNKNOWN');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_cnctd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_status', 'ing', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query($qry));exit();
            return $qry->num_rows();
        }

        public function get_rjctd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_status', 'Rejected');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_msd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->like('call_status', 'Missed', 'both');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_not_picked_client_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type', 'OUTGOING');
            $this->db->where('call_status', 'Not Connected');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_nvr_atnd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type', 'OUTGOING');
            $this->db->where('call_status', 'Not Connected');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_out_cnctd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_type', 'Outgoing', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_in_cnctd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_type', 'Incoming', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_calls_duration($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select_sum('duration');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_type', 'ing', 'both');
            // $this->db->where('call_status', 'Outgoing (Connected)');
            // $this->db->or_where('call_status', 'Incoming (Answered)');
            $qry = $this->db->get();
            
            $qry->row();
            return $qry;
        }

        public function get_outcalls_duration($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select_sum('duration');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_type', 'Outgoing', 'both');
            $qry = $this->db->get();
            $qry->row();
            return $qry;
        }

        public function get_incalls_duration($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select_sum('duration');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_type', 'Incoming', 'both');
            $qry = $this->db->get();
            $qry->row();
            return $qry;
        }

        public function get_all_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type !=', 'UNKNOWN');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_out_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->like('call_type', 'OUTGOING', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_in_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->like('call_type', 'INCOMING', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_unq_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('receiver_phone_number, name, sender_phone_number, user_name, COUNT(receiver_phone_number) AS call_made');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->group_by('receiver_phone_number');
            $query = $this->db->get();
            
            return $query->result_array();
        }

        public function get_cnctd_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}
            
            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('duration !=', '0');
            $this->db->like('call_status', 'ing', 'both');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query($qry));exit();
            return $qry->result_array();
        }

        public function get_rjctd_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_status', 'Rejected');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_msd_calls_details($user, $sd, $ed){
             $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->like('call_status', 'Missed', 'both');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_not_picked_client_calls_details($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('call_logs_vcall');
            $this->db->where('application_type','VCall');
            if(!empty($user)){
                $this->db->like('user_name', $user, 'both');
            }
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            $this->db->where('call_type', 'OUTGOING');
            $this->db->where('call_status', 'Not Connected');
            $this->db->order_by('id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query());exit();
            return $qry->result_array();
        }

        // Master Pages
        public function team(){
            $this->db->select('*');
            $this->db->from('team');
            $this->db->where('status','1');
            $this->db->order_by('tname', 'asc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function agent(){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('status','1');
            $this->db->order_by('name', 'asc');
            $qry = $this->db->get();
            return $qry->result_array();
        }
    }