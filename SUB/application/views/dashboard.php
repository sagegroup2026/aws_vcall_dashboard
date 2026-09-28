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
               <h4 class="mb-sm-0">Dashboard</h4>
               <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item"><a href="dashboard<?php echo '?' . $_SERVER['QUERY_STRING']; ?>">Dashboards</a></li>
                     <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
      <!-- end page title -->
      <div class="row">
         <div class="col">
            <div class="h-100">
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

                                       if(ag.value == 'Select Agent'){ag.value = ''}

                                       if(sd.value > ed.value){
                                          alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                          sd.focus();
                                       }
                                       else{
                                          var url = "dashboard?agent=" + ag.value + "&sd=" + sd.value + "&ed=" + ed.value;
                                          
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
                  <div class="col-xl-4 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 %
                                 </h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $calls; ?>">0</span></h4>
                                 <a href="total-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-primary rounded fs-3">
                                 <i class="mdi mdi-phone-in-talk-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-xl-4 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Outgoing Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 %
                                 </h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $out_calls; ?>">0</span></h4>
                                 <a href="outbound-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-success rounded fs-3">
                                 <i class="mdi mdi-phone-outgoing-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->

                  <div class="col-xl-4 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Incoming Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-danger fs-14 mb-0">
                                    <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %
                                 </h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $in_calls; ?>">0</span></h4>
                                 <a href="inbound-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-info rounded fs-3">
                                 <i class="mdi mdi-phone-incoming-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  
                  <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Unique Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">
                                    +0.00 %
                                 </h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $unq_calls; ?>">0</span></h4>
                                 <a href="unique-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-info rounded fs-3">
                                 <i class="mdi mdi-phone-dial-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->

                  <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Connected Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">
                                    +0.00 %
                                 </h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php $con_call = $out_cnctd_calls + $in_cnctd_calls; echo $con_call; ?>">0</span></h4>
                                 <a href="connected-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-success rounded fs-3">
                                 <i class="mdi mdi-phone-check-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Rejected Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">+0.00 %</h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $rjctd_calls; ?>">0</span></h4>
                                 <a href="rejected-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-danger rounded fs-3">
                                 <i class="mdi mdi-phone-off-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Missed Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">+0.00 %</h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $msd_calls; ?>">0</span></h4>
                                 <a href="missed-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-warning rounded fs-3">
                                 <i class="mdi mdi-phone-missed-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                   <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Never Attended Calls</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">+0.00 %</h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php // echo $nvratnd_calls; ?>">0</span></h4>
                                 <a href="never-attended-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-danger rounded fs-3">
                                 <i class="mdi mdi-phone-ring-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                   <div class="col-xl-2 col-md-6">
                     <!-- card -->
                     <div class="card card-animate">
                        <div class="card-body">
                           <div class="d-flex align-items-center">
                              <div class="flex-grow-1 overflow-hidden">
                                 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Not Picked By Clients</p>
                              </div>
                              <div class="flex-shrink-0">
                                 <h5 class="text-muted fs-14 mb-0">+0.00 %</h5>
                              </div>
                           </div>
                           <div class="d-flex align-items-end justify-content-between mt-4">
                              <div>
                                 <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $ntpkclnt_calls; ?>">0</span></h4>
                                 <a href="not-picked-by-client<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="text-decoration-underline">View Details</a>
                              </div>
                              <div class="avatar-sm flex-shrink-0">
                                 <span class="avatar-title bg-danger rounded fs-3">
                                    <i class="mdi mdi-phone-remove-outline"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
               </div>
               <!-- end row-->




               <div class="row">
                  <div class="col-xl-5">
                     <!-- card -->
                     <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex bg-soft-secondary">
                           <h4 class="card-title mb-0 flex-grow-1">Connected Calls</h4>
                           <!-- <div class="flex-shrink-0">
                              <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                              Export Report
                              </button>
                           </div> -->
                        </div>
                        <!-- end card header -->
                        <!-- card body -->
                        <div class="card-body">
                           <div class="p-2 h-100 d-flex flex-column">
                              <div class="w-100">
                                 <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                       <h5 class="fs-16 mb-1">Total Connected Calls</h5>
                                       <p class="text-success mb-1">+586.85 (40.6%)</p>
                                    </div>
                                    <h1 class="ff-secondary fw-bold mt-1 text-primary"><i class="mdi mdi-firebase text-primary"></i> <span class="counter-value" data-target="<?php $con_call = $out_cnctd_calls + $in_cnctd_calls; echo $con_call; ?>">0</span></h1>
                                 </div>

                                 <div class="d-flex align-items-end justify-content-between mt-2">
                                    <div>
                                       <p class="fs-14 text-muted mb-1">Outbound Connected Calls</p>
                                       <h4 class="fs-20 ff-secondary fw-semibold mb-0"><?php echo $out_cnctd_calls; ?></h4>
                                    </div>

                                    <div>
                                       <p class="fs-14 text-muted mb-1">Inbound Connected Calls</p>
                                       <h4 class="fs-20 ff-secondary fw-semibold mb-0"><?php echo $in_cnctd_calls; ?></h4>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->

                  <div class="col-xl-7">
                     <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                           <h4 class="card-title mb-0 flex-grow-1">Call Duration</h4>
                           <!-- <div>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                              ALL
                              </button>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                              1M
                              </button>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                              6M
                              </button>
                              <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                              1Y
                              </button>
                           </div> -->
                        </div>
                        <!-- end card header -->
                        
                        <div class="card-header p-0 border-0 alert-warning">
                           <div class="row g-0 text-center">
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <div class="card card-animate">
                                       <div class="card-body">
                                          <div class="d-flex justify-content-between">
                                             <div>
                                                <h2 class="ff-secondary fw-semibold">
                                                   <?php 
                                                      $dro = $out_duration->result_object[0]->duration;
                                                      $dri = $in_duration->result_object[0]->duration;
                                                      $drr = $dro + $dri;
                                                      $dr = $drr;
                                                      $ds = floor($dr / 86400);
                                                      $d = $dr % 86400;
                                                      $h = floor($d / 3600);
                                                      $m = floor(($d - ($h * 3600)) / 60);
                                                      $rs = $d % 60;
                                                      $t = sprintf('%02dD %02d:%02d:%02d', $ds, $h, $m, $rs);
                                                      echo $t;
                                                      // echo gmdate('H:i:s', ($duration->result_object[0]->duration));
                                                   ?>
                                                </h2>
                                                <p class="mb-0 text-muted">Total Call Duration
                                                   <span class="badge bg-light text-danger mb-0">
                                                      <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                                                   </span>
                                                </p>
                                             </div>
                                             <div>
                                                <div class="avatar-sm flex-shrink-0">
                                                   <span class="avatar-title bg-primary rounded-circle fs-2">
                                                     <i class="mdi mdi-av-timer"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div><!-- end card body -->
                                    </div> <!-- end card-->
                                 </div>
                              </div>
                              <!--end col-->
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <div class="card card-animate">
                                       <div class="card-body">
                                          <div class="d-flex justify-content-between">
                                             <div>
                                                <h2 class="ff-secondary fw-semibold">
                                                   <?php 
                                                      $dro = $out_duration->result_object[0]->duration;
                                                      $dri = $in_duration->result_object[0]->duration;
                                                      $drr = $dro + $dri;
                                                      if($con_call == 0){$con_call = 1;}
                                                      $dr = $drr / $con_call;
                                                      $ds = floor($dr / 86400);
                                                      $d = $dr % 86400;
                                                      $h = floor($d / 3600);
                                                      $m = floor(($d - ($h * 3600)) / 60);
                                                      $rs = $d % 60;
                                                      $t = sprintf('%02dD %02d:%02d:%02d', $ds, $h, $m, $rs);
                                                      echo $t;
                                                      // echo gmdate('H:i:s', ($duration->result_object[0]->duration));
                                                   ?>
                                                </h2>
                                                <p class="mb-0 text-muted">Avg. Call Duration
                                                   <span class="badge bg-light text-danger mb-0">
                                                      <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                                                   </span>
                                                </p>
                                             </div>
                                             <div>
                                                <div class="avatar-sm flex-shrink-0">
                                                   <span class="avatar-title bg-success rounded-circle fs-2">
                                                      <i class="mdi mdi-timer-settings-outline"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div><!-- end card body -->
                                    </div> <!-- end card-->
                                 </div>
                              </div>
                              <!--end col-->
                              <style>#flip{-webkit-transform: scaleX(-1); transform: scaleX(-1);}</style>
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <div class="card card-animate">
                                       <div class="card-body">
                                          <div class="d-flex justify-content-between">
                                             <div>
                                                <h2 class="ff-secondary fw-semibold"><?php 
                                                      $dr = $out_duration->result_object[0]->duration;
                                                      $ds = floor($dr / 86400);
                                                      $d = $dr % 86400;
                                                      $h = floor($d / 3600);
                                                      $m = floor(($d - ($h * 3600)) / 60);
                                                      $rs = $d % 60;
                                                      $t = sprintf('%02dD %02d:%02d:%02d', $ds, $h, $m, $rs);
                                                      echo $t;
                                                      // echo gmdate('H:i:s', ($duration->result_object[0]->duration));
                                                   ?></h2>
                                                <p class="mb-0 text-muted">Outbound Calls<br>
                                                   <span class="badge bg-light text-danger mb-0">
                                                      <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                                                   </span>
                                                </p>
                                             </div>
                                             <div>
                                                <div class="avatar-sm flex-shrink-0">
                                                   <span class="avatar-title bg-info rounded-circle fs-2">
                                                      <i class="ri-history-line" id="flip"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div><!-- end card body -->
                                    </div> <!-- end card-->
                                 </div>
                              </div>
                              <!--end col-->
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <div class="card card-animate">
                                       <div class="card-body">
                                          <div class="d-flex justify-content-between">
                                             <div>
                                                <h2 class="ff-secondary fw-semibold"><?php 
                                                      $dr = $in_duration->result_object[0]->duration;
                                                      $ds = floor($dr / 86400);
                                                      $d = $dr % 86400;
                                                      $h = floor($d / 3600);
                                                      $m = floor(($d - ($h * 3600)) / 60);
                                                      $rs = $d % 60;
                                                      $t = sprintf('%02dD %02d:%02d:%02d', $ds, $h, $m, $rs);
                                                      echo $t;
                                                      // echo gmdate('H:i:s', ($duration->result_object[0]->duration));
                                                   ?></h2>
                                                <p class="mb-0 text-muted">Inbound Calls
                                                   <span class="badge bg-light text-danger mb-0">
                                                      <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                                                   </span>
                                                </p>
                                             </div>
                                             <div>
                                                <div class="avatar-sm flex-shrink-0">
                                                   <span class="avatar-title bg-warning rounded-circle fs-2">
                                                      <i class="ri-history-line"></i>
                                                      <!-- <i class="mdi mdi-clock-in"></i> -->
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div><!-- end card body -->
                                    </div> <!-- end card-->
                                 </div>
                              </div>
                              <!--end col-->
                           </div>
                        </div>
                        <!-- end card header -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
               </div>
            </div>
            <!-- end .h-100-->
         </div>
         <!-- end col -->
      </div>
   </div>
   <!-- container-fluid -->
</div>
<!-- End Page-content -->