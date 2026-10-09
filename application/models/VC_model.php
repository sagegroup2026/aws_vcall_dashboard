<?php

    class VC_model extends CI_Model
    {
        function __construct()
        {
            parent:: __construct();
            $this->load->database();
            $this->db2 = $this->load->database('sr_trak', TRUE);
        }
        
        public function dashboard_data1($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}
// print_r($tid);exit();
            $this->db->select("
                cv.user_name,
                cv.sender_phone_number,
            
                COUNT(*) AS total_calls,

                SUM(CASE WHEN cv.call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,
                SUM(CASE WHEN cv.call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,

                COUNT(DISTINCT cv.receiver_phone_number) AS total_unique_calls,

                SUM(CASE 
                    WHEN cv.call_status IN ('Incoming (Answered)', 'Outgoing (Connected)') 
                    THEN 1 ELSE 0 END) AS total_connected_calls,

                SUM(CASE 
                    WHEN cv.call_status = 'Outgoing (Connected)' 
                    THEN 1 ELSE 0 END) AS total_outbound_connected_calls,

                SUM(CASE 
                    WHEN cv.call_status = 'Incoming (Answered)' 
                    THEN 1 ELSE 0 END) AS total_inbound_connected_calls,

                SUM(CASE WHEN cv.call_type = 'REJECTED' THEN 1 ELSE 0 END) AS total_rejected_calls,
                SUM(CASE WHEN cv.call_type = 'MISSED' THEN 1 ELSE 0 END) AS total_missed_calls,
                SUM(CASE WHEN cv.call_type = 'OUTGOING' and cv.call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,

                SUM(CASE 
                    WHEN cv.call_type = 'OUTGOING' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0
                    THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_outgoing_call_duration,

                SUM(CASE 
                    WHEN cv.call_type = 'INCOMING' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0
                    THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_incoming_call_duration
                ", FALSE);

            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type', 'VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid != 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type !=', 'UNKNOWN');

            $this->db->group_by(['cv.sender_phone_number', 'cv.user_name']);
            
            $qry = $this->db->get();
            print_r($this->db->last_query());exit();
            return $qry->row_array();
        }


        public function dashboard_data_old_sanjay($user, $from_date, $to_date, $team, $startSecond, $endSecond){

            // 🔹 Default values
            $from_date = $from_date . ' 00:00:00';
            $to_date   = $to_date . ' 23:59:59';
            $team = $this->session->userdata("team");
            
            // 🔹 Escape values (IMPORTANT for security)
            $from_date = $this->db->escape($from_date);
            $to_date   = $this->db->escape($to_date);

            $where = [];

            // Base condition
            $where[] = "cv.application_type = 'VCall'";

            // 🔹 User filter
            if (!empty($user)) {
                $where[] = "(cv.user_name LIKE '%$user%' OR REPLACE(cv.user_name, ' ', '') LIKE '%" . str_replace(' ', '', $user) . "%')";
            }

            if ($team > 0) {
                $where[] = "u.team = $team";
            }

            // 🔹 Date filters (VERY IMPORTANT to escape)
            if (!empty($from_date)) {
                $where[] = "cv.call_date >= $from_date";
            }

            if (!empty($to_date)) {
                $where[] = "cv.call_date <= $to_date";
            }

            // 🔹 Exclude UNKNOWN
            $where[] = "cv.call_type != 'UNKNOWN'";

            // Final WHERE string
            $where_sql = "WHERE " . implode(" AND ", $where);

            // 🔥 MAIN QUERY (Agent + Total)
            $sql = "
            SELECT 
            'TOTAL' AS user_name,
            NULL AS sender_phone_number,

            COUNT(*) AS total_calls,

            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' THEN 1 ELSE 0 END) AS total_outbound_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'incoming' THEN 1 ELSE 0 END) AS total_inbound_calls,

            COUNT(DISTINCT cv.receiver_phone_number) AS total_unique_calls,

            SUM(CASE 
                WHEN cv.call_status IN ('Incoming (Answered)', 'Outgoing (Connected)') 
                THEN 1 ELSE 0 
            END) AS total_connected_calls,

            SUM(CASE WHEN cv.call_status = 'Outgoing (Connected)' THEN 1 ELSE 0 END) AS total_outbound_connected_calls,
            SUM(CASE WHEN cv.call_status = 'Incoming (Answered)' THEN 1 ELSE 0 END) AS total_inbound_connected_calls,

            SUM(CASE WHEN LOWER(cv.call_type) = 'rejected' THEN 1 ELSE 0 END) AS total_rejected_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'missed' THEN 1 ELSE 0 END) AS total_missed_calls,

            SUM(CASE 
                WHEN LOWER(cv.call_type) = 'outgoing' AND cv.call_status = 'Not Connected' 
                THEN 1 ELSE 0 
            END) AS total_not_picked_client_calls,

            SUM(CASE 
                WHEN LOWER(cv.call_type) = 'outgoing'
                AND cv.duration REGEXP '^[0-9]+$'
                AND cv.duration > 0
                THEN CAST(cv.duration AS UNSIGNED)
                ELSE 0
            END) AS total_outgoing_call_duration,

            SUM(CASE 
                WHEN LOWER(cv.call_type) = 'incoming'
                AND cv.duration REGEXP '^[0-9]+$'
                AND cv.duration > 0
                THEN CAST(cv.duration AS UNSIGNED)
                ELSE 0
            END) AS total_incoming_call_duration,

            1 AS is_total

            FROM highrise_app_call_logs_vcall cv
            INNER JOIN users u ON cv.sender_phone_number LIKE CONCAT('%', u.mobile)

            $where_sql
            ";

            $query = $this->db->query($sql);
            // print_r($query);exit;
            // echo $this->db->last_query();exit;
            return $query->result_array();
        }

		public function dashboard_data_ooolddd($user, $from_date, $to_date, $team, $startSecond, $endSecond){
    // 🔹 Default values
    $from_date = $from_date . ' 00:00:00';
    $to_date = $to_date . ' 23:59:59';
    $team = $this->session->userdata("team");

    // 🔹 Escape values (IMPORTANT for security)
    $from_date = $this->db->escape($from_date);
    $to_date = $this->db->escape($to_date);

    $where = [];

    // Base condition
    $where[] = "cv.application_type = 'VCall'";

    // 🔹 User filter
    if (!empty($user)) {
        $where[] = "(cv.user_name LIKE '%$user%' OR REPLACE(cv.user_name, ' ', '') LIKE '%" . str_replace(' ', '', $user) . "%')";
    }

    if ($team > 0) {
        $where[] = "u.team = $team";
    }

    // 🔹 Date filters (VERY IMPORTANT to escape)
    if (!empty($from_date)) {
        $where[] = "cv.call_date >= $from_date";
    }
    if (!empty($to_date)) {
        $where[] = "cv.call_date <= $to_date";
    }

    // 🔹 New Duration Filter ($startSecond aur $endSecond ke liye)
    // Dono values empty nahi honi chahiye tabhi filter lagega
    if (($startSecond !== '' && $startSecond !== null) && ($endSecond !== '' && $endSecond !== null)) {
        $startSecond = (int)$startSecond;
        $endSecond = (int)$endSecond;
        
        // cv.duration valid number hona chahiye aur range ke bich hona chahiye
        $where[] = "cv.duration REGEXP '^[0-9]+$' AND CAST(cv.duration AS UNSIGNED) BETWEEN $startSecond AND $endSecond";
    }

    // 🔹 Exclude UNKNOWN
    $where[] = "cv.call_type != 'UNKNOWN'";

    // Final WHERE string
    $where_sql = "WHERE " . implode(" AND ", $where);

    // 🔥 MAIN QUERY (Agent + Total)
    $sql = "
        SELECT 
            'TOTAL' AS user_name, 
            NULL AS sender_phone_number, 
            COUNT(*) AS total_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' THEN 1 ELSE 0 END) AS total_outbound_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'incoming' THEN 1 ELSE 0 END) AS total_inbound_calls,
            COUNT(DISTINCT cv.receiver_phone_number) AS total_unique_calls,
            SUM(CASE WHEN cv.call_status IN ('Incoming (Answered)', 'Outgoing (Connected)') THEN 1 ELSE 0 END) AS total_connected_calls,
            SUM(CASE WHEN cv.call_status = 'Outgoing (Connected)' THEN 1 ELSE 0 END) AS total_outbound_connected_calls,
            SUM(CASE WHEN cv.call_status = 'Incoming (Answered)' THEN 1 ELSE 0 END) AS total_inbound_connected_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'rejected' THEN 1 ELSE 0 END) AS total_rejected_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'missed' THEN 1 ELSE 0 END) AS total_missed_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' AND cv.call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,
            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0 THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_outgoing_call_duration,
            SUM(CASE WHEN LOWER(cv.call_type) = 'incoming' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0 THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_incoming_call_duration,
            1 AS is_total
        FROM highrise_app_call_logs_vcall cv
        INNER JOIN users u ON cv.sender_phone_number LIKE CONCAT('%', u.mobile)
        $where_sql
    ";

    $query = $this->db->query($sql);
    return $query->result_array();
}


public function dashboard_data($user, $from_date, $to_date, $team, $startSecond, $endSecond){
    // 🔹 Default values
    $from_date = $from_date . ' 00:00:00';
    $to_date = $to_date . ' 23:59:59';
    $team = $this->session->userdata("team");

    // 🔹 Escape values (IMPORTANT for security)
    $from_date = $this->db->escape($from_date);
    $to_date = $this->db->escape($to_date);

    $where = [];

    // Base condition
    $where[] = "cv.application_type = 'VCall'";

    // 🔹 Personal numbers wipeout condition (Global exclusion)
    $where[] = "cv.receiver_phone_number NOT IN (SELECT number FROM presonalNumberWipeOut WHERE status = 1)";

    // 🔹 User filter
    if (!empty($user)) {
        $where[] = "(cv.user_name LIKE '%$user%' OR REPLACE(cv.user_name, ' ', '') LIKE '%" . str_replace(' ', '', $user) . "%')";
    }

    if ($team > 0) {
        $where[] = "u.team = $team";
    }

    // 🔹 Date filters (VERY IMPORTANT to escape)
    if (!empty($from_date)) {
        $where[] = "cv.call_date >= $from_date";
    }
    if (!empty($to_date)) {
        $where[] = "cv.call_date <= $to_date";
    }

    // 🔹 New Duration Filter ($startSecond aur $endSecond ke liye)
    if (($startSecond !== '' && $startSecond !== null) && ($endSecond !== '' && $endSecond !== null)) {
        $startSecond = (int)$startSecond;
        $endSecond = (int)$endSecond;
        
        $where[] = "cv.duration REGEXP '^[0-9]+$' AND CAST(cv.duration AS UNSIGNED) BETWEEN $startSecond AND $endSecond";
    }

    // 🔹 Exclude UNKNOWN
    $where[] = "cv.call_type != 'UNKNOWN'";

    // Final WHERE string
    $where_sql = "WHERE " . implode(" AND ", $where);

    // 🔥 MAIN QUERY (Agent + Total)
    $sql = "        SELECT             'TOTAL' AS user_name,             NULL AS sender_phone_number,             COUNT(*) AS total_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' THEN 1 ELSE 0 END) AS total_outbound_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'incoming' THEN 1 ELSE 0 END) AS total_inbound_calls,            COUNT(DISTINCT cv.receiver_phone_number) AS total_unique_calls,            SUM(CASE WHEN cv.call_status IN ('Incoming (Answered)', 'Outgoing (Connected)') THEN 1 ELSE 0 END) AS total_connected_calls,            SUM(CASE WHEN cv.call_status = 'Outgoing (Connected)' THEN 1 ELSE 0 END) AS total_outbound_connected_calls,            SUM(CASE WHEN cv.call_status = 'Incoming (Answered)' THEN 1 ELSE 0 END) AS total_inbound_connected_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'rejected' THEN 1 ELSE 0 END) AS total_rejected_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'missed' THEN 1 ELSE 0 END) AS total_missed_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' AND cv.call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,            SUM(CASE WHEN LOWER(cv.call_type) = 'outgoing' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0 THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_outgoing_call_duration,            SUM(CASE WHEN LOWER(cv.call_type) = 'incoming' AND cv.duration REGEXP '^[0-9]+$' AND cv.duration > 0 THEN CAST(cv.duration AS UNSIGNED) ELSE 0 END) AS total_incoming_call_duration,            1 AS is_total        FROM highrise_app_call_logs_vcall cv        INNER JOIN users u ON cv.sender_phone_number LIKE CONCAT('%', u.mobile)        $where_sql    ";

    $query = $this->db->query($sql);
     $query->result_array();


	echo $this->db->last_query();exit;
}





       	public function get_all_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('cv.*, u.team, u.last_login');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type !=', 'UNKNOWN');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
//print_r($qry);exit;
//echo $this->db->last_query();exit;
            return $qry->num_rows();
        }

        public function get_in_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->like('cv.call_type', 'INCOMING', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_out_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->like('cv.call_type', 'OUTGOING', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_unq_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('DISTINCT(cv.receiver_phone_number)');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type !=', 'UNKNOWN');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_cnctd_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.duration !=', '0');
            $this->db->like('cv.call_status', 'ing', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query($qry));exit();
            return $qry->num_rows();
        }

        public function get_rjctd_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_status', 'Rejected');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_msd_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->like('cv.call_type', 'Missed', 'both');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_not_picked_client_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type', 'OUTGOING');
            $this->db->where('cv.call_status', 'Not Connected');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_nvr_atnd_calls($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type', 'OUTGOING');
            $this->db->where('cv.call_status', 'Not Connected');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->num_rows();
        }

        public function get_out_cnctd_calls($user, $sd, $ed){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall');
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
            $this->db->from('highrise_app_call_logs_vcall');
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
            $this->db->from('highrise_app_call_logs_vcall');
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
            $this->db->from('highrise_app_call_logs_vcall');
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
            $this->db->from('highrise_app_call_logs_vcall');
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

        public function get_all_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*, cv.name as cnm');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type !=', 'UNKNOWN');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
//echo $this->db->last_query();exit;
            return $qry->result_array();
        }

        public function get_out_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->like('cv.call_type', 'OUTGOING', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_in_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->like('cv.call_type', 'INCOMING', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_unq_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('cv.receiver_phone_number, cv.name, cv.sender_phone_number, cv.user_name, COUNT(cv.receiver_phone_number) AS call_made');

            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->group_by('cv.receiver_phone_number');
            $query = $this->db->get();
            // echo $this->db->last_query();exit;
            return $query->result_array();
        }

        public function get_cnctd_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}
            
            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.duration !=', '0');
            $this->db->like('cv.call_status', 'ing', 'both');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query($qry));exit();
            return $qry->result_array();
        }

        public function get_rjctd_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_status', 'Rejected');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_msd_calls_details($user, $sd, $ed, $tid){
             $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->like('cv.call_status', 'Missed', 'both');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function get_not_picked_client_calls_details($user, $sd, $ed, $tid){
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select('*');
            $this->db->from('highrise_app_call_logs_vcall cv');
            $this->db->join('users u', "RIGHT(cv.sender_phone_number, 10) = RIGHT(u.mobile, 10)", 'inner');
            $this->db->where('cv.application_type','VCall');
            if(!empty($user)){
                $this->db->like('cv.user_name', $user, 'both');
            }
            if($tid > 0){
                $this->db->where('u.team',$tid);
            }
            $this->db->where('cv.call_date >=', $sd);
            $this->db->where('cv.call_date <=', $ed);
            $this->db->where('cv.call_type', 'OUTGOING');
            $this->db->where('cv.call_status', 'Not Connected');
            $this->db->order_by('cv.id', 'desc');
            $qry = $this->db->get();
            // print_r($this->db->last_query());exit();
            return $qry->result_array();
        }

        // Master Pages
        public function team(){
            $this->db->select('
                t.tname,
                th.name AS team_head_name,
                th.mobile AS team_head_mobile
            ');

            $this->db->from('users u');
            $this->db->join('team t', 'u.team = t.id', 'inner');
            $this->db->join('users th', 't.thead = th.id', 'inner');

            $this->db->where('u.status', 1);
            $this->db->group_by('t.tname');
            $this->db->order_by('t.tname', 'ASC');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function team_details(){
            $this->db->select('
                u.name,
                u.username,
                u.mobile,
                u.email,
                u.designation,
                u.user_role,
                u.last_login,
                t.tname,
                th.name AS team_head_name,
                th.mobile AS team_head_mobile
            ');

            $this->db->from('users u');
            $this->db->join('team t', 'u.team = t.id', 'inner');
            $this->db->join('users th', 't.thead = th.id', 'inner');

            $this->db->where('u.status', 1);
            $this->db->order_by('t.tname', 'ASC');
            $this->db->order_by('u.name', 'ASC');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        public function agent($tid){
            $this->db->select('*');
            $this->db->from('users');
            if($tid != 0){
                $this->db->where('team',$tid);
            }
            $this->db->where('status','1');
            $this->db->where('user_role !=','Admin');
            $this->db->order_by('name', 'asc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

        // public function dtrack(){
        //     $this->db2->select('user_name, SUM(total_entries) AS rcpt');
        //     $this->db2->from('highrise_app_back_office_productivity_table');
        //     $this->db2->like('type', 'receipt', 'both');
        //     $this->db2->like('created_at', 'feb 14', 'both');
        //     $this->db2->group_by('user_name');
        //     $this->db2->order_by('id', 'DESC');
        //     $qry = $this->db2->get();
        //     return $qry->result_array();
        // }

        public function GetHomeVisitTotal($sd, $ed)
        {
            $currentDate = date('Y-m-d');

            $sd = !empty($sd) ? $sd : $currentDate;
            $ed = !empty($ed) ? $ed : $currentDate;

            $this->db2->select('name, COUNT(id) as total_count');
            $this->db2->from('highrise_app_homevisit');

             $this->db2->where('date >=', $sd);
             $this->db2->where('date <=', $ed);

            $this->db2->group_by('name');
            //$this->db2->order_by('date', 'DESC');

            $qry = $this->db2->get();
            return $qry->result_array();

             
        }

        public function highrise_app_back_office_productivity_table_Count($sd, $ed){
            $currentDate = date('D M d Y H:i:s') . " GMT+0530";

            $sd = !empty($sd) ? $sd : $currentDate;
            $ed = !empty($ed) ? $ed : $currentDate;

 
            $this->db2->select("
        user_name,

        SUM(CASE WHEN type = 'Receipt Preparation' 
            THEN total_entries ELSE 0 END) AS reciept_preparation,

        SUM(CASE WHEN type = 'Demand' 
            THEN total_entries ELSE 0 END) AS demand,

        SUM(CASE WHEN type = 'File Approval' 
            THEN total_entries ELSE 0 END) AS file_approval,

        SUM(CASE WHEN type = 'Possession Certificate' 
            THEN total_entries ELSE 0 END) AS possession_certificate,

        SUM(CASE WHEN type = 'Customer Office Visit' 
            THEN total_entries ELSE 0 END) AS customer_office_visit,
        
        SUM(CASE WHEN type = 'Meeting With Senior' 
            THEN total_entries ELSE 0 END) AS meeting_with_senior,

        SUM(CASE WHEN type = 'Registry Preparation' 
            THEN total_entries ELSE 0 END) AS registry_preparation,

        SUM(CASE WHEN type = 'Customer Grievance/Discussion' 
            THEN total_entries ELSE 0 END) AS customer_grievance_discussion,

        SUM(CASE WHEN type = 'Documentation - All Type of Documentation' 
            THEN total_entries ELSE 0 END) AS documentation_all_type_of_documentation,
        
        SUM(CASE WHEN type = 'File Approval' 
            THEN total_entries ELSE 0 END) AS file_approval,

        SUM(CASE WHEN type = 'Registry Authorisation Physical' 
            THEN total_entries ELSE 0 END) AS  registry_authorisation_physical,
        
         SUM(CASE WHEN type = 'Major/Minor Payment Discussion' 
            THEN total_entries ELSE 0 END) AS  major_minor_payment_discussion,        
        SUM(CASE WHEN type = 'Booking File Complete - Verification' 
            THEN total_entries ELSE 0 END) AS  booking_file_complete_verification  
             

    ");

    $this->db2->from('highrise_app_back_office_productivity_table');

    $this->db2->where("
        DATE(
            STR_TO_DATE(LEFT(created_at, 24), '%a %b %d %Y %H:%i:%s')
        ) BETWEEN '$sd' AND '$ed'
    ", NULL, FALSE);

    $this->db2->group_by('user_name');
    $this->db2->order_by('user_name', 'ASC');

    $query = $this->db2->get();
    return $query->result_array();

        }



        public function totaldialedCall($sd, $ed){

            $currentDate = date('Y-m-d');

            $sd = !empty($sd) ? $sd : $currentDate;
            $ed = !empty($ed) ? $ed : $currentDate;
            
            $this->db->select('sender_phone_number, COUNT(*) as total_calls');
            $this->db->from('highrise_app_call_logs_vcall');
            
            // Date range (Optimized version)
            $this->db->where('call_date >=', $sd . ' 00:00:00');
            $this->db->where('call_date <=', $ed . ' 23:59:59');
            
            // IN condition
            $this->db->where_in('call_type', [
                'OUTGOING',
                'WIFIOUTGOING',
                'MISSED',
                'INCOMING',
                'REJECTED'
            ]);
            
            $this->db->group_by('sender_phone_number');
            $this->db->order_by('total_calls', 'DESC');
            
            $query = $this->db->get();
            return $query->result_array();

        }


        public function bookingDataCount($sd = null, $ed = null)
        {
            $currentDate = date("F d, Y");
        
            // Agar date "February 21, 2026" format me aa rahi hai
            if (!empty($sd)) {
                $sd = date("F d, Y", strtotime($sd));
                $booking_month = date("F", strtotime($sd));
                $booking_year = date("Y", strtotime($sd));
            } else {
                $sd = $currentDate;
                $booking_month = date("F", strtotime($currentDate));
                $booking_year = date("Y", strtotime($currentDate));
            }
        
            if (!empty($ed)) {
                $ed = date("F d, Y", strtotime($ed));
                $booking_month = date("F", strtotime($ed));
                $booking_year = date("Y", strtotime($ed));
            } else {
                $ed = $currentDate;
                $booking_month = date("F", strtotime($currentDate));
                $booking_year = date("Y", strtotime($currentDate));
            }
        
            $this->db2->select('sales_person, SUM(bookings) as total_bookings');
            $this->db2->from('bookings_data');
            $this->db2->where('booking_date >=', $sd);
            $this->db2->where('booking_date <=', $ed);
            $this->db2->where('booking_month', $booking_month);
            $this->db2->where('booking_year', $booking_year);
            $this->db2->group_by('sales_person');
            $this->db2->order_by('total_bookings', 'DESC');
        
           return $this->db2->get()->result();

            
            
        }

        public function total_visit_count($sd = null, $ed = null){
            $this->db2->select('sales_name, COUNT(*) as total_visits');
            $this->db2->from('highrise_app_sitevisit');
            $this->db2->where('Visit_Date >=', $sd);
            $this->db2->where('Visit_Date <=', $ed);
            $this->db2->group_by('sales_name');
            $this->db2->order_by('total_visits', 'DESC');

            return $this->db2->get()->result();
        }
      
        public function getVisitCountcorpByDate($sd = null, $ed = null)
        {
            $currentDate = date("Y-m-d");

            // Start Date
            $sd = !empty($sd) ? date("Y-m-d", strtotime($sd)) : $currentDate;

            // End Date
            $ed = !empty($ed) ? date("Y-m-d", strtotime($ed)) : $currentDate;

            $sql = "
                SELECT person_name, COUNT(*) AS total_corp_visits
                FROM (
                    
                    SELECT TRIM(name) AS person_name
                    FROM highrise_app_corpformdata
                    WHERE visit_date BETWEEN ? AND ?
                    
                    UNION ALL
                    
                    SELECT TRIM(
                        SUBSTRING_INDEX(
                            SUBSTRING_INDEX(REPLACE(cofel_name, ', ', ','), ',', numbers.n),
                            ',', -1
                        )
                    ) AS person_name
                    FROM highrise_app_corpformdata
                    JOIN (
                        SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
                    ) numbers
                    ON CHAR_LENGTH(REPLACE(cofel_name, ', ', ',')) 
                    - CHAR_LENGTH(REPLACE(REPLACE(cofel_name, ', ', ','), ',', '')) 
                    >= numbers.n - 1
                    WHERE visit_date BETWEEN ? AND ?
                    
                ) AS combined
                WHERE person_name IS NOT NULL 
                AND person_name != ''
                GROUP BY person_name
                ORDER BY total_corp_visits DESC
            ";

            $query = $this->db2->query($sql, [$sd, $ed, $sd, $ed]);

            return $query->result();
        }

// End
    }
