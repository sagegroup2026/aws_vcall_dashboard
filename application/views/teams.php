        <?php
            $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

            if($uri[2]=='total-calls'){$cno = $calls; $cdetails = $call_details; $hd = 'Total Calls'; $shd = 'Total Calls - Inbound & Outbound';}
            elseif($uri[2]=='outbound-calls'){$cno = $out_calls; $cdetails = $out_details; $hd = 'Total Outbound Calls'; $shd = 'Total Calls - Outbound';}
            elseif($uri[2]=='inbound-calls'){$cno = $in_calls; $cdetails = $in_details; $hd = 'Total Inbound Calls'; $shd = 'Total Calls - Inbound';}
            elseif($uri[2]=='outbound-manual'){$cno = $out_man_calls; $cdetails = $out_man_details; $hd = 'Total Outbound Manual Calls'; $shd = 'Total Calls - Outbound Manual';}
            elseif($uri[2]=='teams'){$tdetails = $teams; $hd = 'All Teams'; $shd = 'All Teams & Their Heads';}
            // exit();

            $sess_nm = $this->session->userdata("name");
            $agent = htmlspecialchars($_GET['agent']);
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
                                                    <div>
                                                        <label for="myteam" class="form-label">Pick A Team</label>
                                                        <select class="form-select border-0 dash-filter-picker shadow" id="myteam" aria-label="Default select example" onchange="myFunction()">
                                                            <option selected>Select Team</option>
                                                            <?php if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ ?>
                                                            <option value="<?php echo $td['tname']; ?>"><?php echo $td['tname']; ?></option>
                                                            <?php }}else{echo '<p>No records found.</p>';} ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function myFunction(){
                                                    var tm = document.getElementById('myteam');
                                                    var ur = window.location.pathname;

                                                    if(tm.value == 'Select Agent'){tm.value = ''}

                                                    if(sd.value > ed.value){
                                                        alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                                        sd.focus();
                                                    }
                                                    else{
                                                        var url = ur + "?myteam=" + ag.value;
                                          
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

                    <?php $x = 1; if (is_array($tdetails) || is_object($tdetails)){foreach($tdetails as $td){ ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"><?php echo $td['tname'] . ' : ' . $td['team_head_name'] . ' (' . $td['team_head_mobile'] . ')'; ?></h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="table-responsive">
                                
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Agent Name</th>
                                                        <th>Agent Mobile No.</th>
                                                        <th>Agent eMail ID</th>
                                                        <th>Agent Designation</th>
                                                        <th>Last Login</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $x = 1; if(is_array($team_details) || is_object($team_details)){$t = count($team_details); $ts = $t - 3; foreach($team_details as $cd){if($cd['team_head_name'] == $td['team_head_name']){if($cd['designation'] != 'DGM'){ ?>
                                                    <tr class="<?php //if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                        <td class="fw-medium"><?php echo $x; $x++; ?></td>
                                                        <td><?php echo $cd['name'] . ' (' . $cd['username'] . ')'; ?></td>
                                                        <td><?php echo $cd['mobile']; ?></td>
                                                        <td><?php echo $cd['email']; ?></td>
                                                        <td><?php echo $cd['designation']; ?></td>
                                                        <td><?php echo $cd['last_login']; ?></td>
                                                    </tr>
                                                    <?php }}}}else{echo '<p>No records found.</p>';} ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <?php }}else{echo '<p>No records found.</p>';} ?>

                </div><!-- container-fluid -->
            </div><!-- End Page-content -->
