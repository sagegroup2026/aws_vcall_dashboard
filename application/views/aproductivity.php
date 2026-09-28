<?php
            $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

            if($uri[2]=='agent-productivity'){$cno = $calls; $cdetails = $call_details; $hd = 'Agent Productivity Matrix'; $shd = 'Agent Productivity Matrix';}
            // elseif($uri[2]=='outbound-calls'){$cno = $out_calls; $cdetails = $out_details; $hd = 'Total Outbound Calls'; $shd = 'Total Calls - Outbound';}
            // elseif($uri[2]=='inbound-calls'){$cno = $in_calls; $cdetails = $in_details; $hd = 'Total Inbound Calls'; $shd = 'Total Calls - Inbound';}
            // elseif($uri[2]=='outbound-manual'){$cno = $out_man_calls; $cdetails = $out_man_details; $hd = 'Total Outbound Manual Calls'; $shd = 'Total Calls - Outbound Manual';}
            // elseif($uri[2]=='teams'){$tdetails = $teams; $hd = 'All Teams'; $shd = 'All Teams & Their Heads';}
            // // exit();

            $sess_nm = $this->session->userdata("name");
            $agent = htmlspecialchars($_GET['agent']);
            if($agent == ""){$user = $sess_nm;}
            elseif(!empty($agent)){$user = $agent;}
        ?>
<?php
   $sess_nm = $this->session->userdata("name");
   $agent = htmlspecialchars($_GET['agent']);
   $usd = htmlspecialchars($_GET['sd']);
   $ued = htmlspecialchars($_GET['ed']);
   if($agent == ""){$user = $sess_nm;}
   elseif(!empty($agent)){$user = $agent;}
