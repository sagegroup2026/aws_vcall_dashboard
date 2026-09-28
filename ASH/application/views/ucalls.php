        <?php
            $sess_nm = $this->session->userdata("name");
            $agent = htmlspecialchars($_GET['agent']);
            $usd = htmlspecialchars($_GET['sd']);
            $ued = htmlspecialchars($_GET['ed']);
            if($agent == ""){$user = $sess_nm;}
            elseif(!empty($agent)){$user = $agent;}

            $cno = $unq_calls; $cdetails = $unq_calls_details; $hd = 'Total Unique Calls'; $shd = 'Total Calls - Unique';
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
                                    <p class="text-muted mb-0">Here's what's <?php if($this->session->userdata("name") == $agent){echo "you've";}elseif($agent == ""){echo "your whole team has";}else{echo "<b>" . $agent . "</b> has";} ?> done today.</p>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <form action="javascript:void(0);">
                                        <div class="row g-3 mb-0 align-items-center">
                                            <div class="col-sm-auto">
                                                <div class="input-group">
                                                    <div>
                                                        <label for="myagent" class="form-label">Pick An Agent</label>
                                                        <select class="form-select border-0 dash-filter-picker shadow" id="myagent" aria-label="Default select example" onchange="myFunction()">
                                                            <option selected>Select Agent</option>
                                                            <?php if (is_array($agt) || is_object($agt)){foreach($agt as $agn){ ?>
                                                            <option value="<?php echo $agn['name']; ?>"><?php echo $agn['name']; ?></option>
                                                            <?php }}else{echo '<p>No records found.</p>';} ?>
                                                        </select>
                                                    </div>
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
                                                    var ag = document.getElementById('myagent');
                                                    var sd = document.getElementById('from');
                                                    var ed = document.getElementById('to');
                                                    var ur = window.location.pathname;

                                                    if(ag.value == 'Select Agent'){ag.value = ''}

                                                    if(sd.value > ed.value){
                                                        alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                                        sd.focus();
                                                    }
                                                    else{
                                                        var url = ur + "?agent=" + ag.value + "&sd=" + sd.value + "&ed=" + ed.value;
                                          
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

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"><?php echo $shd . ' (' . $cno . ')'; ?></h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-now rap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sr. No.</th>
                                                        <th scope="col">Agent Name</th>
                                                        <th scope="col">Agent No.</th>
                                                        <th scope="col">Client Name</th>
                                                        <th scope="col">Client No.</th>
                                                        <th scope="col">Total Calls (Dial & Recieve)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $x = 1; if (is_array($cdetails) || is_object($cdetails)){foreach((array) $cdetails as $cd){ ?>
                                                    <tr>
                                                        <td class="fw-medium"><?php echo $x; $x++; ?></td>
                                                        <td>
                                                            <div class="d-flex gap-2 align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <img src="assets/images/users/avatar-3.jpg" alt="<?php echo $cd['user_name']; ?>" class="avatar-xs rounded-circle" />
                                                                </div>
                                                                <div class="flex-grow-1"><?php echo $cd['user_name']; ?></div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $cd['sender_phone_number']; ?></td>
                                                        <td style="word-wrap: break-all !important; white-space: normal !important; width: 600px;"><?php echo $cd['name']; ?></td>
                                                        <td><?php echo $cd['receiver_phone_number']; ?></td>
                                                        <td><?php echo $cd['call_made']; ?></td>
                                                    </tr>
                                                    <?php }}else{echo '<p>No records found.</p>';} ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- container-fluid -->
            </div><!-- End Page-content -->
