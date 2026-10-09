<style>
    th, td{text-align: center;vertical-align: middle;}
    .la{text-align: left !important;}
</style>

<!-- SweetAlert2 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                        <h4 class="mb-sm-0">Teams List</h4>

                        <div class="page-title-right">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#teamModal" onclick="openAddModal()">
                                <i class="ri-add-line align-bottom me-1"></i> Add New Team
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Manage Teams</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="table-responsive">
                                    <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th class="la">Team Name</th>
                                                <th>Team Head Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1; if(!empty($total) && is_array($total)){ foreach($total as $cd){ ?>
                                            <tr>
                                                <td class="fw-medium"><?php echo $x++; ?></td>
                                                <td class="la"><?php echo $cd['tname']; ?></td>
                                                <td><?php echo isset($cd['team_head_name']) ? $cd['team_head_name'] : $cd['thead']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-soft-primary edit-btn" onclick="editTeam(<?php echo $cd['id']; ?>)">
                                                        <i class="ri-pencil-fill"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php }}else{echo '<tr><td colspan="4" class="text-center">No records found.</td></tr>';} ?>
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
</div>

<!-- Add / Edit Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="teamForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamModalLabel">Add New Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="team_id" id="team_id">
                    <div class="mb-3">
                        <label for="tname" class="form-label">Team Name</label>
                        <input type="text" class="form-control" id="tname" name="tname" required placeholder="Enter team name">
                    </div>
                    <div class="mb-3">
                        <label for="thead" class="form-label">Team Head</label>
                        <select class="form-select" id="thead" name="thead" required>
                            <option value="">Select Team Head</option>
                            <?php if(!empty($users)){ foreach($users as $usr){ ?>
                                <option value="<?php echo $usr['id']; ?>"><?php echo $usr['name']; ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveBtn">Save Team</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AJAX & SweetAlert2 Script -->
<script>
    function openAddModal() {
        $('#teamForm')[0].reset();
        $('#team_id').val('');
        $('#teamModalLabel').text('Add New Team');
        $('#saveBtn').text('Save Team');
    }

    function editTeam(id) {
        $.ajax({
            url: "<?php echo base_url('Team/get_team_data'); ?>",
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    $('#team_id').val(response.data.id);
                    $('#tname').val(response.data.tname);
                    $('#thead').val(response.data.thead);
                    $('#teamModalLabel').text('Edit Team');
                    $('#saveBtn').text('Update Team');
                    $('#teamModal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    }

    $('#teamForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "<?php echo base_url('Team/save_team_ajax'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else if(response.status === 'exists') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Already Exists',
                        text: response.message
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
