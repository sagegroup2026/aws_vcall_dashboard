<?php
$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if($uri[2]=='daily-performance-report'){$hd = 'Daily Performance'; $shd = 'Daily Team Performance Report';}
elseif($uri[2]=='agent-call-report'){$hd = 'Agent Wise Calls Report'; $shd = 'Agent Wise Calls Details';}

// else{echo 'vaidya';}
// // exit();

$sess_nm = $this->session->userdata("name");
$agent = htmlspecialchars($_GET['agent']);
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
                                          var url = "daily-performance-report?&sd=" + sd.value + "&ed=" + ed.value;

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
                                                    </th>
                                                    <th data-column-id="agent-details" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0" style="width:250px;">
                                                        <div class="gridjs-th-content">DGM / Agent Details</div>
                                                    </th>
                                                    <th data-column-id="total-calls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Direct Site<br>Visit</div>
                                                    </th>
                                                    <th data-column-id="ocalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Indirect Site<br>Visit</div>
                                                    </th>
                                                    <th data-column-id="icalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Home<br>Visit</div>
                                                    </th>
                                                    <th data-column-id="ucalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Corporate<br>Visit</div>
                                                    </th>
<!--                                                    <th data-column-id="ccalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">New Leads</div>
                                                    </th>
                                                    <th data-column-id="rcalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Cust. F/W</div>
                                                    </th>
                                                    <th data-column-id="mcalls" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">SM Leads</div>
                                                    </th>
                                                    <th data-column-id="not_pick_client" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">SM F/W</div>
                                                    </th>-->
                                                    <th data-column-id="duration" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">Admission</div>
                                                    </th>
                                                    <th data-column-id="avg_duration" class="gridjs-th gridjs-th-sort gridjs-th-fixed" tabindex="0">
                                                        <div class="gridjs-th-content">IP</div>
                                                    </th>

                                                </tr>
                                            </thead>

					    <tbody class="gridjs-tbody">
					      <?php
						$x = 1;
						//$t = count($total);
						//$ts = $t - 3;

						foreach($total as $cds){
					      ?>
                                                <tr class="gridjs-tr" style="">
                                                    <td data-column-id="" class="gridjs-td"><?php echo $x; $x++; ?></td>
                                                    <td data-column-id="" class="gridjs-td"><?php if($cds['designation']=='DGM'){echo "<b>" . $cds['name'] . " - (". $cds['designation'] .")</b>";} else{echo $cds['name'];} ?></td>
                                                    <td data-column-id="" class="gridjs-td"><?php if($cds['total_dsite_visit']=='0'){echo $cds['total_dsite_visit'];}else{echo "<a href='detailed-dpr?rtype=dsv&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_dsite_visit'] . "</b></a>";} ?></td>
                                                    <td data-column-id="" class="gridjs-td"><?php if($cds['total_ind_site_visit']=='0'){echo $cds['total_ind_site_visit'];}else{echo "<a href='detailed-dpr?rtype=isv&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_ind_site_visit'] . "</b></a>";} ?></td>
                                                    <td data-column-id="" class="gridjs-td"><?php if($cds['total_home_visit']=='0'){echo $cds['total_home_visit'];}else{echo "<a href='detailed-dpr?rtype=hv&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_home_visit'] . "<b></a>";} ?></td>
						    <td data-column-id="" class="gridjs-td"><?php if($cds['total_corp_visit']=='0'){echo $cds['total_corp_visit'];}else{echo "<a href='detailed-dpr?rtype=cv&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_corp_visit'] . "</b></a>";} ?></td>
<!--						    <td data-column-id="" class="gridjs-td"><?php echo 0; ?></td>
						    <td data-column-id="" class="gridjs-td"><?php echo 0; ?></td>
						    <td data-column-id="" class="gridjs-td"><?php echo 0; ?></td>
						    <td data-column-id="" class="gridjs-td"><?php echo 0; ?></td>-->
						    <td data-column-id="" class="gridjs-td"><?php if($cds['total_admission']=='0'){echo $cds['total_admission'];}else{echo "<a href='detailed-dpr?rtype=add&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_admission'] . "</b></a>";} ?></td>
						    <td data-column-id="" class="gridjs-td"><?php if($cds['total_ip']=='0'){echo $cds['total_ip'];}else{echo "<a href='detailed-dpr?rtype=ip&sd=" . $usd . "&ed=" . $ued . "' target='_blank'><b>" . $cds['total_ip'] . "</b></a>";} ?></td>
                                                </tr>
					      <?php } ?>
					   </tbody>

					</table>
				    </div>
				</div>
			    </div>
			</div>
			<link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">

		    </div>
		</div>
	   </div>
