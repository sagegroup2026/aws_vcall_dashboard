<?php
   $sess_nm = $this->session->userdata("name");
   $agent = htmlspecialchars($_GET['agent'] ?? '');
   $usd = htmlspecialchars($_GET['sd'] ?? '');
   $ued = htmlspecialchars($_GET['ed'] ?? '');
   
   // Capture Start Second (ss) and End Second (es) from URL parameters
   $uss = htmlspecialchars($_GET['ss'] ?? '');
   $ues = htmlspecialchars($_GET['es'] ?? '');

   if($agent == ""){$user = $sess_nm;}
   elseif(!empty($agent)){$user = $agent;}
?>

<script>
   window.onload = function() {
    // Get the URL search parameters
    const urlParams = new URLSearchParams(window.location.search);

    // Get the value of specific parameters
    const selectedValue = urlParams.get('agent');
    const selectedSs = urlParams.get('ss');
    const selectedEs = urlParams.get('es');

    // Reference dropdown and second inputs
    const dropdown = document.getElementById('myagent');
    const startSecInput = document.getElementById('startSecond');
    const endSecInput = document.getElementById('endSecond');
    
    if(selectedValue === ''){selectedValue.value = 'Select Agent';}else{dropdown.value = selectedValue;}
    
    if(selectedSs) { startSecInput.value = selectedSs; }
    if(selectedEs) { endSecInput.value = selectedEs; }
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

                                       <!-- NEW: Start Second (ss) Input (Placed before Pick An Agent) -->
                                       <div>
                                          <label for="startSecond" class="form-label">Start Second</label>
                                          <input type="number" id="startSecond" class="form-control border-0 dash-filter-picker shadow" placeholder="e.g. 0" onchange="myFunction()" value="<?php echo $uss; ?>">
                                       </div>
                                       <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

                                       <!-- NEW: End Second (es) Input -->
                                       <div>
                                          <label for="endSecond" class="form-label">End Second</label>
                                          <input type="number" id="endSecond" class="form-control border-0 dash-filter-picker shadow" placeholder="e.g. 60" onchange="myFunction()" value="<?php echo $ues; ?>">
                                       </div>
                                       <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

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
                                       var ss = document.getElementById('startSecond');
                                       var es = document.getElementById('endSecond');

                                       if(ag.value == 'Select Agent'){ag.value = '';}

                                       // Date validation check
                                       if(sd.value > ed.value){
                                          alert('Wrong Date Selection! From Date can not be greater than To Date.');
                                          sd.focus();
                                          return;
                                       }

                                       // Second validation check: If one second field is filled, ensure the other is also filled before updating URL
                                       if((ss.value !== '' && es.value === '') || (ss.value === '' && es.value !== '')) {
                                          // Wait until both startSecond and endSecond are provided
                                          return;
                                       }

                                       // Second range validation check
                                       if(ss.value !== '' && es.value !== '') {
                                          if(parseInt(ss.value) > parseInt(es.value)) {
                                             alert('Wrong Second Selection! Start Second cannot be greater than End Second.');
                                             ss.focus();
                                             return;
                                          }
                                       }

                                       // Construct URL including agent, dates, and seconds parameters
                                       var url = "dashboard?agent=" + encodeURIComponent(ag.value) + 
                                                 "&sd=" + encodeURIComponent(sd.value) + 
                                                 "&ed=" + encodeURIComponent(ed.value) + 
                                                 "&ss=" + encodeURIComponent(ss.value) + 
                                                 "&es=" + encodeURIComponent(es.value);
                                       
                                       location.replace(url);
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

               <!-- Rest of your dashboard cards and charts continue below as normal -->
               <!-- (All cards unchanged, continuing with your existing view links using $_SERVER['QUERY_STRING']) -->
               
               <div class="row">
                  <div class="col-xxl-8">
                     <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                           <h4 class="card-title mb-0 flex-grow-1">Projects Overview</h4>
                           <div>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">ALL</button>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">1M</button>
                              <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">6M</button>
                              <button type="button" class="btn btn-soft-primary btn-sm shadow-none">1Y</button>
                           </div>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-soft-light">
                           <div class="row g-0 text-center">
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1"><span class="counter-value" data-target="9851">0</span></h5>
                                    <p class="text-muted mb-0">Number of Projects</p>
                                 </div>
                              </div>
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1"><span class="counter-value" data-target="1026">0</span></h5>
                                    <p class="text-muted mb-0">Active Projects</p>
                                 </div>
                              </div>
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">$<span class="counter-value" data-target="228.89">0</span>k</h5>
                                    <p class="text-muted mb-0">Revenue</p>
                                 </div>
                              </div>
                              <div class="col-6 col-sm-3">
                                 <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <h5 class="mb-1 text-success"><span class="counter-value" data-target="10589">0</span>h</h5>
                                    <p class="text-muted mb-0">Working Hours</p>
                                 </div>
                              </div>
                           </div>
                        </div><!-- end card header -->
                        
                        <div class="card-body p-0 pb-2">
                           <div>
                              <div id="projects-overview-chart" data-colors='["--vz-primary", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                           </div>
                        </div><!-- end card body -->
                     </div><!-- end card -->
                  </div><!-- end col -->

                  <div class="col-xxl-4">
                     <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                           <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                           <div class="flex-shrink-0">
                              <div class="dropdown card-header-dropdown">
                                 <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Download Report</a>
                                    <a class="dropdown-item" href="#">Export</a>
                                    <a class="dropdown-item" href="#">Import</a>
                                 </div>
                              </div>
                           </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                           <div id="store-visits-source" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                        </div>
                     </div> <!-- .card-->
                  </div>
               </div>

               <div class="row">
                  <div class="col-xl-4 col-md-6">
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
                     </div>
                  </div>

                  <div class="col-xl-4 col-md-6">
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
                     </div>
                  </div>

                  <div class="col-xl-4 col-md-6">
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
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>
