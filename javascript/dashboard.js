// Set the greeting dynamically to the current time
$(document).ready(function() {
    // Get the current hour
    var currentHour = new Date().getHours();
    var greeting = "";
    
    // Set the greeting based on the current hour
    if (currentHour < 12) {
        greeting = "Good morning";
    } else if (currentHour < 18) {
        greeting = "Good afternoon";
    } else {
        greeting = "Good evening";
    }

    // Update the greeting in the HTML element
    document.getElementById("greeting").textContent = greeting + ", " + userName;
});

$(document).ready(function() {
    const cardContainer = document.querySelector('.card-container');
    cardContainer.addEventListener('wheel', (event) => {
        event.preventDefault();
        cardContainer.scrollLeft += event.deltaY;
    });
    
    // Function to add the hover effect
    function addHoverEffect(event) {
        const card = event.target.closest('.card');
        card.classList.add('hovered');
        card.addEventListener('mousemove', updateRotation);
    }
    
    // Function to update the rotation based on mouse position
    function updateRotation(event) {
        const card = event.target.closest('.card');
        const rect = card.getBoundingClientRect();
        const mouseX = event.clientX - rect.left;
        const mouseY = event.clientY - rect.top;
        const rotateX = ((0.5 - mouseY / rect.height) * 20).toFixed(2); // Adjust the rotation sensitivity
        const rotateY = ((mouseX / rect.width) - 0.5) * 20; // Adjust the rotation sensitivity
        const invert = mouseY > rect.height / 2 ? -1 : 1; // Invert the rotation for the lower part of the card
        card.style.transform = `translate3d(0, -5px, 0) rotateX(${rotateX * invert}deg) rotateY(${rotateY}deg)`;
    }
    
    // Function to remove the hover effect
    function removeHoverEffect(event) {
        const card = event.target.closest('.card');
        card.style.transform = '';
        card.classList.remove('hovered');
        card.removeEventListener('mousemove', updateRotation);
    }
    
    // Get all the card elements
    const cards = document.querySelectorAll('.card');
    
    // Add event listeners to each card
    cards.forEach(card => {
        card.addEventListener('mouseenter', addHoverEffect);
        card.addEventListener('mouseleave', removeHoverEffect);
    });
});

$(document).ready(function() {
    function fetchCategories() {
        return $.ajax({
            url: 'Stored_Data/get_category.php',
            type: 'GET',
            dataType: 'json'
        });
    }

    function fetchApartments() {
        return $.ajax({
            url: 'Stored_Data/get_apart.php',
            type: 'GET',
            dataType: 'json'
        });
    }

    function fetchTenants() {
        return $.ajax({
            url: 'Stored_Data/get_tenants.php',
            type: 'GET',
            dataType: 'json'
        });
    }

    function createCard(apartment, category, tenant) {
        var card = $('<div class="card apartment-card" data-id="' + apartment.id + '">');
        var cardBody = $('<div class="card-body d-flex align-items-start">');
        var colorIndicator = $('<div class="color-indicator"></div>');
        var detailsWrapper = $('<div class="details-wrapper">');
        var apartmentName = $('<p class="apartment-name">' + apartment.apart + '</p>');
        var categoryName = $('<p class="category-name">' + category.name + '</p>');
        var tenantDetails = $('<div class="tenant-details">');
        var tenantName = $('<p class="tenant-name">' + (tenant ? tenant.firstname + ' ' + tenant.lastname : 'No tenant') + '</p>');
        var contactNumber = (tenant ? $('<p class="contact-number"><i class="fa fa-phone"></i>' + formatPhoneNumber(tenant.contact) + '</p>') : '');
        var statusDateWrapper = $('<div class="status-date">');
        var date = (tenant ? $('<p class="date">' + formatDate(tenant.dt) + '</p>') : '');
        var status = $('<div class="status ' + getStatusClass(apartment.status) + '"><i class="fa fa-circle"></i> ' + getStatusLabel(apartment.status) + '</div>');
    
        if (apartment.status == 0) {
            colorIndicator.addClass('indicator-available');
        } else if (apartment.status == 1) {
            colorIndicator.addClass('indicator-unavailable');
        } else if (apartment.status == 2) {
            colorIndicator.addClass('indicator-maintenance');
        }
    
        cardBody.append(colorIndicator);
        detailsWrapper.append(apartmentName);
        detailsWrapper.append(categoryName);
        tenantDetails.append(tenantName);
        tenantDetails.append(contactNumber);
        statusDateWrapper.append(date);
        statusDateWrapper.append(status);
        cardBody.append(detailsWrapper);
        cardBody.append(tenantDetails);
        cardBody.append(statusDateWrapper);
        card.append(cardBody);
    
        return card;
    }      

    function populateApartmentList(categoryData, apartmentData, tenantData) {
        var apartmentList = $('.apartment-list');

        if (apartmentData && apartmentData.length > 0) {
            $.each(apartmentData, function(index, apartment) {
                var category = categoryData.find(function(category) {
                    return category.id === apartment.category_id;
                });

                var tenant = tenantData.find(function(tenant) {
                    return tenant.apartment_id === apartment.id;
                });

                var card = createCard(apartment, category, tenant);
                apartmentList.append(card);
            });
        }
    }

    function handleError(xhr, status, error) {
        console.log('Error retrieving data: ' + error);
    }

    fetchCategories()
        .then(function(categoryData) {
            return Promise.all([categoryData, fetchApartments()]);
        })
        .then(function([categoryData, apartmentData]) {
            return Promise.all([categoryData, apartmentData, fetchTenants()]);
        })
        .then(function([categoryData, apartmentData, tenantData]) {
            populateApartmentList(categoryData, apartmentData, tenantData);
        })
        .catch(handleError);
});

