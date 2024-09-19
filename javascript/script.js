// Highlight the selected section
$(document).ready(function() {
  // Get the current page URL
  const currentPageUrl = window.location.href;
  var filename = currentPageUrl.substring(currentPageUrl.lastIndexOf('/') + 1);

  // Get all the navigation links
  const navLinks = document.querySelectorAll('.nav-links li a');

  // Loop through each navigation link
  navLinks.forEach(link => {
      // Check if the link's href matches the current page URL
      if (link.getAttribute('href') === filename) {
        // Add the "active" class to the matching link
        link.classList.add('active');
      }

      // Add a click event listener to each link
      link.addEventListener('click', function() {
        // Remove the "active" class from all links
        navLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Add the "active" class to the clicked link
        this.classList.add('active');

        // Store the active link in localStorage
        localStorage.setItem('activeLink', this.getAttribute('href'));
      });
  });
});

// Search functionality
$(document).ready(function() {
  // Get the table rows
  var currentPage = window.location.href;
  var filename = currentPage.substring(currentPage.lastIndexOf('/') + 1);
  var tableId = getTableId(filename);
  var mainTableRows = $('#' + tableId + ' tbody tr');
  var nestedTableRows = $('#nested-manage-pay tbody tr'); // Add this line to select nested table rows

  // Listen for changes in the search input field
  $('#search-input').on('input', function() {
    var searchText = $(this).val().toLowerCase();

    // Filter the main table rows based on the search query
    mainTableRows.each(function() {
      var rowData = $(this).text().toLowerCase();
      if (rowData.includes(searchText)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    // Filter the nested table rows based on the search query
    nestedTableRows.each(function() {
      var rowData = $(this).text().toLowerCase();
      if (rowData.includes(searchText)) {
        $(this).show();
        $(this).closest('.nested-table').show(); // Show the parent nested table
      } else {
        $(this).hide();
      }
    });
  });

  // Function to get the table ID based on the filename
  function getTableId(filename) {
    // Add cases for different filenames and their corresponding table IDs
    switch (filename) {
      case 'apartments.php':
        return 'manage-apart';
      case 'categories.php':
        return 'manage-cat';
      case 'tenants.php':
        return 'manage-tent';
      case 'payments.php':
        return 'manage-pay';
      case 'report.php':
          return 'overall_report';
      case 'month_report.php':
          return 'month_report';
      default:
        return 'default-table';
    }
  }
});

// // Track if the mouse is being dragged
// let isDragging = false;
// // Track the starting X position of the mouse
// let startX = 0;
// // Track the current X position of the mouse
// let currentX = 0;
// // Track the current scroll position
// let scrollPosition = 0;
// // Track the scroll velocity
// let scrollVelocity = 0;

// // Scroll the container 
// function scrollContainer(direction, velocity) {
//   const container = document.getElementById('card-containers');
//   const scrollAmount = 100; // Adjust scroll amount as needed

//   const scrollDistance = scrollAmount * velocity;
//   if (direction === 'left') {
//     scrollPosition -= scrollDistance;
//   } else if (direction === 'right') {
//     scrollPosition += scrollDistance;
//   }

//   container.style.transform = `translateX(-${scrollPosition}px)`;
// }

// // Handle mouse scroll event
// function handleMouseScroll(event) {
//   // Get the scroll delta and determine the scroll direction
//   const scrollDelta = Math.sign(event.deltaY);
//   const direction = scrollDelta > 0 ? 'right' : 'left';

//   scrollContainer(direction, 1);
//   event.preventDefault(); // Prevent default scrolling behavior
// }

// // Start dragging the container
// function startDragging(event) {
//   isDragging = true;
//   startX = event.clientX;
//   currentX = startX;
//   scrollVelocity = 0;

//   // Disable text selection and box clickability during drag scrolling
//   const apartmentContainer = document.getElementById('card-containers');
//   apartmentContainer.classList.add('disable-select', 'disable-pointer-events');
// }

// // Handle dragging the container
// function handleDragging(event) {
//   if (!isDragging) return;

//   currentX = event.clientX;
//   const dragDistance = currentX - startX;
//   const dragDirection = dragDistance < 0 ? 'right' : 'left';

//   scrollVelocity = Math.abs(dragDistance) / window.innerWidth;
//   scrollContainer(dragDirection, scrollVelocity);
// }

// // Stop dragging the container
// function stopDragging() {
//   if (!isDragging) return;

//   isDragging = false;
//   startX = 0;
//   currentX = 0;

//   // Apply momentum scrolling based on the scroll velocity
//   const momentumDirection = scrollVelocity > 0 ? 'right' : 'left';
//   const momentumScrollAmount = 20 * Math.abs(scrollVelocity); // Adjust scroll amount as needed
//   scrollContainer(momentumDirection, momentumScrollAmount);

//   // Enable text selection and box clickability when not scrolling
//   const apartmentContainer = document.getElementById('card-containers');
//   apartmentContainer.classList.remove('disable-select', 'disable-pointer-events');
// }

// // Left scroll button
// function scrollLeft() {
//   scrollContainer('left', 1);
// }

// // Right scroll button
// function scrollRight() {
//   scrollContainer('right', 1);
// }

// Handles the click event on apartment boxes
async function showApartmentDetails(apartmentId) {
  const tenantDetailsContainer = document.getElementById('tenant-details-container');
  tenantDetailsContainer.innerHTML = ''; // Clear previous tenant details

  try {
    const response = await fetch('/Web_Projects/HRMS/Stored_Data/get_tenant_details.php', {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded'
      },
      body: `apartmentId=${apartmentId}`
    });

    if (!response.ok) {
      throw new Error('Failed to fetch tenant data');
    }

    const data = await response.json();

    if (data.length > 0) {
      const tenant = data[0]; // Assuming we only retrieve the first tenant for simplicity

      const templateResponse = await fetch('/Web_Projects/HRMS/Forms/tenant_apartment_details.html');

      if (!templateResponse.ok) {
        throw new Error('Failed to fetch template');
      }

      const template = await templateResponse.text();

      const html = template
        .replace('{{firstName}}', tenant.firstname)
        .replace('{{lastName}}', tenant.lastname)
        .replace('{{contact}}', tenant.contact)
        .replace('{{occupation}}', tenant.occupation)
        .replace('{{date}}', formatDate(tenant.dt));

      tenantDetailsContainer.innerHTML = html;
    }
  } catch (error) {
    console.error(error);
  }

  var button = document.getElementById("tenantDetailsButton");
  button.click();
}

// Update apartment status
async function updateApartmentStatus(apartmentId, status) {
  try {
    const response = await fetch('Stored_Data/update_apartment_status.php', {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded'
      },
      body: `apartmentId=${apartmentId}&status=${status}`
    });

    if (response.ok) {
      console.log(await response.text());

      const apartmentElement = document.getElementById('apartment-' + apartmentId);
      const occupiedButton = apartmentElement.querySelector('.btn-warning');
      const vacantButton = apartmentElement.querySelector('.btn-success');

      if (status === 1) {
        occupiedButton.style.opacity = '1';
        vacantButton.style.opacity = '0.5';
        maintenanceButton.style.opacity = '0.5';
        occupiedButton.blur(); // Remove focus from occupied button
      } else if (status === 0) {
        occupiedButton.style.opacity = '0.5';
        vacantButton.style.opacity = '1';
        maintenanceButton.style.opacity = '0.5';
        vacantButton.blur(); // Remove focus from vacant button
      } else if (status === 2) {
        occupiedButton.style.opacity = '0.5';
        vacantButton.style.opacity = '0.5';
        maintenanceButton.style.opacity = '1';
        maintenanceButton.blur(); // Remove focus from maintenance button
      }      
    } else {
      throw new Error('Failed to update apartment status');
    }
  } catch (error) {
    console.error(error);
  }
}

// Get category
$(document).ready(function() {
  $.ajax({
      url: "Stored_Data/get_category.php",
      method: "POST",
      dataType: "json",
      success: function(data) {
        // Update the select options with fetched categories
        var options = "";

        options += "<option disabled selected>Select category</option>";
        $.each(data, function(index, value) {
            options += "<option value='" + value.id + "'>" + value.name + "</option>";
        });

        $('#category').html(options);
      }
  });
});

// Get apartment
$(document).ready(function() {
  $.ajax({
      url: "Stored_Data/get_apartments.php",
      method: "POST",
      dataType: "json",
      success: function(data) {
        // Update the select options with fetched apartments
        var options = "";

        options += "<option disabled selected>Select apartment</option>";
        $.each(data, function(index, value) {
            options += "<option value='" + value.id + "'>" + value.apart + "</option>";
        });

        $('#apartment').html(options);
      }
  });
});

// Get tenants
$(document).ready(function() {
  $.ajax({
      url: "Stored_Data/get_tenants.php",
      method: "POST",
      dataType: "json",
      success: function(data) {
        // Update the select options with fetched tenants
        var options = "";

        options += "<option disabled selected value='default'>Select tenants</option>";
        $.each(data, function(index, value) {
          options += "<option value='" + value.id + "'>" + value.lastname + ", " + value.firstname + "</option>";
        });

        $('#tenant').html(options);

        // Event listener for tenant selection
        $('#tenant').on('change', function() {
          var selectedTenantId = $(this).val();
          showTenantDetails(selectedTenantId);

          // Generate random invoice number
          var invoiceNumber = generateRandomNumber();
          $('#invoice').val(invoiceNumber);
        });
      }
  });
});

function generateRandomNumber() {
  var currentDate = new Date();
  var year = currentDate.getFullYear().toString().substr(-2);
  var month = (currentDate.getMonth() + 1).toString().padStart(2, "0");
  var randomPart = generateRandomString(6); // Generate 6-character random string

  var randomNumber = year + month + randomPart;
  return randomNumber;
}

function generateRandomString(length) {
  var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var randomString = "";
  for (var i = 0; i < length; i++) {
    var randomIndex = Math.floor(Math.random() * characters.length);
    randomString += characters.charAt(randomIndex);
  }
  return randomString;
}

// Shows the details of the selected tenant
async function showTenantDetails(tenantId) {
  const tenantDetailsContainer = document.getElementById('tenant-details');
  tenantDetailsContainer.innerHTML = ''; // Clear previous tenant details

  try {
    const response = await fetch('Stored_Data/get_tenant_details.php', {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded'
      },
      body: `tenantId=${tenantId}`
    });

    if (!response.ok) {
      throw new Error('Failed to fetch tenant data');
    }

    const data = await response.json();

    console.log(data);

    if (data.length > 0) {
      const tenant = data[0]; // Assuming we only retrieve the first tenant for simplicity

      const templateResponse = await fetch('Forms/tenant_details.html');

      if (!templateResponse.ok) {
        throw new Error('Failed to fetch template');
      }

      const template = await templateResponse.text();

      // Fetch payments data
      const paymentsResponse = await fetch('Stored_Data/get_payments.php');
      const paymentsData = await paymentsResponse.json();

      let totalAmount = 0; // Updated to numeric value for summing
      let totalBalance = 0; // Updated to numeric value for calculating balance

      // Filter payments data for the same tenant_id
      const tenantPayments = paymentsData.filter(payment => payment.tenant_id === tenantId);

      if (tenantPayments.length > 0) {
        // Calculate total amount
        totalAmount = tenantPayments.reduce((sum, payment) => sum + parseFloat(payment.amount), 0);
      }

      // Calculate total balance
      totalBalance = (calculateMonthsAhead(tenant.dt) * tenant.amount) - totalAmount;
      
      const html = template
        .replace('{{tenant}}', tenant.lastname + ", " + tenant.firstname)
        .replace('{{balance}}', formatAmount(totalBalance))
        .replace('{{total}}', formatAmount(totalAmount))
        .replace('{{price}}', formatAmount(tenant.amount))
        .replace('{{date}}', formatDate(tenant.dt))
        .replace('{{month}}', calculateMonthsAhead(tenant.dt));

      tenantDetailsContainer.innerHTML = html;
    }
  } catch (error) {
    console.error(error);
  }
}

