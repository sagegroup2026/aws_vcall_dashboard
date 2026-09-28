<?php
$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if($uri[2]=='team-call-report'){$hd = 'Team Wise Calls Report'; $shd = 'Team Wise Calls Details';}
elseif($uri[2]=='agent-call-report'){$hd = 'Agent Wise Calls Report'; $shd = 'Agent Wise Calls Details';}
// elseif($uri[2]=='outbound-calls'){$cno = $out_calls; $cdetails = $out_details; $hd = 'Total Outbound Calls'; $shd = 'Total Calls - Outbound';}
// elseif($uri[2]=='inbound-calls'){$cno = $in_calls; $cdetails = $in_details; $hd = 'Total Inbound Calls'; $shd = 'Total Calls - Inbound';}
// elseif($uri[2]=='outbound-manual'){$cno = $out_man_calls; $cdetails = $out_man_details; $hd = 'Total Outbound Manual Calls'; $shd = 'Total Calls - Outbound Manual';}
// else{echo 'vaidya';}
// // exit();

$sess_nm = $this->session->userdata("name");
// $agent = htmlspecialchars($_GET['agent']);
$usd = htmlspecialchars($_GET['sd']);
$ued = htmlspecialchars($_GET['ed']);
// if($agent == ""){$user = $sess_nm;}
// elseif(!empty($agent)){$user = $agent;}
?>

<script>
    window.onload = function() {
        // Get the URL search parameters
        const urlParams = new URLSearchParams(window.location.search);

        // Get the value of a specific parameter (e.g., 'category')
        const selectedValue = urlParams.get('agent'); // Replace 'category' with your parameter name

        // Get a reference to your dropdown element
        const dropdown = document.getElementById('myagent'); // Replace 'myDropdown' with your dropdown's ID

        if (selectedValue === '') {
            selectedValue.value = 'Select Agent';
        } else {
            dropdown.value = selectedValue;
        }
    };
</script>

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
                            <h4 class="fs-16 mb-1"><?php date_default_timezone_set("Asia/Kolkata");
                                                    if (date("a") == 'am') {
                                                        echo 'Good Morning';
                                                    } elseif (date("h") >= '00' && date("h") <= '04') {
                                                        echo 'Good Afternoon';
                                                    } else {
                                                        echo 'Good Evening';
                                                    } ?>, <?php if($this->session->userdata("name") == $agent){echo $agent;}else{echo $this->session->userdata("name");} ?>!</h4>
                            <p class="text-muted mb-0">Here's what's <?php if($this->session->userdata("name") == $agent){echo "you've";}elseif($agent == ""){echo "your whole agents have";}else{echo "<b>" . $agent . "</b> has";} ?> done today.</p>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                    <div class="input-group">
                                       <?php
                                          $currentDate = new DateTime();
                                          $curd = $currentDate->format('Y-m-d');
                                       ?>
                                       <div>
                                          <label for="from" class="form-label">Date From</label>
                                          <input type="date" id="from" class="form-control border-0 dash-filter-picker shadow" onchange="myFunction()" max="<?php echo $ued; ?>" value="<?php if(!empty($usd)){echo $usd;}else{echo $curd;} ?>" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y">
                                       </div>
                                       <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                       <div>
                                          <label for="to" class="form-label">Date To</label>
                                          <input type="date" id="to" class="form-control border-0 dash-filter-picker shadow" onchange="myFunction()" value="<?php if(!empty($ued)){echo $ued;}else{echo $curd;} ?>" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y">
                                       </div>
                                    </div>
                                 </div>
                                 <script>
                                    function myFunction(){
                                    //    var ag = document.getElementById('myagent');
                                       var sd = document.getElementById('from');
                                       var ed = document.getElementById('to');

                                    //    if(ag.value == 'Select Agent'){ag.value = ''}

                                       if(sd.value > ed.value){
                                          alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                          sd.focus();
                                       }
                                       else{
                                          var url = "agent-call-report?&sd=" + sd.value + "&ed=" + ed.value;

                                          location.replace(url);
                                       }
                                    }
                                 </script>


                                <script>
                                    document.querySelector('form').addEventListener('submit', function(e) {
                                        let from = document.getElementById('from').value;
                                        let to = document.getElementById('to').value;

                                        if (from > to) {
                                            alert('From Date cannot be greater than To Date');
                                            e.preventDefault();
                                        }
                                    });
                                </script>
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                    <!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!-- end row -->

                <!-- Test Table Start -->
                <div class="row">
                        <div class="col-lg-12">
                                <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0"><?php echo $shd . ' (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?></h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                                <div id="table-fixed-header">
                                                        <div role="complementary" class="gridjs gridjs-container" style="width: 100%;">
                                                                <div class="gridjs-wrapper" style="height: 700px;">
                                                                        <table role="grid" class="gridjs-table" style="height: 700px;">
                                                                                <thead class="gridjs-thead" style="top: 0; position: sticky; z-index: 10; background: #fff;">
                                                                                        <tr class="gridjs-tr">

			    <th data-column-id="sr_no" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0" style="width:90px;">
                                <div class="gridjs-th-content">Sr. No.</div>
