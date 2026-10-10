<style>
    .table th, .table td { text-align: center; vertical-align: middle; }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Team Members List</h4>
                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#memberModal" onclick="openAddModal()">
                                <i class="ri-add-line align-bottom me-1"></i> Add New Member
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Manage Members</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Gender</th>
                                            <th>Designation</th>
                                            <th>Team Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $x = 1; 
                                        if(!empty($members) && is_array($members)){ 
                                            foreach($members as $m){ 
                                        ?>
                                        <tr>
                                            <td><?php echo $x++; ?></td>
                                            <td><?php echo htmlspecialchars($m['name']); ?></td>
                                            <td><?php echo htmlspecialchars($m['username']); ?></td>
                                            <td><?php echo htmlspecialchars($m['email']); ?></td>
                                            <td><?php echo htmlspecialchars($m['mobile']); ?></td>
                                            <td><?php echo htmlspecialchars($m['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($m['designation']); ?></td>
                                            <td><?php echo htmlspecialchars($m['tname'] ? $m['tname'] : 'No Team'); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-soft-primary" onclick="editMember(<?php echo $m['id']; ?>)">
                                                    <i class="ri-pencil-fill"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo '<tr><td colspan="9" class="text-center">No records found.</td></tr>';
                                        } 
                                        ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add / Edit Member Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="memberForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberModalLabel">Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="member_id" id="member_id">
                    
                    <!-- Row 1: Name & Username -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required placeholder="Enter username">
                        </div>
                    </div>

                    <!-- Row 2: Email & Password -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email address">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password" required placeholder="Enter password">
                        </div>
                    </div>

                    <!-- Row 3: Mobile & Gender -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required placeholder="Enter mobile number">
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Designation & Team -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="designation" class="form-label">Designation</label>
                            <select class="form-select" id="designation" name="designation" required>
                                <option value="">Select Designation</option>
                                <option value="Web Administrator">Web Administrator</option>
                                <option value="DGM">DGM</option>
                                <option value="Manager">Manager</option>
                                <option value="Marketing Executive">Marketing Executive</option>
                                <option value="Support Office Executive">Support Office Executive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="team" class="form-label">Assign Team</label>
                            <select class="form-select" id="team" name="team">
                                <option value="0">Select Team</option>
                                <?php if(!empty($teams)){ foreach($teams as $t){ ?>
                                    <option value="<?php echo $t['id']; ?>"><?php echo $t['tname']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="saveBtn">Save Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AJAX & SweetAlert2 Script -->
<script>
    function openAddModal() {
        $('#memberForm')[0].reset();
        $('#member_id').val('');
        $('#memberModalLabel').text('Add New Member');
        $('#saveBtn').text('Save Member');
    }

    function editMember(id) {
        $.ajax({
            url: "<?php echo base_url('Member/get_member_data'); ?>",
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    $('#member_id').val(response.data.id);
                    $('#name').val(response.data.name);
                    $('#username').val(response.data.username);
                    $('#email').val(response.data.email);
                    $('#password').val(response.data.password);
                    $('#mobile').val(response.data.mobile);
                    $('#gender').val(response.data.gender);
                    $('#designation').val(response.data.designation);
                    $('#team').val(response.data.team);
                    
                    $('#memberModalLabel').text('Edit Member');
                    $('#saveBtn').text('Update Member');
                    $('#memberModal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    }

    $(document).off('submit', '#memberForm').on('submit', '#memberForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "<?php echo base_url('Member/save_member_ajax'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    $('#memberModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
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