function calculateMonthsAhead(date) {
  const today = new Date();
  const tenantDate = new Date(date);

  const monthsDiff = (tenantDate.getFullYear() - today.getFullYear()) * 12 + (tenantDate.getMonth() - today.getMonth());
  const daysDiff = tenantDate.getDate() - today.getDate();

  let monthsAhead = monthsDiff;

  if (monthsDiff > 0 && daysDiff < 0) {
    monthsAhead--;
  } else if (monthsDiff < 0 && daysDiff > 0) {
    monthsAhead++;
  }

  return Math.abs(monthsAhead);
}

function formatDate(dateString) {
  var parts = dateString.split('-');
  var year = parts[0];
  var month = parseInt(parts[1], 10) - 1;
  var day = parts[2];
  
  var dateObj = new Date(year, month, day);
  var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var monthName = monthNames[dateObj.getMonth()];
  var formattedDateString = monthName + ' ' + day + ', ' + year;
  
  return formattedDateString;
}

function formatAmount(amount) {
  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });

  return formatter.format(amount);
}

// Select dropdown with search
$(document).ready(function() {
  $('#tenant').select2({
      placeholder: 'Select a tenant',
      allowClear: true,
      width: '100%'
  });
});

// Cancel onclick event
function resetSelection() {
  // Reset the value of the select element
  document.getElementById('tenant').value = 'default';
  // Clear the generated tenant details HTML
  document.getElementById('tenant-details').innerHTML = '';
  // Reset the value of the invoice element
  document.getElementById('invoice').value = '';
}

