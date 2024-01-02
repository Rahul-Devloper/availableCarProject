<footer>
    <div class="container footer-container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-logo">
                <img src="../public/assets/img/car_haven_logo.png" alt="logo" width="100" height="75">
                </div>
            </div>
            <div class="col-md-4 footer-column">
                <ul>
                    <li><a href="findCars.php">Book Car</a></li>
                    <li><a href="signIn.php">Sign In</a></li>
                    <li><a href="signUp.php">Sign Up</a></li>
                </ul>
            </div>
            <div class="col-md-4 footer-column">
                <ul>
                    <li><a href="aboutUs.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="services.php">Services</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<section>
    <div class="row bg-white">
        <div class="col-md-12">
            <p class="text-center">&copy; 2024 AC. All rights reserved.</p>
        </div>
    </div>
</section>

</div>


<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "20%";
        document.getElementById("main").style.marginLeft = "20%";
        document.getElementById("arrowIcon").innerHTML = '';
        document.querySelector('.arrowIcon').style.backgroundColor = 'transparent';
        document.querySelector('.arrowIcon').style.border = 'none';
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        document.getElementById("arrowIcon").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/></svg>'
        document.querySelector('.arrowIcon').style.backgroundColor = '#0dcaf0';
        document.querySelector('.arrowIcon').style.border = '2px grey solid';
    }
</script>

<!-- aos script -->

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init();
</script>
<!-- findCars form validation -->
<script>
    function validateForm() {
        var currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0); // Set the time part to 00:00:00

        var selectedStartDate = new Date(document.getElementById('date1').value);
        selectedStartDate.setHours(0, 0, 0, 0);

        var selectedEndDate = new Date(document.getElementById('date2').value);
        selectedEndDate.setHours(0, 0, 0, 0);

        var selectedStartTime = document.getElementById('time1').value;
        var selectedEndTime = document.getElementById('time2').value;

        // Combine the selected start date and time
        var combinedStartDateTime = new Date(selectedStartDate);
        combinedStartDateTime.setHours(parseInt(selectedStartTime.split(":")[0]), parseInt(selectedStartTime.split(":")[1]));

        // Combine the selected end date and time
        var combinedEndDateTime = new Date(selectedEndDate);
        combinedEndDateTime.setHours(parseInt(selectedEndTime.split(":")[0]), parseInt(selectedEndTime.split(":")[1]));

        // Validate Start Date and End Date
        if (selectedStartDate < currentDate) {
            alert("Start date should be today or later.");
            return false;
        }

        if (selectedEndDate < currentDate) {
            alert("End date should be today or later.");
            return false;
        }

        if (selectedEndDate < selectedStartDate) {
            alert("End date should be equal to or later than the start date.");
            return false;
        }

        // Validate Start Time and End Time
        var newCurrentDate = new Date()
        if (combinedStartDateTime <= newCurrentDate || combinedEndDateTime <= newCurrentDate) {
            alert("Start time and end time should be now or later.");
            return false;
        }

        if (selectedStartDate.getTime() === selectedEndDate.getTime() && combinedEndDateTime <= combinedStartDateTime) {
            alert("End time should be later than the start time for the same day.");
            return false;
        }

        return true;
    }
</script>





<!-- find current location start -->
<script>
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // Get the latitude and longitude
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                console.log({
                    latitude,
                    longitude
                });

                // Use an API to get the postal code based on latitude and longitude
                getPostalCodeFromCoordinates(latitude, longitude);
            }, function(error) {
                console.error("Error getting current location:", error);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function getPostalCodeFromCoordinates(latitude, longitude) {
        // Your Google Maps Geocoding API key
        var apiKey = 'AIzaSyCiIKcQ1Gdk6vO8ARVez1nOlXuMhXI2Mcw';

        // Google Maps Geocoding API endpoint
        var geocodingApiEndpoint = 'https://maps.googleapis.com/maps/api/geocode/json';

        // Construct the API request URL
        var apiUrl = `${geocodingApiEndpoint}?latlng=${latitude},${longitude}&key=${apiKey}`;

        // Make an AJAX request to the API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                // Check if the API request was successful
                if (data.status === 'OK') {
                    // Extract the postal code from the API response
                    var postalCode = findPostalCode(data.results);
                    if (postalCode) {
                        // Update the location input field with the obtained postal code
                        document.getElementById('location').value = postalCode;
                    } else {
                        console.error('Postal code not found in the API response.');
                    }
                } else {
                    console.error(`Error in geocoding API response: ${data.status}`);
                }
            })
            .catch(error => {
                console.error('Error making API request:', error);
            });
    }

    // Function to find the postal code in the API response
    function findPostalCode(results) {
        for (var i = 0; i < results.length; i++) {
            var addressComponents = results[i].address_components;
            for (var j = 0; j < addressComponents.length; j++) {
                var types = addressComponents[j].types;
                if (types.includes('postal_code')) {
                    return addressComponents[j].long_name;
                }
            }
        }
        return null; // Postal code not found
    }
