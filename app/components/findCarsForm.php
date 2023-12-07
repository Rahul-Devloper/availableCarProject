<form method="post" action="findCars.php" class="box-design">
    <div class="form-group row mb-3">
        <label for="location" class="col-sm-2 col-form-label">Location</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="location" placeholder="Enter Postal Code">
        </div>
    </div>
    <div class="form-group row">
        <label for="date1" class="col-sm-2 col-form-label">Date and Time</label>
        <div class="col-sm-4">
            <input type="date" class="form-control" id="date1">
        </div>
        <div class="col-sm-6">
            <input type="time" class="form-control" id="time1">
        </div>
    </div>
    <div class="form-group row">
        <label for="date2" class="col-sm-2 col-form-label">Date and Time</label>
        <div class="col-sm-4">
            <input type="date" class="form-control" id="date2">
        </div>
        <div class="col-sm-6">
            <input type="time" class="form-control" id="time2">
        </div>
    </div>
    <!-- Submit button to add a car -->
    <div class="col-12 btn-modification">
        <button type="submit" name="find_car" class="btn btn-rounded">Find Car</button>
    </div>
</form>