?>
        <script>
            window.onload = function() {
                // Get the URL search parameters
                const urlParams = new URLSearchParams(window.location.search);

                // Get the value of a specific parameter (e.g., 'category')
                const selectedValue = urlParams.get('agent'); // Replace 'category' with your parameter name

                // Get a reference to your dropdown element
                const dropdown = document.getElementById('myagent'); // Replace 'myDropdown' with your dropdown's ID

                if(selectedValue === ''){selectedValue.value = 'Select Agent';}else{dropdown.value = selectedValue;}
            };
        </script>

        <style>
            th, td {
                text-align: center;
                vertical-align: middle;
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
                                    <h4 class="fs-16 mb-1"><?php date_default_timezone_set("Asia/Kolkata"); if(date("a")=='am'){echo 'Good Morning';}elseif(date("h") >= '00' && date("h") <= '04'){echo 'Good Afternoon';}else{echo 'Good Evening';} ?>, <?php if($this->session->userdata("name") == $agent){echo $agent;}else{echo $this->session->userdata("name");} ?>!</h4>
                                    <!-- <p class="text-muted mb-0">Here's what's <?php if($this->session->userdata("name") == $agent){echo "you've";}elseif($agent == ""){echo "your whole team has";}else{echo "<b>" . $agent . "</b> has";} ?> done today.</p> -->
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <form action="javascript:void(0);">
                                        <div class="row g-3 mb-0 align-items-center">
                                            <div class="col-sm-auto">
                                                <div class="input-group">
                                                     <!-- <div>
                                                        <label for="myteam" class="form-label">Pick A Team</label>
                                                        <select class="form-select border-0 dash-filter-picker shadow" id="myteam" aria-label="Default select example" onchange="myFunction()">
                                                            <option selected>Select Team</option>
                                                            <?php if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ ?>
                                                            <option value="<?php echo $td['tname']; ?>"><?php echo $td['tname']; ?></option>
                                                            <?php }}else{echo '<p>No records found.</p>';} ?>
                                                        </select>
                                                    </div>  -->
                                                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
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
                                                                    
                                                                    var sd = document.getElementById('from');
                                                                    var ed = document.getElementById('to');                                                                    

                                                                    if(sd.value > ed.value){
                                                                        alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                                                        sd.focus();
                                                                    }
                                                                    else{
                                                                        var url = "agent-productivity?sd=" + sd.value + "&ed=" + ed.value;
                                                                        
                                                                        location.replace(url);
                                                                    }
                                                                    }
                                            </script>
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                            </div>
                            <!-- end card header -->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                    <?php 
                       
                    //$x = 1; if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ 
                         if($teams[0]['tname']=='Back Office'){
                             $team_head_name = $teams[0]['team_head_name'];
                             $team_head_mobile = $teams[0]['team_head_mobile'];
                         }
                        ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Backoffice : <?php echo $team_head_name. ' (' . $team_head_mobile. ')'; ?></h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="table-responsive">
                                
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Sr.<br>No.</th>
                                                        <th>Executive Name</th>
                                                        <th>Productivity</th>
                                                        <th>Bookings</th>
                                                        <th>Home<br>Visit</th>
                                                        <th>Direct Site<br>Visit</th>
                                                        <th>Corporate<br>Visit</th>
                                                        <th>Dialed<br>Calls</th>
                                                        <th>Connected<br>Calls</th>
                                                        <th>Unique<br>Calls</th>
                                                        <th>Booking File<br>Complete<br>Verification</th>
                                                        <th>Reciept<br>Preparation</th>
                                                        <th>All Type Of<br>Documentation</th>
                                                        <th>Demand</th>
                                                        <th>Possession<br>Certificate</th>
                                                        <th>File<br>Approval</th>
                                                        <th>Registry<br>Preparation</th>
                                                        <th>Registry<br>Authorisation<br>Physical</th>
                                                        <th>Meeting<br>With<br>Senior</th>
                                                        <th>Major/Minor<br>Payment<br>Discussion</th>
                                                        <th>Customer<br>Office<br>Visit</th>
                                                        <th>Customer<br>Grievance<br>Discussion</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    $visitMap = [];

                                                    foreach ($homeVisit as $hv) {
                                                        if (!empty($hv['name'])) {
                                                            $visitMap[trim(strtolower($hv['name']))] = $hv['total_count'];
                                                        }
                                                    }

                                                    $userWorkMap = [];

                                                    foreach($bookingFileCV as $row){
                                                        $userWorkMap[trim(strtolower($row['user_name']))] = $row;
                                                    }

                                                    $data['userWorkMap'] = $userWorkMap;



                                                    
                                                    ?>
                                                <tbody>
                                                    <?php 
                                                         
                                                        //$x = 1; if(is_array($dtrack) || is_object($dtrack)){foreach($dtrack as $cd){
                                                        //if($cd['team_head_name'] == $td['team_head_name']){if($cd['designation'] != 'DGM'){ 
                                                            $x=1;
                                                            foreach($team_details as $tds){ 
                                                            if($tds['tname']=='Back Office'){
                                                                $cleanName = trim(strtolower($tds['name']));

                                                            $reciept = 0;
                                                            $demand = 0;
                                                            $fileApproval = 0;
                                                            $possession_certificate = 0;

                                                            if(isset($userWorkMap[$cleanName])){
                                                                $reciept = $userWorkMap[$cleanName]['reciept_preparation'];
                                                                $demand = $userWorkMap[$cleanName]['demand'];
                                                                $fileApproval = $userWorkMap[$cleanName]['file_approval'];
                                                                $possession_certificate = $userWorkMap[$cleanName]['possession_certificate'];
                                                                $customer_office_visit = $userWorkMap[$cleanName]['customer_office_visit'];
                                                                $meeting_with_senior = $userWorkMap[$cleanName]['meeting_with_senior'];
                                                                $registry_preparation = $userWorkMap[$cleanName]['registry_preparation'];
                                                                $customer_grievance_discussion = $userWorkMap[$cleanName]['customer_grievance_discussion'];
                                                                $documentation_all_type_of_documentation = $userWorkMap[$cleanName]['documentation_all_type_of_documentation'];
                                                                $file_approval = $userWorkMap[$cleanName]['file_approval'];
                                                                $registry_authorisation_physical = $userWorkMap[$cleanName]['registry_authorisation_physical'];
                                                                $major_minor_payment_discussion = $userWorkMap[$cleanName]['major_minor_payment_discussion'];
                                                                $booking_file_complete_verification = $userWorkMap[$cleanName]['booking_file_complete_verification'];



                                                            }

                                                            ?>
                                                    <tr class="<?php //if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                        <td class="fw-medium"><?php echo $x; ?></td>
                                                        <td><?php echo $tds['name'] . ' (' . $tds['username'] . ')'; ?></td>
                                                        <td>
                                                        <?php 

                                                            $from = $_GET['sd'];  
                                                            $to   = $_GET['ed'];  
                                                            $startDate = new DateTime($from);
                                                            $endDate   = new DateTime($to);

                                                             $interval = $startDate->diff($endDate);

                                                                $total_days =  $interval->days + 1;

                                                           $total_work = ((int)$fileApproval * 6) 
                                                           + ((int)$documentation_all_type_of_documentation * 6) 
                                                           + ((int)$demand * 6)
                                                           + ((int)$possession_certificate * 6)
                                                           + ((int)$file_approval*6)
                                                           + ((int)$customer_office_visit*12)
                                                           + ((int)$registry_preparation*9)
                                                           + ((int)$meeting_with_senior*12)
                                                           + ((int)$homeVisit[$tds['name']]*12)
                                                           + ((int)$meeting_with_senior*12)
                                                           + ((int)$all_bookingDataCount[$tds['name']]*24)
                                                           + $ConCall['+91' . $tds['mobile']];

                                                          if($tds['name']==='Sanjay Maheshwari'){
                                                            $total_work = $total_work + ($registry_authorisation_physical*9);
                                                          }
                                                           //echo '-'.$total_work;
                                                           $percentage = ($total_work / ($total_days*90)) * 100;

                                                           $tot = round($percentage, 2).'%';   // 2 decimal tak
                                                                                                                     ;
                                                        ?><br><span class="badge badge-pill bg-success" data-key="t-new"><b><?= $tot; ?></b></span></td>
                                                        <td><?= rtrim(rtrim($all_bookingDataCount[$tds['name']] ?? '0', '0'), '.') ?: 0; ?></td>
                                                        <td><?= $homeVisit[$tds['name']] ?? 0; ?></td>
                                                        <td><?= $all_total_visit_count[$tds['name']]  ?? 0; ?></td>
                                                        <td><?= $all_total_visit_corp_count[$tds['name']]  ?? 0 ?></td>
                                                        <td><?= $dialedCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $ConCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $total_unique_calls['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $booking_file_complete_verification ?? 0; ?></td>
                                                        <td><?= $reciept ?? 0; ?></td>
                                                        <td><?= $documentation_all_type_of_documentation ?? 0; ?></td>
                                                        <td><?= $demand ?? 0; ?></td>
                                                        <td><?= $possession_certificate ?? 0; ?></td>
                                                        <td><?= $file_approval ?? 0; ?></td>
                                                        <td><?= $registry_preparation ?? 0; ?></td>
                                                        <td><?= $registry_authorisation_physical ?? 0; ?></td>
                                                        <td><?= $meeting_with_senior ?? 0; ?></td>
                                                        <td><?= $major_minor_payment_discussion ?? 0; ?></td>
                                                        <td><?= $customer_office_visit ?? 0; ?></td>
                                                        <td><?= $customer_grievance_discussion ?? 0; ?></td>
                                                    </tr>
                                                    <?php } $x++;} ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <?php //}}else{echo '<p>No records found.</p>';} ?>

                    <?php //$x = 1; if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ 
                        
                        if($teams[1]['tname']=='eMarketing'){
                            $team_head_name1 = $teams[1]['team_head_name'];
                            $team_head_mobile1 = $teams[1]['team_head_mobile'];
                        }


                        ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">eMarketing: <?php echo $team_head_name1. ' (' . $team_head_mobile1. ')'; ?></h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="table-responsive">
                                
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Executive Name</th>
                                                        <th>Productivity</th>
                                                        <th>Bookings</th>
                                                        <th>Negotiation <br>Done</th>
                                                        <th>Home Visit</th>
                                                        <th>Direct Site Visit</th>
                                                        <th>Dialed Calls</th>
                                                        <th>Connected Calls</th>
                                                        <th>Unique<br>Calls</th>
                                                        <th>Meeting With Senior</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  $x=1;
                                                           $productivityTotal = 0;

                                                            foreach($team_details as $tds){ 
                                                            if($tds['tname']=='eMarketing'){

                                                                $cleanName = trim(strtolower($tds['name']));
                                                                $meeting_with_senior = 0;

                                                                if(isset($userWorkMap[$cleanName])){

                                                                $meeting_with_senior = $userWorkMap[$cleanName]['meeting_with_senior'];
                                                                }

                                                        ?>
                                                    <tr class="<?php //if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                        <td class="fw-medium"><?php echo $x; ?></td>
                                                        <td><?php echo $tds['name'] . ' (' . $tds['username'] . ')'; ?></td>
                                                        <td>
                                                        <?php 

                                                            $from = $_GET['sd'];  
                                                            $to   = $_GET['ed'];  
                                                            $startDate = new DateTime($from);
                                                            $endDate   = new DateTime($to);

                                                             $interval = $startDate->diff($endDate);

                                                                $total_days =  $interval->days + 1;

                                                           $total_work = ((int)$homeVisit[$tds['name']]*12) + $ConCall['+91' . $tds['mobile']];

                                                         
                                                           //echo '-'.$total_work;
                                                           $percentage = ($total_work / ($total_days*90)) * 100;

                                                           $tot = round($percentage, 2).'%';   // 2 decimal tak
                                                                                                                     ;
                                                        ?><br><span class="badge badge-pill bg-success" data-key="t-new"><b><?= $tot; ?></b></span></td>
                                                        <td><?= rtrim(rtrim($all_bookingDataCount[$tds['name']] ?? '0', '0'), '.') ?: 0; ?></td>
                                                        <td>-</td>
                                                        <td><?= $homeVisit[$tds['name']] ?? 0; ?></td>
                                                        <td><?= $all_total_visit_count[$tds['name']]  ?? 0; ?></td>
                                                        <td><?= $dialedCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $ConCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $total_unique_calls['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $meeting_with_senior ?? 0; ?></td>
                                                    </tr>
                                                    <?php } $x++;} ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <?php //}}else{echo '<p>No records found.</p>';} ?>

                    <?php //$x = 1; if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ 
                        $i = 0;
                        foreach(array_slice($teams,2) as $ts){
                             
                            $cleanName = trim(strtolower($tds['name']));
                             $meeting_with_senior = 0;

                            if(isset($userWorkMap[$cleanName])){

                            $meeting_with_senior = $userWorkMap[$cleanName]['meeting_with_senior'];
                            }
                        ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"> <?= $ts['tname']; ?>: <?= $ts['team_head_name']; ?> ( <?= $ts['team_head_mobile']; ?> )</h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="table-responsive">
                                
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Executive Name</th>
                                                        <th>Productivity</th>
                                                        <th>Bookings</th>
                                                        <th>Negotiation Done</th>
                                                        <th>Home Visit</th>
                                                        <th>Direct Site Visit</th>
                                                        <th>Indirect Site Visit</th>
                                                        <th>Corporate Visit</th>
                                                        <?php if($ts['tname']=='Up-country / Local Corporate'){ ?>
                                                        <th>DB Stall Visit</th>
                                                        <?php } ?>
                                                        <th>Dialed Calls</th>
                                                        <th>Connected Calls</th>
                                                        <th>Unique<br>Calls</th>
                                                        <th>Meeting With Senior</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                            $x=1;
                                                            $productivityTotal = 0;
                                                            $bookingTotal = 0;
                                                            $homeVisitTotal = 0;
                                                            foreach($team_details as $tds){ 
                                                            if($tds['tname']==$ts['tname']){

                                                        ?>
                                                    <tr class="<?php //if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                        <td class="fw-medium"><?php echo $x;  ?></td>
                                                        <td><?php echo $tds['name'] . ' (' . $tds['username'] . ')'; ?></td>
                                                        <td>
                                                        <?php 

                                                            $from = $_GET['sd'];  
                                                            $to   = $_GET['ed'];  
                                                            $startDate = new DateTime($from);
                                                            $endDate   = new DateTime($to);

                                                             $interval = $startDate->diff($endDate);

                                                                $total_days =  $interval->days + 1;

                                                           $total_work = ((int)$homeVisit[$tds['name']]*12) + $ConCall['+91' . $tds['mobile']];

                                                         
                                                           //echo '-'.$total_work;
                                                           $percentage = ($total_work / ($total_days*90)) * 100;

                                                           $tot = round($percentage, 2).'%';   // 2 decimal tak

                                                           $productivityTotal += round($percentage, 2);
                                                                                                                     ;
                                                        ?><br><span class="badge badge-pill bg-success" data-key="t-new"><b><?= $tot;  ?></b></span></td>
                                                        <td><?= 
                                                        $book = rtrim(rtrim($all_bookingDataCount[$tds['name']] ?? '0', '0'), '.') ?: 0; 
                                                        echo $book;
                                                        $bookingTotal += $book;
                                                        ?></td>
                                                        <td>-<?php //echo $cd['email']; ?></td>
                                                        <td><?= $homeVisit[$tds['name']] ?? 0; 
                                                          $homeVisitTotal += $homeVisit[$tds['name']] ?? 0;
                                                        ?></td>
                                                        <td>-<?php //echo $cd['last_login']; ?></td>
                                                        <td>-<?php //echo $cd['last_login']; ?></td>
                                                        <td><?= $all_total_visit_count[$tds['name']]  ?? 0; ?></td>
                                                       
                                                        <?php if($ts['tname']=='Up-country / Local Corporate'){ ?>
                                                        <td>-</td>
                                                       <?php } ?>
                                                        <td><?= $dialedCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $ConCall['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $total_unique_calls['+91' . $tds['mobile']] ?? 0; ?></td>
                                                        <td><?= $meeting_with_senior ?? 0; ?></td>
                                                    </tr>
                                                    
                                                    <?php } $x++;} //}}}}else{echo '<p>No records found.</p>';} ?>
                                                    <tr class="table-dark" style="border-top-style: solid;">
                                                        <td><b>Grand Total</b></td>
                                                        <td></td>
                                                        <td><b><?php echo $productivityTotal.' %'; ?></b></td>
                                                        <td><b><?php echo $bookingTotal; ?></b></td>
                                                        <td>-</td>
                                                        <td><?= $homeVisitTotal; ?></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <?php if($ts['tname']=='Up-country / Local Corporate'){ ?>
                                                        <td>-</td>
                                                       <?php } ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <?php  } ?>

                </div><!-- container-fluid -->
            </div><!-- End Page-content -->
