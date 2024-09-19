// Edit apartment list
$(document).ready(function() {
  // Edit button click event
  $(document).on('click', '.edit', function() {
    var row = $(this).closest('tr');
    var apartmentId = row.find('.delete').data('id');

    // Create the cancel and save buttons
    var cancelBtn = $('<a href="" class="cancel"><i class="material-icons" data-toggle="tooltip" title="Cancel">&#xE14C;</i></a>');
    var saveBtn = $('<a href="#" class="save" data-id="' + apartmentId + '"><i class="material-icons" data-toggle="tooltip" title="Save">&#xE161;</i></a>');

    // Remove the edit and delete buttons
    row.find('.edit').replaceWith(cancelBtn);
    row.find('.delete').replaceWith(saveBtn);
    
    // Retrieve the values from the table cells
    var apartmentNumber = row.find('td:nth-child(2)').text();
    var category = row.find('td:nth-child(3)').text();
    var status = row.find('td:nth-child(4)').text().includes('Available') ? 0 : 1;

    // Store the original values in data attributes
    row.data('originalApartmentNumber', row.find('td:nth-child(2)').text());
    row.data('originalCategory', row.find('td:nth-child(3)').text());
    row.data('originalStatus', row.find('td:nth-child(4)').text().includes('Available') ? 0 : 1);

    // Replace the table cells with input fields and select dropdowns
    row.find('td:nth-child(2)').html('<input type="text" value="' + apartmentNumber + '" name="apartment_number">');
    row.find('td:nth-child(3)').html('<select name="category"></select>');
    row.find('td:nth-child(4)').html('<select name="status"></select>');

    $.ajax({
      url: 'Stored_Data/get_category.php',
      method: 'GET',
      success: function(response) {
        var categories = JSON.parse(response);

        // Populate the category select dropdown
        categories.forEach(function(categoryItem) {
          var option = '<option value="' + categoryItem.id + '"';
          if (categoryItem.name === category) {
            option += ' selected';
          }
          option += '>' + categoryItem.name + '</option>';
          row.find('select[name="category"]').append(option);
        });

        // Set the selected option in the status select dropdown
        row.find('select[name="status"]').append('<option value="0"' + (status === 0 ? ' selected' : '') + '>Available</option>');
        row.find('select[name="status"]').append('<option value="1"' + (status === 1 ? ' selected' : '') + '>Unavailable</option>');

        // Update the edit button and delete button icons
        row.find('.edit i').text('cancel');
        row.find('.edit i').attr('class', 'material-icons cancel');
        row.find('.delete i').text('check');
        row.find('.delete i').attr('class', 'material-icons save');
      },
      error: function() {
        // Display error message if unable to retrieve categories
        alert('Error retrieving categories.');
      }
    });
  });

  // Cancel button click event
  $(document).on('click', '.cancel', function() {
    // Revert the table cells to their original values
    var row = $(this).closest('tr');
    var apartmentId = row.find('.save').data('id');

    // Replace the cancel button with the original edit button
    var editBtn = $('<a href="#editModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>');
    row.find('.cancel').replaceWith(editBtn);

    // Replace the save button with the original delete button
    var deleteBtn = $('<a href="#" class="delete" data-id="' + apartmentId + '"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>');
    row.find('.save').replaceWith(deleteBtn);

    // Update the edit button icon and class
    editBtn.find('i').text('edit');
    editBtn.find('i').attr('class', 'material-icons edit');

    // Update the delete button icon and class
    deleteBtn.find('i').text('delete');
    deleteBtn.find('i').attr('class', 'material-icons delete');

    row.find('td:nth-child(2)').text(row.data('originalApartmentNumber'));
    row.find('td:nth-child(3)').text(row.data('originalCategory'));
    var status = row.data('originalStatus') === 0 ? 'Available' : 'Unavailable';
    row.find('td:nth-child(4)').html('<span class="badge badge-' + (status === 'Available' ? 'success' : 'default') + '">' + status + '</span>');

    // Update the cancel button and save button icons
    row.find('.cancel i').text('edit');
    row.find('.cancel i').attr('class', 'material-icons edit');
    row.find('.save i').text('delete');
    row.find('.save i').attr('class', 'material-icons delete');
  });

  // Save button click event
  $(document).on('click', '.save', function() {
    var row = $(this).closest('tr');
    var apartmentId = row.find('.save').data('id');
    var apartmentNumber = row.find('td:nth-child(2) input').val();
    var categoryID = row.find('td:nth-child(3) select').val();
    var status = row.find('td:nth-child(4) select').val();

    Swal.fire({
      title: 'Do you want to save the changes?',
      showDenyButton: true,
      confirmButtonText: 'Save',
      denyButtonText: `Don't save`,
    }).then((result) => {
      if (result.isConfirmed) {
        row.find('td:nth-child(2)').text(apartmentNumber);
        row.find('td:nth-child(3)').text(row.find('td:nth-child(3) select option:selected').text());
        row.find('td:nth-child(4)').html(status == 0 ? '<span class="badge badge-success">Available</span>' : '<span class="badge badge-default">Unavailable</span>');
    
        var editBtn = $('<a href="#editModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>');
        row.find('.cancel').replaceWith(editBtn);
    
        var deleteBtn = $('<a href="#" class="delete" data-id="' + apartmentId + '"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>');
        row.find('.save').replaceWith(deleteBtn);
        
        $.ajax({
          url: 'Stored_Data/update_apartment.php',
          method: 'POST',
          data: {
            id: apartmentId,
            category: categoryID,
            apartment_number: apartmentNumber,
            status: status
          },
          success: function() {
            Swal.fire('Saved!', '', 'success').then(() => {
              location.reload();
            });
          }
        });
      }
      else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    })
  });
});