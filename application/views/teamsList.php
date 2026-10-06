

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
                                          var url = "team-call-report?&sd=" + sd.value + "&ed=" + ed.value;
                                          
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
                            <h4 class="card-title mb-0 flex-grow-1"><?php echo $shd . ' (Data Updated On - ' . date("d M'y, H:i:s", $timestamp) . ')'; ?></h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="table-responsive">
                                
                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Team Name</th>
                                                <th>Team Head Name</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php $x = 1; if(is_array($total) || is_object($total)){$t = count($total); $ts = $t - 3; foreach($total as $cd){ ?>
                                            <tr  class="<?php if($x <= 3){echo 'top-performer';}elseif($x > $ts){echo 'low-performer';} ?>">
                                                <td class="fw-medium"><?php echo $x; $x++; ?></td>
                                                <td><?php echo $cd['tname']; ?></td>
                                                <td><?php echo $cd['thead>']; ?></td>
                                               
                                                
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
