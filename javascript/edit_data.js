// Edit apartment list
$(document).ready(function() {
    // Edit button click event
    $(document).on('click', '.edit', function() {
        event.preventDefault();
        
        var row = $(this).closest('tr');
        var table = row.closest('table');
        var tableId = table.attr('id');
        var primaryKey = row.find('.delete').data('id');
      
        // Create the cancel and save buttons
        var cancelBtn = $('<a href="" class="cancel"><i class="material-icons" data-toggle="tooltip" title="Cancel">&#xE14C;</i></a>');
        var saveBtn = $('<a href="#" class="save" data-id="' + primaryKey + '"><i class="material-icons" data-toggle="tooltip" title="Save">&#xE161;</i></a>');
      
        // Replace the edit and delete buttons
        row.find('.edit').replaceWith(cancelBtn);
        row.find('.delete').replaceWith(saveBtn);

        // Replace the table cells with appropriate input fields and select dropdowns
        table.find('th[data-column]').each(function() {
            var columnIndex = $(this).data('column');
            var columnName = $(this).text();
            var cellValue = row.find('td:nth-child(' + columnIndex + ')').text();
            var inputType = getInputType(tableId, columnName);

            if (inputType === 'input') {
                if (columnName === 'Price' || columnName === 'Amount') {
                    var priceValue = cellValue.replace(/[^0-9]/g, '');
                    priceValue = priceValue.slice(0, -2);

                    row.find('td:nth-child(' + columnIndex + ')').html('<input type="text" value="' + priceValue + '" name="' + columnName + '" class="form-control" required style="width: 150px;">');
                } else if (columnName === 'Date') {
                    var dateValue = cellValue;

                    var inputField = $('<input type="text" value="' + dateValue + '" name="' + columnName + '" id="' + columnName + '" class="form-control" required>');
                    row.find('td:nth-child(' + columnIndex + ')').empty().append(inputField);
                    inputField.datepicker({
                        dateFormat: 'yy-mm-dd',
                        autoclose: true,
                        showButtonPanel: true
                    });
                } else {
                    row.find('td:nth-child(' + columnIndex + ')').html('<input type="text" value="' + cellValue + '" name="' + columnName + '" class="form-control" required style="width: 150px;">');
                }
            } else if (inputType === 'select') {
                var selectDropdown = $('<select name="' + columnName + '" class="form-select form-select-lg mb-3" required style="width: 150px;"></select>');

                populateSelectOptions(tableId, columnName, selectDropdown, cellValue);
                row.find('td:nth-child(' + columnIndex + ')').html(selectDropdown);

                if (columnName === 'Apartment Rented') {
                    var values = cellValue.split('|');
                    var apartment = values[1].trim();

                    row.data('originalValue' + columnIndex, apartment);
                }
            }
        });
    });

    // Save button click event
    $(document).on('click', '.save', function() {
        var row = $(this).closest('tr');
        var primaryKey = row.find('.save').data('id');
        var origApartment = row.data('originalValue5');
        var table = row.closest('table');
        var tableId = table.attr('id');
        var rowData = {};

        // Iterate through each table cell and retrieve the corresponding values
        row.find('td').each(function(index) {
            var cell = $(this);
            var columnIndex = index + 1;
            var columnName = cell.closest('table').find('th:nth-child(' + columnIndex + ')').text().trim();
            var cellValue;
            
            // Determine the input type based on the column name
            var inputType = getInputType(tableId, columnName);
            if (inputType === 'select') {
                cellValue = cell.find('select').val();
            } else if (inputType === 'input') {
                if (columnName === "Date"){
                    cellValue = formatToISODate(cell.find('input').val())
                } else {
                    cellValue = cell.find('input').val();
                }
            } else {
                cellValue = cell.text().trim();
            }

            // Modify the column name if it has spaces or special characters
            if (columnName === "Apartment No.") {
                columnName = "apartmentNo";
            } else if (columnName === "Apartment Type") {
                columnName = "apartmentType";
            } else if (columnName === "Apartment Rented") {
                columnName = "apartmentRented";
            }
            
            // Additional Data
            if (columnName === "Name") {
                var namePattern = /^(.*?)\b\s+([\w\s]*)$/;
    
                var matches = cellValue.match(namePattern);
                var firstName = matches ? (matches[1] ? matches[1].trim() : '') : '';
                var secondName = matches ? (matches[2] ? matches[2].split(' ')[0].trim() : '') : '';
                var lastName = matches ? (matches[2] ? matches[2].replace(secondName, '').trim() + (matches[3] || '') : (matches[3] || '')) : '';
    
                if (lastName === "") {
                    lastName += secondName;
                    secondName = "";
                    firstName += secondName; 
                } else {
                    firstName += " " + secondName;
                }
    
                rowData["firstName"] = firstName;
                rowData["lastName"] = lastName;
            }

            // Store the column name and its corresponding value in the rowData object
            rowData[columnName] = cellValue;
        });

        // Additional Data
        rowData["primaryKey"] = primaryKey;
        $.ajax({
            url: 'Stored_Data/get_apart.php',
            method: 'GET',
            success: function(response) {
                var apartmentList = JSON.parse(response);
                var apartmentId = getApartmentId(apartmentList, origApartment);

                rowData["origApartment"] = apartmentId;
            }
        })

        console.log(rowData);

        Swal.fire({
            title: 'Do you want to save the changes?',
            showDenyButton: true,
            confirmButtonText: 'Save',
            denyButtonText: `Don't save`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'Stored_Data/update_data.php',
                    method: 'POST',
                    data: rowData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: response.message,
                                icon: 'success',
                            }).then(() => {
                                window.location.replace(response.redirectUrl);
                            });
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                            });
                        }
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Your changes were not saved', '', 'info');
            }
        });
    });

    function formatToISODate(dateString) {
        var dateObj = new Date(dateString);
        var year = dateObj.getFullYear();
        var month = String(dateObj.getMonth() + 1).padStart(2, '0');
        var day = String(dateObj.getDate()).padStart(2, '0');
      
        var formattedDate = year + '-' + month + '-' + day;
        return formattedDate;
    }

    // Function to determine the input type based on the table and column names
    function getInputType(tableId, columnName) {
        if (tableId === 'manage-apart') {
            if (columnName === 'Category' || columnName === 'Status') {
                return 'select';
            } else if (columnName === 'Apartment No.') {
                return 'input';
            }
        } else if (tableId === 'manage-cat') {
            if (columnName === 'Apartment Type' || columnName === 'Price') {
                return 'input';
            }
        } else if (tableId === 'manage-tent') {
            if (columnName === 'Name' || columnName === 'Phone' || columnName === 'Occupation' || columnName === 'Date') {
                return 'input';
            } else if (columnName === 'Apartment Rented') {
                return 'select';
            }
        } else if (tableId === 'nested-manage-pay') {
            if (columnName === 'Amount') {
                return 'input';
            }
        }
        
        // Add more conditions for other tables and column names as needed
        
        return 'text';
    }

    // Function to populate select options based on the table and column names
    function populateSelectOptions(tableId, columnName, selectDropdown, selectedValue) {
        if (tableId === 'manage-apart') {
            if (columnName === 'Category') {
                $.ajax({
                    url: 'Stored_Data/get_category.php',
                    method: 'GET',
                    success: function(response) {
                        var categories = JSON.parse(response);
                        categories.forEach(function(categoryItem) {
                            var option = $('<option></option>').attr('value', categoryItem.id).text(categoryItem.name);
                            if (categoryItem.name === selectedValue) {
                                option.attr('selected', 'selected');
                            }
                            selectDropdown.append(option);
                        });
                    },
                    error: function() {
                        alert('Error retrieving categories.');
                    }
                });
            } else if (columnName === 'Status') {
                selectDropdown.append('<option value="0"' + (selectedValue === 'Available' ? ' selected' : '') + '>Available</option>');
                selectDropdown.append('<option value="1"' + (selectedValue === 'Unavailable' ? ' selected' : '') + '>Unavailable</option>');
                selectDropdown.append('<option value="2"' + (selectedValue === 'Maintenance' ? ' selected' : '') + '>Maintenance</option>');
            }            
        } else if (tableId === 'manage-tent') {
            $.ajax({
                url: 'Stored_Data/get_apartments.php',
                method: 'GET',
                success: function(response) {
                    var apartment = JSON.parse(response);
                    var values = selectedValue.split('|');
                    var apart = values[1].trim();

                    $.ajax({
                        url: 'Stored_Data/get_apart.php',
                        method: 'GET',
                        success: function(response) {
                            var apartmentList = JSON.parse(response);
                            var apartmentId = getApartmentId(apartmentList, apart);

                            var defaultOption = $('<option></option>').text(apart).attr('selected', 'selected').val(apartmentId);
                            selectDropdown.append(defaultOption);
        
                            apartment.forEach(function(apartmentItem) {
                                var option = $('<option></option>').attr('value', apartmentItem.id).text(apartmentItem.apart);
                                selectDropdown.append(option);
                            });
                        }
                    })
                },
                error: function() {
                    alert('Error retrieving apartments.');
                }
            });
        }

        // Add more conditions for other tables and column names as needed
    } 

    // Get the id of the apartment based from its name
    function getApartmentId(apartmentList, apartmentName) {
        for (var i = 0; i < apartmentList.length; i++) {
            if (apartmentList[i].apart === apartmentName) {
                return apartmentList[i].id;
            }
        }
        return null;
    }

    // Get the id of the category based from its name
    function getCategoryId(categoryList, categoryName) {
        for (var i = 0; i < categoryList.length; i++) {
            if (categoryList[i].name === categoryName) {
                return categoryList[i].id;
            }
        }
        return null;
    }
    
    // Helper function to retrieve the appropriate AJAX URL based on the table ID
    function getAjaxURL(tableId) {
        // Determine the appropriate AJAX URL based on the table ID
        if (tableId === 'manage-apart') {
            return 'Stored_Data/update_apartment.php';
        } else if (tableId === 'manage-cat') {
            return 'Stored_Data/update_category.php';
        }
    
        // Default behavior: return a fallback URL
        return 'Stored_Data/get_data.php';
    }

    // Function to determine whether a column should be edited based on the table and column names
    function shouldEditColumn(tableId, columnName) {
        if (tableId === 'manage-apart' && (columnName === 'Apartment No.' || columnName === 'Category' || columnName === 'Status')) {
            return true;
        } else if (tableId === 'manage-cat' && (columnName === 'Apartment Type' || columnName === 'Price')) {
            return true;
        }

        // Add more conditions for other tables and column names as needed

        return false;
    }
});