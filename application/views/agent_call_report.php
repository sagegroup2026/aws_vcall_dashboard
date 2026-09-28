<?php
$sess_name = $this->session->userdata("name");

// Logged-in user ki team aur role find karenge $total array se agar session me nahi hai
$logged_user_team = '';
$logged_user_role = '';
foreach($total as $row) {
    if($row['user_name'] == $sess_name) {
        $logged_user_team = $row['team'];
        $logged_user_role = $row['user_role'];
        break;
    }
}

// Data ko team-wise group kar lenge
$team_groups = [];
foreach($total as $cds) {
    $t_id = $cds['team'];
    $team_groups[$t_id][] = $cds;
}
?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"><?php echo $hd; ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="dashboard<?php echo '?' . $_SERVER['QUERY_STRING']; ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo $hd; ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Welcoming / Greeting Section -->
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">
                                <?php 
                                date_default_timezone_set("Asia/Kolkata");
                                if (date("a") == 'am') { echo 'Good Morning'; } 
                                elseif (date("h") >= '00' && date("h") <= '04') { echo 'Good Afternoon'; } 
                                else { echo 'Good Evening'; } 
                                ?>, <?php echo $sess_name; ?>!
                            </h4>
                            <p class="text-muted mb-0">Here's what your teams have done today.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Wise Tables Loop Start -->
            <?php 
            foreach($team_groups as $team_id => $agents): 
                // Agar user Manager hai, toh sirf apni team ka table dikhaye, baaki skip karde
                if($logged_user_role == 'Manager' && $team_id != $logged_user_team) {
                    continue; 
                }
            ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h4 class="card-title mb-0">
                                    <?php echo "Team " . $team_id.$user_name . " Report &mdash; " . $shd . ' (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?>
                                </h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table role="grid" class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark" style="top: 0; position: sticky; z-index: 10;">
                                            <tr>
                                                <th style="width:90px;">Sr. No.</th>
                                                <th style="width:250px;">DGM / Agent Details</th>
                                                <th>Total<br>Calls</th>
                                                <th>Outbound<br>Calls</th>
                                                <th>Inbound<br>Calls</th>
                                                <th>Unique<br>Calls</th>
                                                <th>Connected<br>Calls</th>
                                                <th>Rejected<br>Calls</th>
                                                <th>Missed<br>Calls</th>
                                                <th>Calls Not Picked<br>By Clients</th>
                                                <th>Total<br>Call Duration</th>
                                                <th>Average<br>Call Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                            $x = 1;
                                            foreach($agents as $cds){
                                           ?>
                                            <tr class="<?php if($x <= 3){echo 'top-performer';} ?>">
                                                <td class="fw-medium"><?php echo $x; $x++; ?></td>
                                                <td><?php echo "<a href='total-calls?agent=" . $cds['user_name'] . "&sd=" . $usd . "&ed=" . $ued . "' target='_blank'>" . $cds['user_name'] . "</a><br>" . $cds['sender_phone_number']; ?></td>
                                                <td><?php echo $cds['total_calls']; ?></td>
                                                <td><?php echo $cds['total_outbound_calls']; ?></td>
                                                <td><?php echo $cds['total_inbound_calls']; ?></td>
                                                <td><?php echo $cds['total_unique_calls']; ?></td>
                                                <td><?php echo $cds['total_connected_calls']; ?></td>
                                                <td><?php echo $cds['total_rejected_calls']; ?></td>
                                                <td><?php echo $cds['total_missed_calls']; ?></td>
                                                <td><?php echo $cds['total_not_picked_client_calls']; ?></td>
                                                <td>
                                                    <?php 
                                                        $d = $cds['total_call_duration']; 
                                                        $h = floor($d / 3600); 
                                                        $m = floor(($d - ($h * 3600)) / 60); 
                                                        $rs = $d % 60; 
                                                        echo sprintf('%02d:%02d:%02d', $h, $m, $rs); 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($cds['total_connected_calls'] != '0'){
                                                            $d = $cds['total_call_duration'] / $cds['total_connected_calls']; 
                                                            $h = floor($d / 3600); 
                                                            $m = floor(($d - ($h * 3600)) / 60); 
                                                            $rs = $d % 60; 
                                                            echo sprintf('%02d:%02d:%02d', $h, $m, $rs);
                                                        } else {
                                                            echo '00:00:00';
                                                        } 
                                                    ?>
                                                </td>
                                            </tr>
                                           <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- Team Wise Tables Loop End -->

        </div><!-- container-fluid -->
    </div>
</div>

<link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
