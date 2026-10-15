<style>
    .table th, .table td { text-align: center; vertical-align: middle; font-size: 13px; }
    .table .la { text-align: left !important; }
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Start Page Title --> 
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Bookings List Report</h4>
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal" onclick="openAddModal()">
                                <i class="ri-add-line align-bottom me-1"></i> Add New Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Manage Bookings</h4>
                            <div class="flex-shrink-0 d-flex gap-2">
                                <select id="per_page_limit" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <input type="text" id="table_search" class="form-control form-control-sm" placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Date</th>
                                                <th>Month / Year</th>
                                                <th>Customer Name</th>
                                                <th>Project</th>
                                                <th>Unit</th>
                                                <th>Type</th>
                                                <th>Sales Person</th>
                                                <th>Team</th>
                                                <th>Bookings</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bookings_table_body">
                                            <!-- AJAX Data will load here -->
                                        </tbody>
                                    </table> 
                                </div>
                                <!-- Pagination Info & Links -->
                                <div class="pagination-container">
                                    <div id="table_info" class="text-muted font-size-13"></div>
                                    <nav>
                                        <ul id="pagination_links" class="pagination pagination-sm mb-0"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add / Edit Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="bookingForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Add New Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="booking_id">
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="booking_date" class="form-label">Booking Date</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" required onchange="autoFillMonthYear(this.value)">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="booking_month" class="form-label">Booking Month</label>
                            <select class="form-select" id="booking_month" name="booking_month" required>
                                <option value="">Select Month</option>
                                <?php 
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                foreach($months as $m) {
                                    echo '<option value="'.$m.'">'.$m.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="booking_year" class="form-label">Booking Year</label>
                            <select class="form-select" id="booking_year" name="booking_year" required>
                                <option value="">Select Year</option>
                                <?php 
                                $currentYear = date('Y');
                                for($y = $currentYear - 5; $y <= $currentYear + 2; $y++) {
                                    echo '<option value="'.$y.'">'.$y.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="project" class="form-label">Project Name</label>
                            <select class="form-select" id="project" name="project" required>
                                <option value="">Select Project</option>
                                <option value="SGS">SGS</option>
                                <option value="SUP">SUP</option>
                                <option value="SMS">SMS</option>
                                <option value="SB 2">SB 2</option>
                                <?php if(!empty($projects)){ foreach($projects as $proj){ if(!empty($proj['project'])){ ?>
                                    <option value="<?php echo $proj['project']; ?>"><?php echo $proj['project']; ?></option>
                                <?php }}} ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter unit" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Flat">Flat</option>
                                <option value="Plot">Plot</option>
                                <option value="Shop">Shop</option>
                                <option value="Bungalow">Bungalow</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sales_person" class="form-label">Sales Person (User)</label>
                            <select class="form-select" id="sales_person" name="sales_person" required>
                                <option value="">Select Sales Person</option>
                                <?php if(!empty($users)){ foreach($users as $usr){ ?>
                                    <option value="<?php echo $usr['name']; ?>"><?php echo $usr['name']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="team" class="form-label">Team</label>
                            <select class="form-select" id="team" name="team" required>
                                <option value="">Select Team</option>
                                <?php if(!empty($teams)){ foreach($teams as $tm){ ?>
                                    <option value="<?php echo $tm['tname']; ?>"><?php echo $tm['tname']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="source" class="form-label">Source</label>
                            <input type="text" class="form-control" id="source" name="source" placeholder="Enter source">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sub_source" class="form-label">Sub Source</label>
                            <input type="text" class="form-control" id="sub_source" name="sub_source" placeholder="Enter sub source">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="bookings" class="form-label">Bookings Count/Value</label>
                            <input type="text" class="form-control" id="bookings" name="bookings" value="1.00" placeholder="1.00">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="DGM_Name" class="form-label">DGM Name</label>
                            <input type="text" class="form-control" id="DGM_Name" name="DGM_Name" placeholder="Enter DGM Name">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="highrise" class="form-label">Highrise</label>
                            <input type="text" class="form-control" id="highrise" name="highrise" placeholder="Enter highrise">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveBookingBtn">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AJAX & Script -->
<script>
    var current_page = 1;

    $(document).ready(function() {
        loadBookings(current_page);

        // Per page limit change
        $('#per_page_limit').change(function() {
            loadBookings(1);
        });

        // Search filter keyup
        $('#table_search').keyup(function() {
            loadBookings(1);
        });

        // Pagination click event
        $(document).on('click', '.ajax-pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            loadBookings(page);
        });
    });

    function loadBookings(page) {
        var limit = $('#per_page_limit').val();
        var search = $('#table_search').val();

        $.ajax({
            url: "<?php echo base_url('Booking/fetch_bookings_ajax'); ?>",
            type: "POST",
            data: { page: page, limit: limit, search: search },
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    var rows = '';
                    var data = response.data;
                    
                    if(data.length > 0) {
                        var sno = (response.current_page - 1) * response.per_page + 1;
                        $.each(data, function(index, bk) {
                            rows += '<tr>';
                            rows += '<td class="fw-medium">' + (sno++) + '</td>';
                            rows += '<td>' + escapeHtml(bk.booking_date) + '</td>';
                            rows += '<td>' + escapeHtml((bk.booking_month || '') + ' ' + (bk.booking_year || '')) + '</td>';
                            rows += '<td>' + escapeHtml(bk.customer_name || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.project || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.unit || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.type || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.sales_person || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.team || '') + '</td>';
                            rows += '<td>' + escapeHtml(bk.bookings || '') + '</td>';
                            rows += '<td>';
                            rows += '<button type="button" class="btn btn-sm btn-soft-primary edit-btn" onclick="editBooking(' + bk.id + ')"><i class="ri-pencil-fill"></i> Edit</button>';
                            rows += '</td>';
                            rows += '</tr>';
                        });
                    } else {
                        rows += '<tr><td colspan="11" class="text-center">No records found.</td></tr>';
                    }
                    $('#bookings_table_body').html(rows);

                    // Render Pagination Info & Links
                    renderPagination(response.total_records, response.total_pages, response.current_page, response.per_page);
                }
            }
        });
    }

    function renderPagination(total_records, total_pages, current_page, per_page) {
    var start_entry = total_records === 0 ? 0 : (current_page - 1) * per_page + 1;
    var end_entry = Math.min(current_page * per_page, total_records);
    
    $('#table_info').text('Showing ' + start_entry + ' to ' + end_entry + ' of ' + total_records + ' entries');

    var paginationHtml = '';
    if(total_pages > 1) {
        // Previous button
        var prevDisabled = current_page === 1 ? 'disabled' : '';
        paginationHtml += '<li class="page-item ' + prevDisabled + '"><a class="page-link ajax-pagination-link" href="#" data-page="' + (current_page - 1) + '">Previous</a></li>';

        // Logic to limit visible page numbers
        var maxVisiblePages = 5;
        var startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
        var endPage = Math.min(total_pages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        // First page + ellipsis if needed
        if (startPage > 1) {
            paginationHtml += '<li class="page-item"><a class="page-link ajax-pagination-link" href="#" data-page="1">1</a></li>';
            if (startPage > 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Page numbers
        for(var i = startPage; i <= endPage; i++) {
            var active = i === current_page ? 'active' : '';
            paginationHtml += '<li class="page-item ' + active + '"><a class="page-link ajax-pagination-link" href="#" data-page="' + i + '">' + i + '</a></li>';
        }

        // Last page + ellipsis if needed
        if (endPage < total_pages) {
            if (endPage < total_pages - 1) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += '<li class="page-item"><a class="page-link ajax-pagination-link" href="#" data-page="' + total_pages + '">' + total_pages + '</a></li>';
        }

        // Next button
        var nextDisabled = current_page === total_pages ? 'disabled' : '';
        paginationHtml += '<li class="page-item ' + nextDisabled + '"><a class="page-link ajax-pagination-link" href="#" data-page="' + (current_page + 1) + '">Next</a></li>';
    }
    $('#pagination_links').html(paginationHtml);
}

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    function autoFillMonthYear(dateVal) {
        if(dateVal) {
            let d = new Date(dateVal);
            let monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let month = monthNames[d.getMonth()];
            let year = d.getFullYear();

            $('#booking_month').val(month);
            $('#booking_year').val(year);
        }
    }

    function openAddModal() {
        $('#bookingForm')[0].reset();
        $('#booking_id').val('');
        $('#bookingModalLabel').text('Add New Booking');
        $('#saveBookingBtn').text('Save Booking');
        
        let today = new Date().toISOString().split('T')[0];
        $('#booking_date').val(today);
        autoFillMonthYear(today);
    }

    function editBooking(id) {
        $.ajax({
            url: "<?php echo base_url('Booking/get_booking_data'); ?>",
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    $('#booking_id').val(response.data.id);
                    
                    // Date ko properly YYYY-MM-DD format mein convert karke set karna
                    if(response.data.booking_date) {
                        let formattedDate = new Date(response.data.booking_date).toISOString().split('T')[0];
                        $('#booking_date').val(formattedDate);
                    } else {
                        $('#booking_date').val('');
                    }

                    $('#booking_month').val(response.data.booking_month);
                    $('#booking_year').val(response.data.booking_year);
                    $('#customer_name').val(response.data.customer_name);
                    $('#project').val(response.data.project);
                    $('#unit').val(response.data.unit);
                    $('#type').val(response.data.type);
                    $('#sales_person').val(response.data.sales_person);
                    $('#team').val(response.data.team);
                    $('#source').val(response.data.source);
                    $('#sub_source').val(response.data.sub_source);
                    $('#bookings').val(response.data.bookings);
                    $('#DGM_Name').val(response.data.DGM_Name);
                    $('#highrise').val(response.data.highrise);

                    $('#bookingModalLabel').text('Edit Booking');
                    $('#saveBookingBtn').text('Update Booking');
                    $('#bookingModal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    }

    $(document).off('submit', '#bookingForm').on('submit', '#bookingForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "<?php echo base_url('Booking/save_booking_ajax'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    $('#bookingModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        loadBookings(current_page);
                    });
                } else if(response.status === 'exists') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unit Already Booked!',
                        html: response.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                } 
            }
        });
    });
</script>
