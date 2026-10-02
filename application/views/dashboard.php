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
    
    if(selectedValue === '' || selectedValue === null){dropdown.value = 'Select Agent';}else{dropdown.value = selectedValue;}
    
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
                                    <div class="d-flex align-items-center flex-wrap gap-2">

                                       <!-- Start Second (ss) Input -->
                                       <div>
                                          <label for="startSecond" class="form-label">Start Sec</label>
                                          <input type="number" id="startSecond" class="form-control border-0 dash-filter-picker shadow" placeholder="Start Sec" onchange="myFunction()" value="<?php echo $uss; ?>" style="width: 110px;">
                                       </div>

                                       <!-- End Second (es) Input -->
                                       <div>
                                          <label for="endSecond" class="form-label">End Sec</label>
                                          <input type="number" id="endSecond" class="form-control border-0 dash-filter-picker shadow" placeholder="End Sec" onchange="myFunction()" value="<?php echo $ues; ?>" style="width: 110px;">
                                       </div>

                                       <!-- Pick An Agent -->
                                       <div>
                                          <label for="myagent" class="form-label">Pick An Agent</label>
                                          <select class="form-select border-0 dash-filter-picker shadow" id="myagent" aria-label="Default select example" onchange="myFunction()">
                                             <option selected>Select Agent</option>
                                             <?php if (is_array($agt) || is_object($agt)){foreach($agt as $agn){ ?>
                                             <option value="<?php echo $agn['name']; ?>" <?php if($agent == $agn['name']){ echo 'selected'; } ?>><?php echo $agn['name']; ?></option>
                                             <?php }}else{echo '<p>No records found.</p>';} ?>
                                          </select>
                                       </div>

                                       <?php
                                          $currentDate = new DateTime();
                                          $curd = $currentDate->format('Y-m-d');
                                       ?>
                                       <!-- Date From -->
                                       <div>
                                          <label for="from" class="form-label">Date From</label>
                                          <input type="date" id="from" class="form-control border-0 dash-filter-picker shadow" onchange="myFunction()" value="<?php if(!empty($usd)){echo $usd;}else{echo $curd;} ?>">
                                       </div>

                                       <!-- Date To -->
                                       <div>
                                          <label for="to" class="form-label">Date To</label>
                                          <input type="date" id="to" class="form-control border-0 dash-filter-picker shadow" onchange="myFunction()" value="<?php if(!empty($ued)){echo $ued;}else{echo $curd;} ?>">
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

                                       // CRITICAL REQUIREMENT: Do NOT change URL unless BOTH startSecond and endSecond have values entered, OR both are empty.
                                       // If one is filled and the other is empty, halt execution.
                                       if((ss.value !== '' && es.value === '') || (ss.value === '' && es.value !== '')) {
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

                                       // Build URL parameters dynamically
                                       var url = "dashboard?agent=" + encodeURIComponent(ag.value) + 
                                                 "&sd=" + encodeURIComponent(sd.value) + 
                                                 "&ed=" + encodeURIComponent(ed.value);

                                       // Only append ss and es if both have values
                                       if(ss.value !== '' && es.value !== '') {
                                          url += "&ss=" + encodeURIComponent(ss.value) + "&es=" + encodeURIComponent(es.value);
                                       }
                                       
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

               <!-- Rest of your dashboard widgets and cards -->
               <div class="row">
                  <div class="col-xxl-8">
                     <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                           <h4 class="card-title mb-0 flex-grow-1">Projects Overview</h4>
                        </div>
                        <div class="card-body p-0 pb-2">
                           <div id="projects-overview-chart" class="apex-charts" dir="ltr"></div>
                        </div>
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>
