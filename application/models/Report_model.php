<?php

    class Report_model extends CI_Model
    {
        function __construct()
        {
            parent:: __construct();
        }


        private function base_query($team,$user,$sd,$ed)
        {
            $this->db->from('highrise_app_call_logs_vcall c');
            $this->db->join('users u','u.mobile = c.sender_phone_number');
            $this->db->join('team t','t.id = u.team');
        
            if (!empty($team)) {
                $this->db->where('t.id',$team);
            }
        
            if (!empty($user)) {
                $this->db->where('u.id',$user);
            }
        
            if (!empty($sd) && !empty($ed)) {
                $this->db->where('DATE(c.call_date) >=',$sd);
                $this->db->where('DATE(c.call_date) <=',$ed);
            }
        }
        
        public function get_manager_team($manager_name){
            $this->db->select('team');
            $this->db->from('users');
            $this->db->where('name', $manager_name);
            $this->db->where('user_role', 'Manager');
            $qry = $this->db->get();
            $row = $qry->row_array();
            return isset($row['team']) ? $row['team'] : null;
        }

        public function get_agents(){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('status','1');
            $this->db->where('user_role','Manager');
            $this->db->order_by('name', 'asc');
            $qry = $this->db->get();
            return $qry->result_array();
        }

	public function daily_perf_rpt_old($sd, $ed, $agent){
	    $sql = "
		SELECT
		    '$agent' AS employee_name,

		    ( SELECT COUNT(*) FROM highrise_app_corpformdata WHERE name = '$agent' AND visit_date BETWEEN $sd AND $ed) AS total_corp_visit,

		    ( SELECT COUNT(*) FROM highrise_app_sitevisit WHERE sales_name = '$agent' AND visit_type = 'direct' AND Visit_Date BETWEEN $sd AND $ed) AS total_site_visits,

		    ( SELECT COUNT(*) FROM highrise_app_homevisit WHERE name = '$agent' AND date BETWEEN $sd AND $ed) AS total_home_visit
		";

	    $query = $this->db->query($sql);
echo $this->db->last_query() . "<br><br>";
	    $result = $query->row_array();

echo "<pre>";
print_r($result); exit();
	}

public function daily_perf_rpt($from_date, $to_date, $team = 0)
{
    $sql = "
    SELECT
        u.id,
        u.name,
	u.designation,
        u.team,

        COALESCE(cv.total_corp_visit,0) AS total_corp_visit,
        COALESCE(sv.total_dsite_visit,0) AS total_dsite_visit,
	COALESCE(dsv.total_ind_site_visit,0) AS total_ind_site_visit,
        COALESCE(hv.total_home_visit,0) AS total_home_visit,
	COALESCE(ad.total_admission,0) AS total_admission,
	COALESCE(ip.total_ip,0) AS total_ip

    FROM users u
    RIGHT JOIN highrise_app_members m
    ON u.mobile = m.mobile

    LEFT JOIN
    (
        SELECT
            aname,
            COUNT(*) total_corp_visit
        FROM (
		SELECT name AS aname FROM highrise_app_corpformdata WHERE visit_date BETWEEN ? AND ? AND name IS NOT NULL AND name != ''
		UNION ALL
		SELECT cofel_name AS aname FROM highrise_app_corpformdata WHERE visit_date BETWEEN ? AND ? AND cofel_name IS NOT NULL AND cofel_name != ''
        ) tcv
        GROUP BY aname
    ) cv
        ON cv.aname=u.name

    LEFT JOIN
    (
        SELECT
            sales_name,
            COUNT(*) total_dsite_visit
        FROM highrise_app_sitevisit
        WHERE visit_type='direct'
        AND Visit_Date BETWEEN ? AND ?
        GROUP BY sales_name
    ) sv
        ON sv.sales_name=u.name

    LEFT JOIN
    (
	SELECT
	   sales_name,
	   COUNT(*) total_ind_site_visit
	FROM highrise_app_sitevisit
	WHERE visit_type='indirect'
	AND Visit_Date BETWEEN ? AND ?
	GROUP BY sales_name
    ) dsv
	ON dsv.sales_name=u.name

    LEFT JOIN
    (
        SELECT
            aname,
            COUNT(*) total_home_visit
        FROM (
		SELECT name AS aname FROM highrise_app_homevisit WHERE date BETWEEN ? AND ? AND name IS NOT NULL AND name != ''
		UNION ALL
		SELECT co_fellow AS aname FROM highrise_app_homevisit WHERE date BETWEEN ? AND ? AND name IS NOT NULL AND name != ''
	) thv
        GROUP BY aname
    ) hv
        ON hv.aname=u.name

    LEFT JOIN
    (
	SELECT
	    name_id,
	    COUNT(*) total_admission
	FROM highrise_app_admissiondata
	WHERE date BETWEEN ? AND ?
	GROUP BY name_id
    ) ad
	ON ad.name_id=m.id

    LEFT JOIN
    (
	SELECT
	    name_id,
	    COUNT(*) total_ip
	FROM highrise_app_ipdata
	WHERE date BETWEEN ? AND ?
	GROUP BY name_id
    ) ip
	ON ip.name_id=m.id

    WHERE u.status=1 and u.user_role != 'Admin'
    ";

    $params = [
        $from_date,
        $to_date,
        $from_date,
        $to_date,
        $from_date,
        $to_date,
	$from_date,
	$to_date,
	$from_date,
	$to_date,
	$from_date,
	$to_date,
	$from_date,
	$to_date,
	$from_date,
	$to_date
    ];

    if ($team > 0) {
        $sql .= " AND u.team = ?";
        $params[] = $team;
    }

    $sql .= " ORDER BY u.team, u.designation, u.name";

    return $this->db->query($sql, $params)->result_array();

}


public function total_call_optimised_old($sd = '', $ed = '', $agent = null)
{
    $currentDate = new DateTime();
    $curd = $currentDate->format('Y-m-d');

    $sd = !empty($sd) ? $sd . ' 00:00:00.000000' : $curd . ' 00:00:00.000000';
    $ed = !empty($ed) ? $ed . ' 23:59:59.000000' : $curd . ' 23:59:59.000000';

    $this->db->select("
        u.name AS user_name,

        COUNT(c.id) AS total_calls,

        SUM(CASE WHEN c.call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,

        SUM(CASE WHEN c.call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,

        COUNT(DISTINCT c.receiver_phone_number) AS total_unique_calls,

        SUM(CASE
                WHEN c.call_status IN ('incoming (answered)', 'outgoing (connected)')
                THEN 1
                ELSE 0
            END) AS total_connected_calls,

        SUM(CASE
                WHEN c.call_status = 'outgoing (connected)'
                THEN 1
                ELSE 0
            END) AS total_outbound_connected_calls,

        SUM(CASE
                WHEN c.call_status = 'incoming (answered)'
                THEN 1
                ELSE 0
            END) AS total_inbound_connected_calls,

        SUM(CASE
                WHEN c.call_status = 'REJECTED'
                THEN 1
                ELSE 0
            END) AS total_rejected_calls,

        SUM(CASE
                WHEN c.call_status = 'MISSED'
                THEN 1
                ELSE 0
            END) AS total_missed_calls,

        SUM(CASE
                WHEN c.call_type = 'OUTGOING'
                 AND c.call_status = 'Not Connected'
                THEN 1
                ELSE 0
            END) AS total_not_picked_client_calls,

        SUM(
            CASE
                WHEN c.duration IS NOT NULL
                 AND c.duration <> ''
                THEN c.duration
                ELSE 0
            END
        ) AS total_call_duration
    ", FALSE);

    $this->db->from('users u');

    $join = "
        u.name = c.user_name
        AND c.application_type = 'VCall'
        AND c.call_date >= ".$this->db->escape($sd)."
        AND c.call_date <= ".$this->db->escape($ed);

    $this->db->join(
        'highrise_app_call_logs_vcall c',
        $join,
        'left'
    );

    // Active users only
    $this->db->where('u.status', 1);
    $this->db->where('u.user_role !=', 'Admin');

    // Team filter
    if (!empty($agent)) {
        $this->db->where('u.team', $agent);
    }

    // Optional: Only agents
    // $this->db->where('u.role', 'Agent');

    $this->db->group_by('u.id');

    $this->db->order_by('total_connected_calls', 'DESC');
    $this->db->order_by('total_call_duration', 'DESC');
    $this->db->order_by('u.name', 'ASC');


// Get the last query string
//$lastQuery = $this->db->last_query();
//echo $lastQuery; exit();

    return $this->db->get()->result_array();
}


        public function total_call_optimised_sanjay($sd,$ed,$agent=null)
        {
            $currentDate = new DateTime();
            $curd = $currentDate->format('Y-m-d');
            if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
            if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

            $this->db->select("
                user_name,
                sender_phone_number,

                COUNT(*) AS total_calls,

                SUM(CASE WHEN call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,
                SUM(CASE WHEN call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,

                COUNT(DISTINCT receiver_phone_number) AS total_unique_calls,

                SUM(CASE 
                    WHEN call_status IN ('incoming (answered)', 'outgoing (connected)') 
                    THEN 1 ELSE 0 END) AS total_connected_calls,

                SUM(CASE 
                    WHEN call_status = 'outgoing (connected)' 
                    THEN 1 ELSE 0 END) AS total_outbound_connected_calls,

                SUM(CASE 
                    WHEN call_status = 'incoming (answered)'    
                    THEN 1 ELSE 0 END) AS total_inbound_connected_calls,

                SUM(CASE WHEN call_type = 'REJECTED' THEN 1 ELSE 0 END) AS total_rejected_calls,
                SUM(CASE WHEN call_type = 'MISSED' THEN 1 ELSE 0 END) AS total_missed_calls,
                SUM(CASE WHEN call_type = 'OUTGOING' and call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,

                SUM(CASE 
                    WHEN duration IS NOT NULL AND duration <> '' 
                    THEN duration ELSE 0 END) AS total_call_duration
            ", FALSE);

            $this->db->from('highrise_app_call_logs_vcall');

            $this->db->where('application_type', 'VCall');
            $this->db->where('call_date >=', $sd);
            $this->db->where('call_date <=', $ed);
            if(!empty($agent)){$this->db->where('user_name IN (SELECT name FROM users WHERE team = '.$this->db->escape($agent).' AND status = 1)', NULL, FALSE);}

            $this->db->group_by(['user_name', 'sender_phone_number']);
           
            $this->db->order_by('total_connected_calls', 'DESC');
            $this->db->order_by('total_call_duration', 'DESC');
            $query = $this->db->get();
            return $query->result_array();
        }

public function total_call_optimised_oooolds($sd, $ed, $agent = null)
{
    $currentDate = new DateTime();
    $curd = $currentDate->format('Y-m-d');
    if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
    if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

    $this->db->select("
        highrise_app_call_logs_vcall.user_name,         
        highrise_app_call_logs_vcall.sender_phone_number,         
        COUNT(*) AS total_calls,         
        SUM(CASE WHEN call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,         
        SUM(CASE WHEN call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,         
        COUNT(DISTINCT receiver_phone_number) AS total_unique_calls,         
        SUM(CASE WHEN call_status IN ('incoming (answered)', 'outgoing (connected)') THEN 1 ELSE 0 END) AS total_connected_calls,         
        SUM(CASE WHEN call_status = 'outgoing (connected)' THEN 1 ELSE 0 END) AS total_outbound_connected_calls,         
        SUM(CASE WHEN call_status = 'incoming (answered)' THEN 1 ELSE 0 END) AS total_inbound_connected_calls,         
        SUM(CASE WHEN call_type = 'REJECTED' THEN 1 ELSE 0 END) AS total_rejected_calls,         
        SUM(CASE WHEN call_type = 'MISSED' THEN 1 ELSE 0 END) AS total_missed_calls,         
        SUM(CASE WHEN call_type = 'OUTGOING' and call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,         
        SUM(CASE WHEN duration IS NOT NULL AND duration <> '' THEN duration ELSE 0 END) AS total_call_duration,
        users.team,
        users.user_role
    ", FALSE);

    $this->db->from('highrise_app_call_logs_vcall');
    
    // Users table ko name aur user_name ke base par join kar diya hai
    $this->db->join('users', 'users.name = highrise_app_call_logs_vcall.user_name', 'left');

    $this->db->where('application_type', 'VCall');
    $this->db->where('call_date >=', $sd);
    $this->db->where('call_date <=', $ed);
    
    if(!empty($agent)){
        $this->db->where('highrise_app_call_logs_vcall.user_name IN (SELECT name FROM users WHERE team = '.$this->db->escape($agent).' AND status = 1)', NULL, FALSE);
    }

    $this->db->group_by(['highrise_app_call_logs_vcall.user_name', 'highrise_app_call_logs_vcall.sender_phone_number']);
    $this->db->order_by('total_connected_calls', 'DESC');
    $this->db->order_by('total_call_duration', 'DESC');
    
    $query = $this->db->get();
    return $query->result_array();
}

public function total_call_optimised($sd, $ed, $agent = null)
{
    $currentDate = new DateTime();
    $curd = $currentDate->format('Y-m-d');
    if(!empty($sd)){$sd = $sd . ' 00:00:00.000000';}else{$sd = $curd . ' 00:00:00.000000';}
    if(!empty($ed)){$ed = $ed . ' 23:59:59.000000';}else{$ed = $curd . ' 23:59:59.000000';}

    $this->db->select("
        highrise_app_call_logs_vcall.user_name,         
        highrise_app_call_logs_vcall.sender_phone_number,         
        COUNT(*) AS total_calls,         
        SUM(CASE WHEN call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,         
        SUM(CASE WHEN call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,         
        COUNT(DISTINCT receiver_phone_number) AS total_unique_calls,         
        SUM(CASE WHEN call_status IN ('incoming (answered)', 'outgoing (connected)') THEN 1 ELSE 0 END) AS total_connected_calls,         
        SUM(CASE WHEN call_status = 'outgoing (connected)' THEN 1 ELSE 0 END) AS total_outbound_connected_calls,         
        SUM(CASE WHEN call_status = 'incoming (answered)' THEN 1 ELSE 0 END) AS total_inbound_connected_calls,         
        SUM(CASE WHEN call_type = 'REJECTED' THEN 1 ELSE 0 END) AS total_rejected_calls,         
        SUM(CASE WHEN call_type = 'MISSED' THEN 1 ELSE 0 END) AS total_missed_calls,         
        SUM(CASE WHEN call_type = 'OUTGOING' and call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,         
        SUM(CASE WHEN duration IS NOT NULL AND duration <> '' THEN duration ELSE 0 END) AS total_call_duration,
        users.team,
        users.user_role
    ", FALSE);

    $this->db->from('highrise_app_call_logs_vcall');
    
    // Users table ko join kiya hai
    $this->db->join('users', 'users.name = highrise_app_call_logs_vcall.user_name', 'left');

    $this->db->where('application_type', 'VCall');
    $this->db->where('call_date >=', $sd);
    $this->db->where('call_date <=', $ed);
    
    // --- YAHAN SE UID HATA DIYA HAI ---
    // Ab table ke andar jo bhi number active (status = 1) hain, 
    // woh receiver phone number wali list me match ho kar report se puri tarah exclude ho jayenge.
    $this->db->where("receiver_phone_number NOT IN (SELECT number FROM presonalNumberWipeOut WHERE status = 1)", NULL, FALSE);
    
    // Agar sender number par bhi yeh rule lagana ho toh niche wali line bhi add kar sakte hain:
    // $this->db->where("sender_phone_number NOT IN (SELECT number FROM presonalNumberWipeOut WHERE status = 1)", NULL, FALSE);

    if(!empty($agent)){
        $this->db->where('highrise_app_call_logs_vcall.user_name IN (SELECT name FROM users WHERE team = '.$this->db->escape($agent).' AND status = 1)', NULL, FALSE);
    }

    $this->db->group_by(['highrise_app_call_logs_vcall.user_name', 'highrise_app_call_logs_vcall.sender_phone_number']);
    $this->db->order_by('total_connected_calls', 'DESC');
    $this->db->order_by('total_call_duration', 'DESC');
    
    $query = $this->db$this->db->get();
    return $query->result_array();
}
public function get_team_managers()
{
    $this->db->select('team, name');
    $this->db->from('users');
    $this->db->where('user_role', 'Manager');
    $this->db->where('status', 1);
    $query = $this->db->get();
    
    $managers = [];
    foreach($query->result_array() as $row){
        $managers[$row['team']] = $row['name'];
    }
    return $managers;
}
		 public function daily_performance_report($sd = '', $ed = '', $agent = null)
		 {
			$currentDate = new DateTime();
			$curd = $currentDate->format('Y-m-d');

			$sd = !empty($sd) ? $sd . ' 00:00:00.000000' : $curd . ' 00:00:00.000000';
			$ed = !empty($ed) ? $ed . ' 23:59:59.000000' : $curd . ' 23:59:59.000000';

			$this->db->select("
				u.name AS user_name,

				COUNT(c.id) AS total_calls,

				SUM(CASE WHEN c.call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,

				SUM(CASE WHEN c.call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,

				COUNT(DISTINCT c.receiver_phone_number) AS total_unique_calls,

				SUM(CASE
						WHEN c.call_status IN ('incoming (answered)', 'outgoing (connected)')
						THEN 1
						ELSE 0
					END) AS total_connected_calls,

				SUM(CASE
						WHEN c.call_status = 'outgoing (connected)'
						THEN 1
						ELSE 0
					END) AS total_outbound_connected_calls,

				SUM(CASE
						WHEN c.call_status = 'incoming (answered)'
						THEN 1
						ELSE 0
					END) AS total_inbound_connected_calls,

				SUM(CASE
						WHEN c.call_status = 'REJECTED'
						THEN 1
						ELSE 0
					END) AS total_rejected_calls,

				SUM(CASE
						WHEN c.call_status = 'MISSED'
						THEN 1
						ELSE 0
					END) AS total_missed_calls,

				SUM(CASE
						WHEN c.call_type = 'OUTGOING'
						 AND c.call_status = 'Not Connected'
						THEN 1
						ELSE 0
					END) AS total_not_picked_client_calls,

				SUM(
					CASE
						WHEN c.duration IS NOT NULL
						 AND c.duration <> ''
						THEN c.duration
						ELSE 0
					END
				) AS total_call_duration
			", FALSE);

			$this->db->from('users u');

			$join = "
				u.name = c.user_name
				AND c.application_type = 'VCall'
				AND c.call_date >= ".$this->db->escape($sd)."
				AND c.call_date <= ".$this->db->escape($ed);

			$this->db->join(
				'highrise_app_call_logs_vcall c',
				$join,
				'left'
			);

			// Active users only
			$this->db->where('u.status', 1);

			// Team filter
			if (!empty($agent)) {
				$this->db->where('u.team', $agent);
			}

			// Optional: Only agents
			// $this->db->where('u.role', 'Agent');

			$this->db->group_by('u.id');

			$this->db->order_by('total_connected_calls', 'DESC');
			$this->db->order_by('total_call_duration', 'DESC');
			$this->db->order_by('u.name', 'ASC');

			return $this->db->get()->result_array();
        }


		public function get_never_logged_in_agents($sd = '', $ed = '', $team_id = null)
    {
        $currentDate = new DateTime();
        $curd = $currentDate->format('Y-m-d');

        $sd = !empty($sd) ? $sd . ' 00:00:00.000000' : $curd . ' 00:00:00.000000';
        $ed = !empty($ed) ? $ed . ' 23:59:59.000000' : $curd . ' 23:59:59.000000';

        $this->db->select("            u.name AS user_name,            u.team AS team,            u.mobile AS sender_phone_number,            COUNT(c.id) AS total_calls,            SUM(CASE WHEN c.call_type = 'OUTGOING' THEN 1 ELSE 0 END) AS total_outbound_calls,            SUM(CASE WHEN c.call_type = 'INCOMING' THEN 1 ELSE 0 END) AS total_inbound_calls,            COUNT(DISTINCT c.receiver_phone_number) AS total_unique_calls,            SUM(CASE WHEN c.call_status IN ('incoming (answered)', 'outgoing (connected)') THEN 1 ELSE 0 END) AS total_connected_calls,            SUM(CASE WHEN c.call_status = 'REJECTED' THEN 1 ELSE 0 END) AS total_rejected_calls,            SUM(CASE WHEN c.call_status = 'MISSED' THEN 1 ELSE 0 END) AS total_missed_calls,            SUM(CASE WHEN c.call_type = 'OUTGOING' AND c.call_status = 'Not Connected' THEN 1 ELSE 0 END) AS total_not_picked_client_calls,            SUM(CASE WHEN c.duration IS NOT NULL AND c.duration <> '' THEN c.duration ELSE 0 END) AS total_call_duration        ", FALSE);

        $this->db->from('users u');

        $join = "            u.name = c.user_name            AND c.application_type = 'VCall'            AND c.call_date >= ".$this->db->escape($sd)."            AND c.call_date <= ".$this->db->escape($ed);

        $this->db->join('highrise_app_call_logs_vcall c', $join, 'left');

        // Active users only (Admin restriction removed so managers can appear)
        $this->db->where('u.status', 1);

        // Team filter if provided
        if (!empty($team_id)) {
            $this->db->where('u.team', $team_id);
        }

        $this->db->group_by('u.id');
    
        // Jin users ke total calls 0 hain
        $this->db->having('COUNT(c.id)', 0);

        $this->db->order_by('u.name', 'ASC');

        return $this->db->get()->result_array();
    }

/****End*****/
    }?>
