<?php
$sess_name = $this->session->userdata("name");

// Logged-in user ki team aur role find karenge
$logged_user_team = '';
$logged_user_role = '';
foreach($total as $row) {
    if(isset($row['user_name']) && $row['user_name'] == $sess_name) {
        $logged_user_team = $row['team'];
        $logged_user_role = $row['user_role'];
        break;
    }
}

// 1. Call logs se team-wise data group kar lenge
$team_groups = [];
foreach($total as $cds) {
    $t_id = $cds['team'];
    $team_groups[$t_id][] = $cds;
}

// 2. Ensure karenge ki agar kisi manager ki team me call nahi hui, tab bhi wo team list me aaye
$all_managers = isset($managers) ? $managers : [];
foreach($all_managers as $t_id => $m_name) {
    if(!isset($team_groups[$t_id])) {
        $team_groups[$t_id] = []; 
    }
}
ksort($team_groups);
?>

<style>
    th, td{text-align: center;vertical-align: middle;}
    .la{text-align: left !important;}
    .top-performer {
        background-color: #d4edda; /* green */
        font-weight: bold;
    }
    .low-performer {
        background-color: #f8d7da; /* red */
    }
    /* Sorting ke liye pointer cursor */
    th.sortable {
        cursor: pointer;
    }
    th.sortable:hover {
        background-color: #343a40;
    }
</style>

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

            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">
                                <?php 
                                date_default_timezone_set("Asia/Kolkata");
                                if (date("a") == 'am') {
                                    echo 'Good Morning';
                                } elseif (date("h") >= '00' && date("h") <= '04') {
                                    echo 'Good Afternoon';
                                } else {
                                    echo 'Good Evening';
                                } 
                                ?>, <?php echo $sess_name; ?>!
                            </h4>
                            <p class="text-muted mb-0">Here's what your teams have done today.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Wise Tables Loop Start -->
            <?php 
            $table_counter = 1;
            foreach($team_groups as $team_id => $agents): 
                // Agar user Manager hai, toh sirf apni team ka table dikhaye, baaki skip karde
                if($logged_user_role == 'Manager' && $team_id != $logged_user_team) {
                    continue; 
                }

                $manager_name = isset($all_managers[$team_id]) ? $all_managers[$team_id] : 'N/A';
                $table_id = "sortableTable_" . $table_counter;
            ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <?php echo "Team " . $team_id . " Report &mdash; Manager: <span class='text-primary'>" . $manager_name . "</span>"; ?>
                                </h4>
                                <span class="text-muted font-size-12">
                                    <?php echo $shd . ' (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?>
                                </span>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="<?php echo $table_id; ?>" role="grid" class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark" style="top: 0; position: sticky; z-index: 10;">
                                            <tr>
                                                <th style="width:90px;">Sr. No.</th>
                                                <th class="sortable" style="width:250px;" onclick="sortTable(this, 1)">DGM / Agent Details ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 2)">Total<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 3)">Outbound<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 4)">Inbound<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 5)">Unique<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 6)">Connected<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 7)">Rejected<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 8)">Missed<br>Calls ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 9)">Calls Not Picked<br>By Clients ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 10)">Total<br>Call Duration ↕</th>
                                                <th class="sortable" onclick="sortTable(this, 11)">Average<br>Call Duration ↕</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                            if(!empty($agents)) {
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
                                           <?php 
                                                }
                                            } else {
                                                echo '<tr><td colspan="12" class="text-center text-muted">No calls recorded for this team today.</td></tr>';
                                            }
                                           ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>
            <?php 
                $table_counter++;
                endforeach; 
            ?>
            <!-- Team Wise Tables Loop End -->

        </div><!-- container-fluid -->
    </div>
</div>

<script>
function sortTable(thElement, colIndex) {
    let table = thElement.closest('table');
    let tbody = table.querySelector('tbody');
    let rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Agar "No calls recorded" message hai toh sort mat karo
    if (rows.length === 1 && rows[0].querySelector('td').getAttribute('colspan')) {
        return;
    }

    let asc = thElement.getAttribute('data-order') !== 'asc';
    
    // Sabhi headers se arrows reset kar do
    table.querySelectorAll('th').forEach(th => th.removeAttribute('data-order'));
    thElement.setAttribute('data-order', asc ? 'asc' : 'desc');

    rows.sort((rowA, rowB) => {
        let cellA = rowA.querySelectorAll('td')[colIndex].innerText.trim();
        let cellB = rowB.querySelectorAll('td')[colIndex].innerText.trim();

        let valA = isNaN(cellA) ? cellA : parseFloat(cellA);
        let valB = isNaN(cellB) ? cellB : parseFloat(cellB);

        if (valA > valB) return asc ? 1 : -1;
        if (valA < valB) return asc ? -1 : 1;
        return 0;
    });

    // Rows ko dobara append karo table me aur Sr. No. update karo
    rows.forEach((row, index) => {
        row.querySelectorAll('td')[0].innerText = index + 1;
        tbody.appendChild(row);
    });
}
</script>