// Delete button function
document.addEventListener('DOMContentLoaded', function() {
  const deleteButtons = document.querySelectorAll('.delete');

  deleteButtons.forEach(function(button) {
    button.addEventListener('click', function(event) {
      event.preventDefault();

      const dataId = this.dataset.id;
      const tableId = this.closest('table').id;
      
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          deleteData(tableId, dataId);
        }
      });
    });
  });

  function deleteData(tableId, id) {
    let deleteUrl = '';
    let redirectUrl = '';

    if (tableId === 'manage-apart') {
      deleteUrl = 'Stored_Data/delete_data.php?id=' + id + '&tableid=manage-apart';
      redirectUrl = 'apartments.php';
    } else if (tableId === 'manage-cat') {
      deleteUrl = 'Stored_Data/delete_data.php?id=' + id + '&tableid=manage-cat';
      redirectUrl = 'categories.php';
    } else if (tableId === 'manage-tent') {
      deleteUrl = 'Stored_Data/delete_data.php?id=' + id + '&tableid=manage-tent';
      redirectUrl = 'tenants.php';
    } else if (tableId === 'nested-manage-pay') {
      deleteUrl = 'Stored_Data/delete_data.php?id=' + id + '&tableid=nested-manage-pay';
      redirectUrl = 'payments.php';
    }
    
    fetch(deleteUrl, {
      method: 'POST'
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire(
            'Deleted!',
            'The data has been deleted.',
            'success'
          ).then(() => {
            window.location.replace(redirectUrl);
          });
        } else {
          Swal.fire(
            'Failed to delete',
            'An error occurred while deleting the data.',
            'error'
          );
        }
      });
  }
});