// Get the status class based on the apartment status
function getStatusClass(status) {
    if (status == 0) {
        return 'available';
    } else if (status == 1) {
        return 'unavailable';
    } else if (status == 2) {
        return 'maintenance';
    }
}

// Get the status label based on the apartment status
function getStatusLabel(status) {
    if (status == 0) {
        return 'Vacant';
    } else if (status == 1) {
        return 'Occupied';
    } else if (status == 2) {
        return 'Maintenance';
    }
}

function formatPhoneNumber(number) {
    // Remove any non-digit characters from the number
    var digits = number.replace(/\D/g, '');
  
    // Check if the number has the expected length
    if (digits.length === 10) {
      // Format the number as "+63 123 456 7890"
      return ' +63 ' + digits.substr(0, 3) + ' ' + digits.substr(3, 3) + ' ' + digits.substr(6);
    } else {
      // Return the original number if it doesn't have the expected length
      return number;
    }
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

// Generate calendar
$(document).ready(function() {
    // Get current date
    var currentDate = new Date();
    var dateNow = new Date();

    // Function to get the name of the weekday
    function getWeekdayName(dayIndex) {
        var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        return weekdays[dayIndex];
    }

    // Function to get the start date and end date of the current week
    function getCurrentWeekRange() {
        var currentDayOfWeek = currentDate.getDay();
        var startDate = new Date(currentDate);
        startDate.setDate(startDate.getDate() - currentDayOfWeek);
        var endDate = new Date(currentDate);
        endDate.setDate(endDate.getDate() + (6 - currentDayOfWeek));
        return { startDate, endDate };
    }

    // Function to get the week number for the current month
    function getWeekNumber() {
        var firstDayOfYear = new Date(currentDate.getFullYear(), 0, 1);
        var pastDaysOfYear = (currentDate - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    }

    function getDefaultWeek() {
        // Copy date so don't modify original
        var d = new Date();
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() + 4 - (d.getDay() || 7)); // Set to nearest Thursday
        var yearStart = new Date(d.getFullYear(), 0, 1);
        var weekNo = Math.ceil(((d - yearStart) / 86400000 + 1) / 7); // Calculate week number
        return weekNo;
    }

    // Function to generate the weekdays and dates of the current week
    function generateCurrentWeek() {
        var { startDate, endDate } = getCurrentWeekRange();
        var calendarHeaderTitle = document.querySelector('.calendar-header-title');
        var calendarWeekdays = document.querySelector('.calendar-weekdays');
        var calendarDates = document.querySelector('.calendar-dates');

        calendarWeekdays.innerHTML = '';
        calendarDates.innerHTML = '';
    
        for (var date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
            var weekdayElement = document.createElement('div');
            weekdayElement.classList.add('weekday');
            weekdayElement.textContent = getWeekdayName(date.getDay());
    
            var dateElement = document.createElement('div');
            dateElement.classList.add('dates');
            dateElement.textContent = date.getDate();
            dateElement.style.lineHeight = '1.5'; // Adjust line height for vertical centering
    
            var dateContainer = document.createElement('div');
            dateContainer.classList.add('date-container');
            dateContainer.classList.add(dateNow.getMonth());

            if (date.getDate() === currentDate.getDate() && date.getMonth() === currentDate.getMonth()) {
                if (getWeekNumber() === (getDefaultWeek())){
                    dateContainer.classList.add('current-date-container');
                    dateElement.classList.add('current-date');
                }
            }
    
            dateContainer.appendChild(weekdayElement);
            dateContainer.appendChild(dateElement);
    
            calendarWeekdays.appendChild(dateContainer);

            if ((currentDate.getDate()) !== (dateNow.getDate())) {
                // Makes sure the current date is the highlighted date
                var element = document.querySelector('.date-container');
                element.classList.add(currentDate.getDate());
        
                var hasClass = element.classList.contains(dateNow.getDate());
                if (!hasClass) {
                    dateContainer.classList.remove('current-date-container');
                    dateElement.classList.remove('current-date');
                }
            }
        }

        // Update calendar header title with week number and month/year
        var weekNumber = getWeekNumber();
        var monthYear = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
        calendarHeaderTitle.textContent = 'Week ' + weekNumber + ' - ' + monthYear;

        // Add class to calendar-dates for week view
        calendarDates.classList.add('week-view');
    }

    function generateCalendar() {
        var calendarHeaderTitle = document.querySelector('.calendar-header-title');
        var calendarWeekdays = document.querySelector('.calendar-weekdays');
        var calendarDates = document.querySelector('.calendar-dates');
    
        // Clear the calendar content
        calendarWeekdays.innerHTML = '';
        calendarDates.innerHTML = '';
    
        // Get the current month and year
        var month = currentDate.getMonth();
        var year = currentDate.getFullYear();
    
        // Set the calendar header title to the current month and year
        calendarHeaderTitle.textContent = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
    
        // Get the number of days in the current month
        var numDays = new Date(year, month + 1, 0).getDate();
    
        // Generate the weekdays
        var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
        // Add weekdays to calendar weekdays container
        for (var i = 0; i < weekdays.length; i++) {
            var weekdayElement = document.createElement('div');
            weekdayElement.classList.add('weekday');
            weekdayElement.textContent = weekdays[i];
            calendarWeekdays.appendChild(weekdayElement);
        }
    
        // Calculate the starting day of the month (0: Sunday, 1: Monday, ..., 6: Saturday)
        var startDay = new Date(year, month, 1).getDay();
    
        // Generate the dates for the month
        for (var i = 0; i < startDay; i++) {
            var emptyDateContainer = document.createElement('div');
            emptyDateContainer.classList.add('empty-date-container');
            calendarDates.appendChild(emptyDateContainer);
        }
    
        for (var date = 1; date <= numDays; date++) {
            var dateContainer = document.createElement('div');
            dateContainer.classList.add('date-container');
            dateContainer.classList.add(currentDate.getMonth());
            dateContainer.textContent = date;
        
            if (date === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
                dateContainer.classList.add('current-date-container');
                dateContainer.classList.add('current-date');
            }
        
            calendarDates.appendChild(dateContainer);

            // Makes sure the current date is the highlighted date
            var element = document.querySelector('.date-container');
            currentDate.setDate(dateNow.getDate());

            element.classList.add(currentDate.getDate());
    
            var hasClass = element.classList.contains(dateNow.getMonth());
            if (!hasClass) {
                dateContainer.classList.remove('current-date-container');
                dateContainer.classList.remove('current-date');
            }
        }

        // Add class to calendar-dates for month view
        calendarDates.classList.add('calendar-view');
    }
    
    // Generate the initial calendar
    generateCurrentWeek();

    // Calendar header buttons
    var leftButton = document.querySelector('.calendar-header-left');
    var rightButton = document.querySelector('.calendar-header-right');

    leftButton.addEventListener('click', function() {
        if (document.querySelector('.calendar-view')) {
            currentDate.setMonth(currentDate.getMonth() - 1);
            currentDate.setDate(dateNow.getDate());

            generateCalendar();
        } else {
            currentDate.setDate(currentDate.getDate() - 7);;

            if ((getWeekNumber() === (getDefaultWeek()))){
                currentDate.setDate(dateNow.getDate());
            }

            generateCurrentWeek();
        }
    });

    rightButton.addEventListener('click', function() {
        if (document.querySelector('.calendar-view')) {
            currentDate.setMonth(currentDate.getMonth() + 1);
            currentDate.setDate(dateNow.getDate());
            
            generateCalendar();
        } else {
            currentDate.setDate(currentDate.getDate() + 7);

            if ((getWeekNumber() === (getDefaultWeek()))){
                currentDate.setDate(dateNow.getDate());
            }

            generateCurrentWeek();
        }
    });

    // Add event listener to calendar header title
    var calendarHeaderTitle = document.querySelector('.calendar-header-title');
    calendarHeaderTitle.addEventListener('click', function() {
        var isWeekView = document.querySelector('.week-view');
        var mainClass = document.querySelector('.calendar-dates');
        var transactionContainer = document.querySelector('.latest-transactions');
        var transactionList = document.querySelector('.transaction-list');

        if (isWeekView !== null) {
            mainClass.classList.remove('week-view');
            generateCalendar();

            transactionContainer.style.top = '120%';
            transactionList.style.maxHeight = '100px';

            // Remove the current payment item lists
            transactionList.innerHTML = '';

            // Generate the stacked payment item containers
            generatePaymentStack();
        } else {
            mainClass.classList.remove('calendar-view');
            generateCurrentWeek();

            transactionContainer.style.top = '70%';
            transactionList.style.maxHeight = '350px';

            // Remove the current stacked payment lists
            transactionList.innerHTML = '';

            // Generate the stacked payment item containers
            generatePaymentList();
        }
    });

    //Click event on apartment overview
    $(document).on('click', '.apartment-card', function() {
        // var mainClass = $('.side-container');
        // mainClass.find('.calendar').hide();

        // var transactionList = document.querySelector('.transaction-list');
        // transactionList.innerHTML = '';

        const containerSeparator = document.querySelector('.container-separator');
        containerSeparator.innerHTML = '';
        const apartmentsDetail = createApartmentsDetail();
        containerSeparator.appendChild(apartmentsDetail);

        // Generate the stacked payment item containers
        // generatePaymentStack();

        var latestTransaction = document.querySelector('.latest-transaction');
        // latestTransaction.style.position = ''
        // transactionList.style.marginTop = '10%';

        var apartmentId = $(this).data('id');
    
        $.ajax({
            url: 'Stored_Data/get_pictures.php',
            method: 'POST',
            data: { apartmentId: apartmentId },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                generateImages(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    function createApartmentsDetail() {
        const apartmentsDetail = document.createElement('div');
        apartmentsDetail.classList.add('apartments-detail');
    
        const detailsLabel = document.createElement('div');
        detailsLabel.classList.add('col-lg-10', 'details-label');
        const detailsLabelHeading = document.createElement('h5');
        detailsLabelHeading.textContent = 'Apartment Details';
        detailsLabel.appendChild(detailsLabelHeading);
        apartmentsDetail.appendChild(detailsLabel);
    
        const picturesContainer = document.createElement('div');
        picturesContainer.classList.add('pictures-container');
        const apartmentPicture = document.createElement('div');
        apartmentPicture.classList.add('apartment-picture');
        picturesContainer.appendChild(apartmentPicture);
        apartmentsDetail.appendChild(picturesContainer);
    
        const detailsContainer = document.createElement('div');
        detailsContainer.classList.add('details-container');
        apartmentsDetail.appendChild(detailsContainer);
    
        return apartmentsDetail;
    }

    function generateImages(images) {
        const apartmentPicture = document.querySelector('.apartment-picture');
        apartmentPicture.innerHTML = ''; // Clear previous content

        let currentIndex = 0;

        function displayImage(index, direction) {
            const imgElement = $('<img>').attr('src', 'data:image;base64,' + images[index].picture);
            const newImage = imgElement[0];
        
            // Add slide-out animation class to the previous image
            $(apartmentPicture).children().addClass('slide-out-' + direction);
        
            setTimeout(function() {
                // Remove previous image and append new image
                $(apartmentPicture).empty().append(newImage);
        
                // Add slide-in animation class to the new image
                $(newImage).addClass('slide-in-' + direction);
        
                // Remove slide-out animation classes after animation is complete
                setTimeout(function() {
                    $(apartmentPicture).children().removeClass('slide-out-' + direction);
                }, 500);
        
                regenerateButtons(); // Regenerate the navigation buttons when a new image is displayed
            }, 500);
        
            const previousIndex = (index - 1 + images.length) % images.length;
            preloadImage(previousIndex);
        }        
        
        function goToPreviousImage() {
            console.log('left');
        
            currentIndex = (currentIndex - 1 + images.length) % images.length;
        
            console.log(currentIndex);
            displayImage(currentIndex, 'left');
        
            const previousIndex = (currentIndex - 1 + images.length) % images.length;
            preloadImage(previousIndex);
        }
        
        function goToNextImage() {
            console.log('right');
        
            currentIndex = (currentIndex + 1) % images.length;
            console.log(currentIndex);
            displayImage(currentIndex, 'right');
        
            const nextIndex = (currentIndex + 1) % images.length;
            preloadImage(nextIndex);
        }
        
        function preloadImage(index) {
            const imgElement = $('<img>').attr('src', 'data:image;base64,' + images[index].picture);
            const image = imgElement[0];
        
            // Hide the image off-screen to preload it
            $(image).css('position', 'absolute');
            $(image).css('top', '-9999px');
            $(image).css('left', '-9999px');
        
            // Append the image to the apartment-picture container to trigger preloading
            $(apartmentPicture).append(image);
        
            // Remove the image from the apartment-picture container after preloading
            $(image).on('load', function() {
                $(this).remove();
            });
        }        

        function regenerateButtons() {
            // Remove existing navigation buttons
            $('.navigation-buttons').remove();

            // Create the navigation buttons dynamically
            const navigationButtons = document.createElement('div');
            navigationButtons.classList.add('navigation-buttons');
            const leftButton = document.createElement('button');
            leftButton.classList.add('left-button');
            leftButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            const rightButton = document.createElement('button');
            rightButton.classList.add('right-button');
            rightButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            navigationButtons.appendChild(leftButton);
            navigationButtons.appendChild(rightButton);

            // Append the buttons to the apartment-picture container
            $(apartmentPicture).append(navigationButtons);

            // Add event listeners to the buttons
            $(leftButton).click(function() {
                goToPreviousImage();
            });
            $(rightButton).click(function() {
                goToNextImage();
            });
        }

        displayImage(currentIndex);
    }

    // Fetch payment data using AJAX
    function fetchPayments() {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Stored_Data/get_payments.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var payments = JSON.parse(xhr.responseText);
                    resolve(payments);
                } else {
                    reject(xhr.statusText);
                }
            };
            xhr.onerror = function() {
                reject(xhr.statusText);
            };
            xhr.send();
        });
    }

    // Fetch tenants data using AJAX
    function getTenantName(tenantId) {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Stored_Data/get_tenants.php?id=' + tenantId);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var tenants = JSON.parse(xhr.responseText);
                    var tenantName = '';
                    for (var i = 0; i < tenants.length; i++) {
                      if (tenants[i].id === tenantId) {
                        tenantName = tenants[i].firstname + ' ' + tenants[i].lastname;
                        break;
                      }
                    }
                    resolve(tenantName);
                } else {
                    reject('Failed to retrieve tenant name');
                }
            };
            xhr.onerror = function() {
                reject('Error occurred while retrieving tenant name');
            };
            xhr.send();
        });
    }
    
    // Generate payment list
    function generatePaymentList() {
        var paymentListContainer = document.querySelector('.transaction-list');
    
        fetchPayments()
            .then(function(payments) {
                // Clear the existing content
                paymentListContainer.innerHTML = '';
        
                // Generate payment items
                payments.forEach(function(payment) {
                    var paymentItem = document.createElement('div');
                    paymentItem.classList.add('payment-item');
            
                    var invoiceContainer = document.createElement('div');
                    invoiceContainer.classList.add('invoice-container');
            
                    // Retrieve tenant's name using AJAX request
                    getTenantName(payment.tenant_id)
                        .then(function(tenantName) {
                            var tenantNameLabel = document.createElement('span');
                            tenantNameLabel.classList.add('tenant-names');
                            tenantNameLabel.textContent = tenantName;
                
                            invoiceContainer.appendChild(tenantNameLabel);
                        })
                        .catch(function(error) {
                            console.log('Error:', error);
                        });
            
                    var invoiceValue = document.createElement('span');
                    invoiceValue.classList.add('invoice-value');
                    invoiceValue.textContent = "#" + payment.invoice;
            
                    invoiceContainer.appendChild(invoiceValue);
            
                    var amountContainer = document.createElement('div');
                    amountContainer.classList.add('amount-container');
            
                    var amountValue = document.createElement('span');
                    amountValue.classList.add('amount-value');
                    amountValue.textContent = "₱" + formatAmount(payment.amount);
            
                    amountContainer.appendChild(amountValue);
            
                    paymentItem.appendChild(invoiceContainer);
                    paymentItem.appendChild(amountContainer);
            
                    paymentListContainer.appendChild(paymentItem);
                });
            })
            .catch(function(error) {
                console.log('Error:', error);
            });
    }

    function generatePaymentStack() {
        var paymentListContainer = document.querySelector('.transaction-list');
        paymentListContainer.innerHTML = ''; // Clear existing content
      
        var paymentStack = document.createElement('div');
        paymentStack.classList.add('payment-stack');
      
        var maxOpacity = 1;
        var opacityStep = 0.25;
        var visibleItemCount = 3; // Number of items visible behind the front item
      
        var startIndex = 0;
        var currentIndex = startIndex;
        var payments = []; // Array to store all payments
      
        var scrollAccumulator = 0;
        var scrollThreshold = 50; // Adjust this value to control sensitivity

        // Function to load payments
        function loadPayments(startIndex) {
            console.log('loadPayments');

            fetchPayments()
                .then(function(data) {
                    payments = data;

                    paymentStack.innerHTML = ''; // Clear existing payment items

                    payments
                        .slice(startIndex, startIndex + visibleItemCount + 1)
                        .forEach(function(payment, index) {
                            var paymentItem = createPaymentItem(payment, index);
                            var opacity = index === 0 ? maxOpacity : maxOpacity - (index * opacityStep); // Adjust the opacity calculation
                            paymentItem.style.opacity = opacity;
                            paymentStack.appendChild(paymentItem);
                        });
                })
                .catch(function(error) {
                    console.log('Error:', error);
                });
        }
      
        // Function to update current index
        function updateCurrentIndex(direction) {
            console.log('updateCurrentIndex');

            if (direction === 'down') {
                return currentIndex + 1;
            } else {
                return currentIndex - 1;
            }
        }
      
        // Function to handle wheel event
        function handleWheelEvent(event) {
            console.log('handleWheelEvent');

            var delta = event.deltaY;
            scrollAccumulator += delta;

            if (Math.abs(scrollAccumulator) >= scrollThreshold) {
                var direction = scrollAccumulator > 0 ? 'down' : 'up';
                console.log(direction);
        
                // Reset the accumulator after detecting the scroll direction
                scrollAccumulator = 0;

                var paymentItems = paymentStack.getElementsByClassName('stacked-payment');
                var frontPaymentItem = paymentItems[0];

                if (direction === 'down' && currentIndex + visibleItemCount >= payments.length + 2) {
                    return; // Stop the scroll function when reaching the last data or container
                }
            
                if (direction === 'down') {
                    if (currentIndex + visibleItemCount < payments.length - 1) {
                        var nextPayment = payments[currentIndex + visibleItemCount + 1];
                        var nextPaymentItem = createPaymentItem(
                            nextPayment,
                            currentIndex + visibleItemCount + 1
                        );
                
                        paymentStack.appendChild(nextPaymentItem);
                        paymentItems = paymentStack.getElementsByClassName('stacked-payment');
                    }
            
                    animateScrollDown(paymentItems, frontPaymentItem);
                } else {
                    if (currentIndex > 0) {
                        var prevPayment = payments[currentIndex - 1];
                        var prevPaymentItem = createPaymentItem(prevPayment, currentIndex - 1);
                
                        paymentStack.insertBefore(prevPaymentItem, frontPaymentItem);
                        paymentItems = paymentStack.getElementsByClassName('stacked-payment');
                        paymentStack.scrollBy(0, frontPaymentItem.offsetHeight); // Adjust scroll position to prevent jumpiness
                    }
            
                    animateScrollUp(paymentItems, frontPaymentItem);
                }
            
                currentIndex = updateCurrentIndex(direction);
            }
        }
      
        // Add wheel event listener
        paymentStack.addEventListener('wheel', handleWheelEvent);
      
        // Load initial set of payments
        loadPayments(startIndex);
      
        // Append the payment stack to the container
        paymentListContainer.appendChild(paymentStack);

        function createPaymentItem(payment, index) {
            console.log('createPaymentItem');
    
            var paymentItem = document.createElement('div');
            paymentItem.classList.add('stacked-payment');
        
            var invoiceContainer = document.createElement('div');
            invoiceContainer.classList.add('invoice-container');
        
            // Retrieve tenant's name using AJAX request
            getTenantName(payment.tenant_id)
                .then(function(tenantName) {
                    var tenantNameLabel = document.createElement('span');
                    tenantNameLabel.classList.add('tenants-name');
                    tenantNameLabel.textContent = tenantName;
            
                    invoiceContainer.appendChild(tenantNameLabel);
                })
                .catch(function(error) {
                    console.log('Error:', error);
                });
        
            var invoiceValue = document.createElement('span');
            invoiceValue.classList.add('invoice-value');
            invoiceValue.textContent = '#' + payment.invoice;
        
            invoiceContainer.appendChild(invoiceValue);
        
            var amountContainer = document.createElement('div');
            amountContainer.classList.add('amount-container');
        
            var amountValue = document.createElement('span');
            amountValue.classList.add('amount-values');
            amountValue.textContent = '₱' + formatAmount(payment.amount);
        
            amountContainer.appendChild(amountValue);
        
            paymentItem.appendChild(invoiceContainer);
            paymentItem.appendChild(amountContainer);
        
            paymentItem.style.transform = 'translate3d(0, 0, ' + (-50 * index) + 'px)'; // Adjust the depth of each payment item
        
            return paymentItem;
        }
        
        function animateScrollDown(paymentItems, frontPaymentItem) {
            console.log('animateScrollDown');
        
            frontPaymentItem.style.transform = 'rotateX(90deg)';
            frontPaymentItem.style.transition = 'transform 0.5s ease';
        
            setTimeout(function() {
                frontPaymentItem.style.display = 'none';
                frontPaymentItem.remove();
        
                // Update positions and opacity of all payment items
                paymentItems = paymentStack.getElementsByClassName('stacked-payment');
                for (var i = 0; i < paymentItems.length; i++) {
                    paymentItems[i].style.transform = 'translate3d(0, 0, ' + (-50 * i) + 'px)';
                    paymentItems[i].style.opacity = maxOpacity - i * opacityStep;
                }
            }, 500);
        }
        
        function animateScrollUp(paymentItems, frontPaymentItem) {
            console.log('animateScrollUp');
        
            var itemHeight = frontPaymentItem.offsetHeight;
        
            // Move the frontPaymentItem and all subsequent paymentItems up
            for (var i = 0; i < paymentItems.length; i++) {
                var paymentItem = paymentItems[i];
                paymentItem.style.transition = 'transform 0.5s ease';
                paymentItem.style.transform = 'translate3d(0, 0, ' + (-50 * i) + 'px)';
            }
        
            setTimeout(function() {
                frontPaymentItem.style.transition = '';
                frontPaymentItem.style.transform = 'rotateX(0deg)';
                frontPaymentItem.style.opacity = 1; // Set opacity to 1 for full visibility
        
                var lastPayment = payments[currentIndex + visibleItemCount];
                var lastPaymentItem = createPaymentItem(lastPayment, currentIndex + visibleItemCount);
                lastPaymentItem.style.opacity = 1; // Set opacity to 1 for full visibility
                paymentStack.appendChild(lastPaymentItem);
        
                setTimeout(function() {
                    lastPaymentItem.style.transform = 'translate3d(0, 0, ' + (-50 * (paymentItems.length - 1)) + 'px)';
                    lastPaymentItem.style.transition = 'transform 0.5s ease';
        
                    setTimeout(function() {
                        frontPaymentItem.remove();
        
                        // Update the paymentItems array with the new lastPaymentItem
                        paymentItems[0] = lastPaymentItem;
                    }, 500);
                }, 50);
            }, 500);
        }        
    }

    function formatAmount(amount) {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    
        return formatter.format(amount);
    }
    
    // Call the function to generate the payment list
    generatePaymentList(); 
});