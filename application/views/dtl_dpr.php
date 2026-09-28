<?php
$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if($uri[2]=='team-call-report'){$hd = 'Team Wise Calls Report'; $shd = 'Team Wise Calls Details';}
// elseif($uri[2]=='outbound-calls'){$cno = $out_calls; $cdetails = $out_details; $hd = 'Total Outbound Calls'; $shd = 'Total Calls - Outbound';}
// elseif($uri[2]=='inbound-calls'){$cno = $in_calls; $cdetails = $in_details; $hd = 'Total Inbound Calls'; $shd = 'Total Calls - Inbound';}
// elseif($uri[2]=='outbound-manual'){$cno = $out_man_calls; $cdetails = $out_man_details; $hd = 'Total Outbound Manual Calls'; $shd = 'Total Calls - Outbound Manual';}
// else{echo 'vaidya';}
// // exit();

$sess_nm = $this->session->userdata("name");
// $agent = htmlspecialchars($_GET['agent']);
$rtp = htmlspecialchars($_GET['rtype']);
$usd = htmlspecialchars($_GET['sd']);
$ued = htmlspecialchars($_GET['ed']);
// if($agent == ""){$user = $sess_nm;}
// elseif(!empty($agent)){$user = $agent;}

if($rtp == 'dsv'){$rtyp = 'Direct Site Visits Details';}
elseif($rtp == 'isv'){$rtyp = 'Indirect Site Visits Details';}
elseif($rtp == 'hv'){$rtyp = 'Home Visits Details';}
elseif($rtp == 'cv'){$rtyp = 'Corporate Visits Details';}
elseif($rtp == 'add'){$rtyp = 'Admission Details';}
elseif($rtp == 'ip'){$rtyp = 'IP Details';}

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
                        <h4 class="mb-sm-0"><?php echo $rtyp; ?> Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="dashboard<?php echo '?' . $_SERVER['QUERY_STRING']; ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo $rtyp; ?></li>
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
                            <p class="text-muted mb-0">Here's what's <?php if($this->session->userdata("name") == $agent){echo "you've";}elseif($agent == ""){echo "your whole team has";}else{echo "<b>" . $agent . "</b> has";} ?> done today.</p>
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
					  var url = "detailed-dpr?rtype=<?php echo $rtp; ?>&sd=" + sd.value + "&ed=" + ed.value;

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
            <!--end row-->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"><?php echo $rtyp . ' Report (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?></h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="table-responsive">

				    <?php if($rtp == 'hv'){ ?>
                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>DGM / Agent Details</th>
                                                <th>Customer Details</th>
                                                <th>Discussion</th>
                                                <th>Co-Fellow</th>
                                                <th>Visit Pic</th>
                                                <th>Location</th>
                                                <th>Visit Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

					    <tr>
						<td>1</td>
						<td>Abhishek<br>7897897897</td>
						<td>Vinay Lodhi</td>
						<td>Ashish discussed</td>
						<td>Ashish</td>
						<td><img src='HomeVisit/manju-ingle2026-08-21_164624.005370samardha_16-46-22.webp' title=''></td>
						<td>Ayodhya Nagar</td>
						<td>01-08-2026</td>
						<td><a href='#'>Delete</a></td>
					    </tr>
                                            <?php $x = 1; if(is_array($total) || is_object($total)){$t = count($total); $ts = $t - 3; foreach($total as $cd){ ?>
                                            <tr class="<?php if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
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
				    <?php } ?>

                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- container-fluid -->
    </div><!-- End Page-content -->