$(document).ready(function() {
  // Click event handler for the gallery button
  $(document).on("click", ".gallery-button", function() {
    var apartmentId = $(this).data("id");

    if (selectedApartmentId === apartmentId) {
      // Same button clicked, rotate back and hide pictures
      $(this).removeClass("active rotate");
      $(".images-container").slideUp(300, function() {
        $(this).empty();
        selectedApartmentId = ""; // Reset the selected apartment ID after hiding the pictures
      });
    } else {
      // Different button clicked, update active button and show pictures
      $(".gallery-button").removeClass("active rotate");
      $(this).addClass("active");

      var imagesContainer = $(".images-container");
      imagesContainer.slideUp(300, function() {
        $(this).empty();
        generateGallery(apartmentId);
        imagesContainer.slideDown(300, function() {
          enablePictureSelection(); // Enable picture selection after showing the pictures
        });
      });

      selectedApartmentId = apartmentId; // Update the selected apartment ID
    }
  });

  // Function to enable picture selection
  function enablePictureSelection() {
    // Click event for image container
    $(".images-container").off("click", ".picture-item");
    $(".images-container").on("click", ".picture-item", function() {
      $(this).toggleClass("selected");
  
      // Update the label showing the number of selected pictures
      var selectedCount = $(".picture-item.selected").length;
      $(".selected-label").remove(); // Remove existing selected label
  
      if (selectedCount > 0) {
        $(".new-button").hide();
        $(".image-header-container").append(
          `<div class="selected-label">
             <button class="deselect-button" title="Deselect">
               <i class="fas fa-times"></i>
             </button>
             ${selectedCount} selected
             <button class="delete-button" title="Delete">
               <i class="bi bi-trash delete-icon"></i>
             </button>
           </div>`
        );
      } else {
        $(".new-button").show();
      }
    });
  
    // Click event to remove selection when clicking outside the picture containers
    $(document).on("click", function(event) {
      if (
        !$(event.target).closest(".picture-item").length &&
        !$(event.target).closest(".image-header-container").length
      ) {
        $(".picture-item").removeClass("selected");
        $(".selected-label").remove();
        $(".new-button").show();
      }
    });
  
    // Click event for deselect button
    $(".image-header-container").off("click", ".deselect-button");
    $(".image-header-container").on("click", ".deselect-button", function(event) {
      event.stopPropagation();
      $(".picture-item.selected").removeClass("selected");
      $(".selected-label").remove();
      $(".new-button").show();
    });

    // Click event for delete button
    $(".image-header-container").off("click", ".delete-button");
    $(".image-header-container").on("click", ".delete-button", function() {
      event.stopPropagation();
      
      handleDeleteButtonClick();
    });
  }

  $(document).on("click", ".dots-button", function(event) {
    console.log('start');

    event.stopPropagation();
  
    var dropdownMenu = $(this).siblings(".dropdown-menu");
    dropdownMenu.toggle();
  
    handleEllipsisButtonClick();

    console.log('end');
  });
});