<!--                                <button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button>-->
                            </th>
                            <th data-column-id="agent-details" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0" style="width:250px;">
                                <div class="gridjs-th-content">DGM / Agent Details</div>
                            </th>
                            <th data-column-id="total-calls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Total<br>Calls</div>
                            </th>
                            <th data-column-id="ocalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Outbound<br>Calls</div>
                            </th>
                            <th data-column-id="icalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Inbound<br>Calls</div>
                            </th>
                            <th data-column-id="ucalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Unique<br>Calls</div>
                            </th>
                            <th data-column-id="ccalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Connected<br>Calls</div>
                            </th>
                            <th data-column-id="rcalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Rejected<br>Calls</div>
                            </th>
                            <th data-column-id="mcalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Missed<br>Calls</div>
                            </th>
                            <th data-column-id="not_pick_client" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Calls Not Picked<br>By Clients</div>
                            </th>
                            <th data-column-id="duration" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Total<br>Call Duration</div>
                            </th>
                            <th data-column-id="avg_duration" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                <div class="gridjs-th-content">Average<br>Call Duration</div>
                            </th>

                                                                                        </tr>
                                                                                </thead>

                                                                                <tbody class="gridjs-tbody">
										   <?php
											$x = 1;
											$t = count($total);
											$ts = $t - 3;
											echo "<pre>";
											print_r($total);
											foreach($total as $cds){
										   ?>
                                                                                        <tr class="gridjs-tr <?php if($x <= 3){echo 'top-performer';} ?>"> <!--elseif($x > $ts){echo 'low-performer';} ?>">-->
                                                                                                <td data-column-id="" class="gridjs-td"><?php echo $x; $x++; ?></td>
                                                                                                <td data-column-id="" class="gridjs-td"><?php echo "<a href='total-calls?agent=" . $cds['user_name'] . "&sd=" . $usd . "&ed=" . $ued . "' target='_blank'>" . $cds['user_name'] . "</a><br>" . $cds['sender_phone_number']; ?></td>
                                                                                                <td data-column-id="" class="gridjs-td"><?php echo $cds['total_calls']; ?></td>
                                                                                                <td data-column-id="" class="gridjs-td"><?php echo $cds['total_outbound_calls']; ?></td>
                                                                                                <td data-column-id="" class="gridjs-td"><?php echo $cds['total_inbound_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php echo $cds['total_unique_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php echo $cds['total_connected_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php echo $cds['total_rejected_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php echo $cds['total_missed_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php echo $cds['total_not_picked_client_calls']; ?></td>
												<td data-column-id="" class="gridjs-td"><?php $d = $cds['total_call_duration']; $h = floor($d / 3600); $m = floor(($d - ($h * 3600)) / 60); $rs = $d % 60; $t = sprintf('%02d:%02d:%02d', $h, $m, $rs); echo $t; ?></td>
												<td data-column-id="" class="gridjs-td"><?php if($cds['total_connected_calls'] != '0'){$d = $cds['total_call_duration'] / $cds['total_connected_calls']; $h = floor($d / 3600); $m = floor(($d - ($h * 3600)) / 60); $rs = $d % 60; $t = sprintf('%02d:%02d:%02d', $h, $m, $rs); echo $t;} else{echo '00:00:00';} ?></td>
                                                                                        </tr>
										   <?php } ?>
                                                                                </tbody>
                                                                        </table>
                                                                </div>

                                                                <div class="gridjs-footer">
                                                                        <div class="gridjs-pagination">
                                                                                <div role="status" aria-live="polite" class="gridjs-summary" title="Page 1 of 1">Showing <b>1</b> to <b>10</b> of <b><?php echo count($total); ?></b> results</div>
<!--                                                                                <div class="gridjs-pages">
                                                                                        <button tabindex="0" role="button" disabled="" title="Previous" aria-label="Previous" class="">Previous</button>
                                                                                        <button tabindex="0" role="button" class="gridjs-currentPage" title="Page 1" aria-label="Page 1">1</button>
                                                                                        <button tabindex="0" role="button" disabled="" title="Next" aria-label="Next" class="">Next</button>
                                                                                </div>-->
                                                                        </div>
                                                                </div>
                                                                <div id="gridjs-temp" class="gridjs-temp"></div>
                                                        </div>
                                                </div>
                                        </div><!-- end card-body -->
                                </div>
                                <!-- end card -->
                        </div>
                        <!-- end col -->
                </div>

                <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
<!-- Test Table End -->



<!--            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><?php echo $shd . ' (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?></h4>
                        </div><!-- end card header --
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="table-responsive">

                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>DGM / Agent Details</th>
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

                                            <?php $x = 1;
                                            if(is_array($total) || is_object($total)){
                                                $t = count($total);
                                                $ts = $t - 3;
                                                foreach($total as $cd){ ?>
                                            <tr  class="<?php if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                <td class="fw-medium"><?php echo $x; $x++; ?></td>
                                                <td><?php echo "<a href='total-calls?agent=" . $cd['user_name'] . "&sd=" . $usd . "&ed=" . $ued . "' target='_blank'>" . $cd['user_name'] . '</a><br>' . $cd['sender_phone_number']; ?></td>
                                                <td><?php echo $cd['total_calls']; ?></td>
                                                <td><?php echo $cd['total_outbound_calls']; ?></td>
                                                <td><?php echo $cd['total_inbound_calls']; ?></td>
                                                <td><?php echo $cd['total_unique_calls']; ?></td>
                                                <td><?php echo $cd['total_connected_calls']; ?></td>
                                                <td><?php echo $cd['total_rejected_calls']; ?></td>
                                                <td><?php echo $cd['total_missed_calls']; ?></td>
                                                <td><?php echo $cd['total_not_picked_client_calls']; ?></td>
                                                <td><?php $d = $cd['total_call_duration']; $h = floor($d / 3600); $m = floor(($d - ($h * 3600)) / 60); $rs = $d % 60; $t = sprintf('%02d:%02d:%02d', $h, $m, $rs); echo $t; ?></td>
                                                <td><?php if($cd['total_connected_calls'] != '0'){$d = $cd['total_call_duration'] / $cd['total_connected_calls']; $h = floor($d / 3600); $m = floor(($d - ($h * 3600)) / 60); $rs = $d % 60; $t = sprintf('%02d:%02d:%02d', $h, $m, $rs); echo $t;}else{echo '00:00:00';}?></td>
                                            </tr>
                                            <?php }}else{echo '<p>No records found.</p>';} ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div><!-- end card-body --
                    </div><!-- end card --
                </div><!-- end col --
            </div><!-- end row -->

        </div><!-- container-fluid -->
    </div><!-- End Page-content -->