</script>
<!-- find current location end -->


<script>
    function toggleAvailability() {
        // ...
        const alwaysAvailableCheckbox = $('#alwaysAvailable');
        const modalAlwaysAvailableCheckbox = $('#modalAlwaysAvailable');
        const changeScheduleButton = $('#availabilityModalButton');

        if (alwaysAvailableCheckbox.prop('checked')) {
            // Disable the button if always available is checked
            changeScheduleButton.prop('disabled', true);
        } else {
            // Enable the button if always available is not checked
            changeScheduleButton.prop('disabled', false);
        }
    }

    function submitAvailabilityForm() {
        // Handle form submission based on the selected availability type
        const form = $('#availabilityForm');
        // Use AJAX to submit the form data to the server
        // ...

        // Close the modal after submission
        $('#availabilityModal').modal('hide');
    }


    function addAvailabilityRow() {
        var availabilityRows = document.getElementById('availabilityRows');
        var newRow = document.createElement('div');
        newRow.className = 'row mb-3';
        newRow.innerHTML = `
        <div class="col-md-4">
            <label for="day">Date:</label>
            <input name="car_availability_day[]" type="date" class="form-control" id="day">
        </div>
        <div class="col-md-4">
            <label for="startTime">Start Time:</label>
            <input name="availability_start_time[]" type="time" class="form-control" id="startTime">
        </div>
        <div class="col-md-4">
            <label for="endTime">End Time:</label>
            <input name="availability_end_time[]" type="time" class="form-control" id="endTime">
        </div>
    `;
        availabilityRows.appendChild(newRow);
    }
</script>

<script>
    function makeCarAvailable(carId) {
        // Perform an AJAX request to update the booking_status
        $.ajax({
            url: 'makeCarAvailable.php',
            type: 'POST',
            data: {
                carId: carId
            },
            success: function(response) {
                // Handle the response, e.g., refresh the page or update the UI
                alert(response.message);
            },
            error: function(error) {
                console.error('Error making car available:', error);
            }
        });
    }
</script>

<!-- car feedback handling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var feedbackForm = document.getElementById('feedbackForm');
        var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));

        var feedbackButton = document.querySelectorAll('.btn-primary[data-bs-target="#feedbackModal"]');
        var carIdInput = document.getElementById('carIdInput');

        feedbackButton.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the car_id from the data attribute
                var carId = this.getAttribute('data-car-id');

                // Set the car_id in the hidden input field
                carIdInput.value = carId;

                // Show the feedback modal
                feedbackModal.show();
            });
        });

        feedbackForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Get the selected rating, feedback text, and car_id
            var rating = document.querySelector('input[name="rating"]:checked').value;
            var feedbackText = document.querySelector('textarea[name="feedback"]').value;
            var carId = carIdInput.value;

            // Send feedback data to the server using fetch
            fetch('submitCarFeedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'car_id=' + encodeURIComponent(carId) + '&rating=' + encodeURIComponent(rating) + '&feedback=' + encodeURIComponent(feedbackText),
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Log the response from the server
                    // You can add code here to handle the server response, such as displaying a success message or updating the UI.
                })
                .catch(error => {
                    console.error('Error submitting feedback:', error);
                })
                .finally(() => {
                    // Close the modal after submitting feedback
                    feedbackModal.hide();
                    //reload page
                    location.reload();
                });
        });
    });
</script>


<!-- driver feedback handling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var feedbackForm = document.getElementById('driverFeedbackForm');
        var feedbackModal = new bootstrap.Modal(document.getElementById('driverFeedbackModal'));

        var feedbackButton = document.querySelectorAll('.btn-primary[data-bs-target="#driverFeedbackModal"]');
        var driverIdInput = document.getElementById('driverIdInput');

        feedbackButton.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the driver_id from the data attribute
                var driverId = this.getAttribute('data-driver-id');

                // Set the driver_id in the hidden input field
                driverIdInput.value = driverId;

                // Show the feedback modal
                feedbackModal.show();
            });
        });

        feedbackForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Get the selected rating, feedback text, and driver_id
            var rating = document.querySelector('input[name="rating"]:checked').value;
            var feedbackText = document.querySelector('textarea[name="feedback"]').value;
            var driverId = driverIdInput.value;

            // Send feedback data to the server using fetch
            fetch('submitDriverFeedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'driver_id=' + encodeURIComponent(driverId) + '&rating=' + encodeURIComponent(rating) + '&feedback=' + encodeURIComponent(feedbackText),
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Log the response from the server
                    // You can add code here to handle the server response, such as displaying a success message or updating the UI.
                })
                .catch(error => {
                    console.error('Error submitting feedback:', error);
                })
                .finally(() => {
                    // Close the modal after submitting feedback
                    feedbackModal.hide();
                    //reload page
                    location.reload();
                });
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script> -->
</body>

</html>