var selectedApartmentId = ""; // Update this variable with the selected apartment ID

function generateGallery(apartmentId) {
  var imagesContainer = $(".images-container");
  imagesContainer.empty();

  // Retrieve pictures from the database for the selected apartment ID
  $.ajax({
    url: "Stored_Data/get_pictures.php",
    method: "POST",
    dataType: "json",
    data: { apartmentId: apartmentId },
    success: function(data) {
      // Picture containers
      var pictureContainer = $("<div class='picture-container row'></div>");

      for (var i = 0; i < data.length; i++) {
        var picture = data[i];

        // Picture item
        var pictureItem = $("<div class='picture-item col-md-4' data-id='" + picture.id + "'></div>");

        // File name
        var fileName = $("<div class='file-name'><span class='icon'><i class='bi bi-file-image'></i></span>" + picture.filename + "</div>");
        pictureItem.append(fileName);

        // Picture
        var img = $("<img src='data:image/png;base64," + picture.picture + "'>");
        pictureItem.append(img);

        // Ellipsis button container
        var dotsButtonContainer = $("<div class='dots-button-container'></div>");

        // Dots button
        var dotsButton = $("<button class='dots-button' type='button'><i class='bi bi-three-dots-vertical'></i></button>");
        dotsButtonContainer.append(dotsButton);

        // Dropdown menu
        var dropdownMenu = $("<div class='dropdown-menu'></div>");
        var renameButton = $("<button class='dropdown-item rename-button' type='button'>Rename</button>");
        var deleteButton = $("<button class='dropdown-item delete-button' type='button'>Delete</button>");
        dropdownMenu.append(renameButton);
        dropdownMenu.append(deleteButton);
        dotsButtonContainer.append(dropdownMenu);

        // pictureItem.append(dotsButtonContainer);
        pictureContainer.append(pictureItem);
      }

      var imageHeaderContainer = $("<div class='image-header-container'></div>");
      var imageLabel = $("<label for='picture' class='picLabel'>Pictures > </label>");
      
      // Fetch the apartment name using the apartmentId
      $.ajax({
        url: "Stored_Data/get_apart.php",
        method: "POST",
        dataType: "json",
        data: { apartmentId: apartmentId },
        success: function(apartmentData) {
          // Access the apartment by index using a traditional for loop
          for (var i = 0; i < apartmentData.length; i++) {
            var currentApartment = apartmentData[i];
      
            // Access the id property of the current apartment object
            var apartmentId = currentApartment.id;
      
            // Perform any necessary operations with the apartmentId
            var matchingApartment = apartmentData.find(function(apartment) {
              return apartment.id === apartmentId;
            });
            
            var apartmentName = ""; // Default value
      
            if (matchingApartment && matchingApartment.apart) {
              if (matchingApartment.id == selectedApartmentId) {
                apartmentName = matchingApartment.apart;
                var apartmentNameElement = $("<span class='apartment-name'></span>").text(apartmentName);
                var arrowIcon = $("<i class='bi bi-chevron-right'></i>");
                imageLabel.empty().append("Pictures ", arrowIcon, " ", apartmentNameElement);
              }
            }
          }
        }
      });      

      var newButton = $("<button class='new-button btn btn-outline-secondary' type='button' data-id='" + apartmentId + "'><i class='bi bi-plus'></i> New</button>");

      imageHeaderContainer.append(imageLabel);
      imageHeaderContainer.append(newButton)
      imagesContainer.append(imageHeaderContainer);
      imagesContainer.append(pictureContainer);
      
      // Click event for the new button to add pictures
      newButton.on("click", function() {
        // Open file explorer to select pictures
        var input = $("<input type='file' multiple accept='image/*'>");
        input.on("change", function() {
          var files = input[0].files;

          // Filter files exceeding 40MB
          var validFiles = Array.from(files).filter(function(file) {
            var fileSize = file.size / (1024 * 1024); // File size in MB
            return fileSize <= 40;
          });

          validFiles.forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
              var dataURL = e.target.result;
              var fileName = file.name;
              var base64Data = dataURL.split(',')[1];

              $.ajax({
                url: "Stored_Data/insert_data.php",
                method: "POST",
                data: {
                  apartId: apartmentId,
                  fileName: fileName,
                  picture: base64Data
                },
                success: function(response) {
                  Swal.fire(
                    'Inserted!',
                    'The images have been added.',
                    'success'
                  ).then(function() {
                    generateGallery(apartmentId);
                  });
                },
                error: function(xhr, status, error) {
                  Swal.fire(
                    'Failed to delete',
                    'An error occurred while deleting the data.',
                    'error'
                  );
                }
              });
            };
            reader.readAsDataURL(file); // Read file as data URL
          });
        });
        input.click(); // Trigger click event on the input element
      });
    }
  });

  // Reapply event delegation and event handler for deselect button
  $(".images-container").off("click", ".deselect-button");
  $(".images-container").on("click", ".deselect-button", function(event) {
    event.stopPropagation();
    $(".picture-item.selected").removeClass("selected");
    $(".selected-label").remove();
    $(".new-button").show();
  });
  
  // Reapply event delegation and event handler for delete button
  $(".images-container").off("click", ".delete-button");
  $(".images-container").on("click", ".delete-button", function(event) {
    event.stopPropagation();
    handleDeleteButtonClick();
  });

  // // Event delegation for dots button
  // $(".images-container").off("click", ".dots-button");
  // $(".images-container").on("click", ".dots-button", function(event) {
  //   event.stopPropagation();
  
  //   handleEllipsisButtonClick(); // Call the function first
  
  //   // Toggle the dropdown menu
  //   $(this).siblings(".dropdown-menu").toggle();
  // });
}

