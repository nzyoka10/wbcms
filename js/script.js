$(document).ready(function () {
    var table = $('#example').DataTable({
        "createdRow": function (row, data, dataIndex) {
            $(row).attr('id', data[0]);
        },
        'serverSide': true,
        'processing': true,
        'paging': true,
        'order': [],
        'ajax': {
            'url': 'fetch_data.php',
            'type': 'post',
        },
        "columnDefs": [{
            "targets": [6],
            "orderable": false,
            "searchable": false,
        }]
    });

    // Event listener for form submission to add a new user
    $(document).on('submit', '#addUser', function (e) {
        e.preventDefault();
        var name = $('#addUserField').val();
        var email = $('#addEmailField').val();
        var mobile = $('#addMobileField').val();
        var address = $('#addAddressField').val();
        var meter_id = $('#addMeterIdField').val();
        var status = $('#addStatusField').val();

        if (name != '' && email != '' && mobile != '' && address != '' && meter_id != '' && status != '') {
            $.ajax({
                url: "add_user.php",
                type: "post",
                data: {
                    name: name,
                    email: email,
                    mobile: mobile,
                    address: address,
                    meter_id: meter_id,
                    status: status
                },
                success: function (data) {
                    console.log(data); // Check response in console
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'true') {
                        table.ajax.reload();
                        $('#addUserModal').modal('hide');
                        $('#addUser')[0].reset(); // Clear form fields
                    } else {
                        alert('Failed to add user.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // Log any AJAX errors
                    alert('Error adding user. Please try again later.');
                }
            });
        } else {
            alert('Fill all the required fields');
        }
    });

    // Event listener for edit button click
    $('#example').on('click', '.editbtn', function (event) {
        var trid = $(this).closest('tr').attr('id');
        var id = $(this).data('id');
        $('#exampleModal').modal('show');

        // AJAX request to get single user data
        $.ajax({
            url: "get_single_data.php",
            data: {
                id: id
            },
            type: 'post',
            success: function (data) {
                var json = JSON.parse(data);
                // Populate form fields with fetched data
                $('#nameField').val(json.name);
                $('#emailField').val(json.email);
                $('#mobileField').val(json.mobile);
                $('#addressField').val(json.address);
                $('#meterIdField').val(json.meter_id);
                $('#statusField').val(json.status);
                $('#id').val(id);
                $('#trid').val(trid);
            }
        });
    });

    // Event listener for form submission to update user data
    $(document).on('submit', '#updateUser', function (e) {
        e.preventDefault(); // Prevent default form submission
        var name = $('#nameField').val();
        var email = $('#emailField').val();
        var mobile = $('#mobileField').val();
        var address = $('#addressField').val();
        var meter_id = $('#meterIdField').val();
        var status = $('#statusField').val();
        var trid = $('#trid').val();
        var id = $('#id').val();

        // Check if all fields are filled
        if (name != '' && email != '' && mobile != '' && address != '' && meter_id != '' && status != '') {
            // AJAX request to update user
            $.ajax({
                url: "update_user.php",
                type: "post",
                data: {
                    name: name,
                    email: email,
                    mobile: mobile,
                    address: address,
                    meter_id: meter_id,
                    status: status,
                    id: id
                },
                success: function (data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    // Check if update was successful
                    if (status == 'true') {
                        table.ajax.reload(); // Reload DataTable
                        $('#exampleModal').modal('hide'); // Hide modal
                    } else {
                        alert('Failed to update user.');
                    }
                }
            });
        } else {
            alert('Fill all the required fields'); // Alert if fields are empty
        }
    });

    // Event listener for delete button click
    $(document).on('click', '.deleteBtn', function (event) {
        event.preventDefault(); // Prevent default action
        var id = $(this).data('id');
        // Confirm delete action
        if (confirm("Are you sure you want to delete this User?")) {
            // AJAX request to delete user
            $.ajax({
                url: "delete_user.php",
                data: {
                    id: id
                },
                type: "post",
                success: function (data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    // Check if deletion was successful
                    if (status == 'success') {
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        alert('Failed to delete user.');
                    }
                }
            });
        } else {
            return; // Do nothing if cancel is clicked
        }
    });
});
