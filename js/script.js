
$(document).ready(function(){
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', // Form action
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                // Assuming your server returns a JSON response with the new client data
                const client = JSON.parse(response);

                // Check if the response contains the necessary client information
                if (client) {
                    // Append the new client to the table
                    $('table tbody').append(`
                        <tr>
                            <th scope="row">${client.index}</th>
                            <td>${client.client_name}</td>
                            <td>${client.contact_number}</td>
                            <td>${client.address}</td>
                            <td>${client.meter_number}</td>
                            <td>${client.meter_reading}</td>
                            <td>${client.status}</td>
                            <td>
                                <a href="view_client.php?id=${client.user_id}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="edit_client.php?id=${client.user_id}" class="btn btn-warning btn-sm" title="Modify">
                                    <i class="fas fa-edit"></i> Modify
                                </a>
                                <a href="delete_client.php?id=${client.user_id}" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this client?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    `);

                    // Clear the form
                    $('#registerForm')[0].reset();

                    // Close the modal
                    $('#exampleModal').modal('hide');
                } else {
                    alert('Failed to register client. Please try again.');
                }
            },
            error: function() {
                alert('Error occurred. Please try again.');
            }
        });
    });
});