// Function to handle ellipsis button click event
function handleEllipsisButtonClick() {
  // Event delegation for dropdown menu items
  $(".images-container").off("click", ".dropdown-item");
  $(".images-container").on("click", ".dropdown-item", function(event) {
    event.stopPropagation();

    // Get the selected picture item
    var pictureItem = $(this).closest(".picture-item");

    // Get the data-id of the picture item
    var pictureId = pictureItem.data("id");

    // Determine the action based on the clicked item
    if ($(this).hasClass("rename-button")) {
      // Handle rename button click
      renamePicture(pictureId);
    } else if ($(this).hasClass("delete-button")) {
      // Handle delete button click
      deletePicture(pictureId);
    }

    // Hide the dropdown menu
    $(".dropdown-menu").hide();
  });
}

// Function to handle delete button click event
function handleDeleteButtonClick() {
  event.stopPropagation();
  
  var selectedItems = $(".picture-item.selected");
  var selectedCount = selectedItems.length;

  if (selectedCount > 0) {
    Swal.fire({
      title: 'Delete ' + selectedCount + ' images?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        var imageIds = selectedItems.map(function() {
          return $(this).data('id');
        }).get();

        // Send AJAX request to delete images
        $.ajax({
          url: "Stored_Data/delete_data.php",
          method: "POST",
          data: { imageIds: imageIds },
          success: function(response) {
            // Handle success response
            Swal.fire(
              'Deleted!',
              'Your file(s) have been deleted.',
              'success'
            );

            // Remove selected picture items from the UI
            selectedItems.remove();
            $(".selected-label").remove();
            $(".new-button").show();
          },
          error: function(xhr, status, error) {
            // Handle error response
            Swal.fire(
              'Error!',
              'An error occurred while deleting the file(s).',
              'error'
            );
          }
        });
      }
    });
  }
}

// // Set the Occupied and Vacant button unclickable
// var unclickableBtn = document.getElementById('unclickable-btn');
// unclickableBtn.addEventListener('click', function(event) {
//   event.preventDefault(); // Prevent the default click behavior
// });

// // Scroll event listeners
// const scrollContainerElement = document.querySelector('.scroll-container');
// const scrollRightButton = document.getElementById('scrollRightButton');
// const scrollLeftButton = document.getElementById('scrollLeftButton');

// scrollContainerElement.addEventListener('mousedown', startDragging);
// scrollContainerElement.addEventListener('mousemove', handleDragging);
// scrollContainerElement.addEventListener('mouseup', stopDragging);
// scrollContainerElement.addEventListener('mouseleave', stopDragging);
// scrollContainerElement.addEventListener('wheel', handleMouseScroll);
// scrollLeftButton.addEventListener('click', scrollLeft);
// scrollRightButton.addEventListener('click', scrollRight);