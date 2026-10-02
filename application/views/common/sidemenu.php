<?php
$sess_nm = $this->session->userdata("name");

$sd = htmlspecialchars($_GET['sd']);
$ed = htmlspecialchars($_GET['ed']);

$currentDate = new DateTime();
$curd = $currentDate->format('Y-m-d');
if(!empty($sd)){$sd = $sd;}else{$sd = $curd;}
if(!empty($ed)){$ed = $ed;}else{$ed = $curd;}
?>
      
      
      <!-- ========== App Menu ========== -->
      <div class="app-menu navbar-menu">
        <div id="scrollbar">
         <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
               <li class="menu-title"><span data-key="t-menu">Menu</span></li>
               <li class="nav-item">
                  <a class="nav-link menu-link" href="<?php echo base_url(); ?>dashboard<?php echo '?' . $_SERVER['QUERY_STRING']; ?>">
                     <i class="mdi mdi-speedometer"></i> <span data-key="t-dashboards">Dashboards</span>
                  </a>
               </li>
               <!-- end Dashboard Menu -->
               <li class="nav-item">
                  <a class="nav-link menu-link" href="#sidebarAnlytics" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAnlytics">
                     <i data-feather="slack"></i></i> <span data-key="t-anlytics">Master</span>
                  </a>
                  <div class="collapse menu-dropdown" id="sidebarAnlytics">
                     <ul class="nav nav-sm flex-column">
                        <!--<li class="nav-item">
                           <a href="teams" class="nav-link">Teams</a>
                        </li>-->
						<li class="nav-item">
                           <a href="sidebarAgentReport" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-rpt">Teams</a>
                           <div class="collapse menu-dropdown" id="sidebarAgentReport">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="teams" class="nav-link" data-key="t-auto">All Teams</a>
                                 </li>
								 <li class="nav-item">
                                    <a href="<?php echo base_url(); ?>teamsList" class="nav-link" data-key="t-auto">Teams</a>
                                 </li>
                                 
                              </ul>
                           </div>
                        </li>
                        <li class="nav-item">
                           <a href="#" class="nav-link">Agents</a>
                        </li>
                        <!-- <li class="nav-item">
                           <a href="#sidebarOutbound" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-out">Outbound Calls</a>
                           <div class="collapse menu-dropdown" id="sidebarOutbound">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="outbound-auto<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-auto">Auto</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="outbound-manual<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-manual">Manual</a>
                                 </li>
                              </ul>
                           </div>
                        </li>
                        <li class="nav-item">
                           <a href="inbound-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-chat">Inbound Calls</a>
                        </li> -->
                     </ul>
                  </div>
               </li>
               <li class="nav-item">
                  <a class="nav-link menu-link" href="#sidebarAnlytics" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAnlytics">
                     <i class="ri-bar-chart-2-line"></i> <span data-key="t-anlytics">Anlytics</span>
                  </a>
                  <div class="collapse menu-dropdown" id="sidebarAnlytics">
                     <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                           <a href="total-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link">Overall</a>
                        </li>
                        <li class="nav-item">
                           <a href="#sidebarOutbound" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-out">Outbound Calls</a>
                           <div class="collapse menu-dropdown" id="sidebarOutbound">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="outbound-auto<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-auto">Auto</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="outbound-manual<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-manual">Manual</a>
                                 </li>
                              </ul>
                           </div>
                        </li>
                        <li class="nav-item">
                           <a href="inbound-calls<?php echo '?' . $_SERVER['QUERY_STRING']; ?>" class="nav-link" data-key="t-chat">Inbound Calls</a>
                        </li>
                     </ul>
                  </div>
               </li>
               <li class="nav-item">
                  <a class="nav-link menu-link" href="#sidebarReports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarReports">
                  <i class="ri-newspaper-line"></i> <span data-key="t-reports">Reports</span>
                  </a>
                  <div class="collapse menu-dropdown" id="sidebarReports">
                     <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                           <a href="#sidebarCallReport" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" >Call Reports</a>
                           <div class="collapse menu-dropdown" id="sidebarCallReport">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-auto">Call Detailed Report</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-manual">Manual Call Detailed Report</a>
                                 </li>
                              </ul>
                           </div>
                        </li>

                        <li class="nav-item">
                           <a href="sidebarAgentReport" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-rpt">Agent Reports</a>
                           <div class="collapse menu-dropdown" id="sidebarAgentReport">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="agent-call-report<?php echo '?sd=' . $sd . '&ed=' . $ed; ?>" class="nav-link" data-key="t-auto">Agent Call Report</a>
                                 </li>
								 <li class="nav-item">
                                    <a href="never-logged-in-report<?php echo '?sd=' . $sd . '&ed=' . $ed; ?>" class="nav-link" data-key="t-auto">Never Logged In Report</a>
                                 </li>
                                 <li class="nav-item">
                                    <a href="agent-productivity" class="nav-link" data-key="t-auto">Agent Productivity Report</a>
                                 </li>
                                 <!-- <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-manual">Login History Report</a>
                                 </li> -->
                              </ul>
                           </div>
                        </li>

                        <li class="nav-item">
                           <a href="sidebarAgentReport" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-rpt">Team Reports</a>
                           <div class="collapse menu-dropdown" id="sidebarAgentReport">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="team-call-report<?php echo '?sd=' . $sd . '&ed=' . $ed; ?>" class="nav-link" data-key="t-auto">Team Productivity Report</a>
                                 </li>
                              </ul>
                           </div>
                        </li>

                        <li class="nav-item">
                           <a href="sidebarAgentReport" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail" data-key="t-rpt">Other Reports</a>
                           <div class="collapse menu-dropdown" id="sidebarAgentReport">
                              <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                    <a href="team-call-report<?php echo '?sd=' . $sd . '&ed=' . $ed; ?>" class="nav-link" data-key="t-auto">All Bookings Report</a>
                                 </li>
				 <li class="nav-item">
                                    <a href="daily-performance-report<?php echo '?sd=' . $sd . '&ed=' . $ed; ?>" class="nav-link" data-key="t-auto">Daily Performance Report</a>
                                 </li>
                              </ul>
                           </div>
                        </li>


                     </ul>
                  </div>
               </li>
            </ul>
         </div>
         <!-- Sidebar -->
        </div>
        <div class="sidebar-background"></div>
      </div>
      <!-- Left Sidebar End -->
      <!-- Vertical Overlay-->
      <div class="vertical-overlay"></div>
