<?php
include "db-connect.php";

if (isset($_POST["submit"])){
    $studentId = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $region = $_POST['region'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $birthday = $_POST['birthday'];

    $sql = "INSERT INTO `student_table`(`StudentID`, `Firstname`, `Surname`, `BirthDate`, `Gender`, `Region`, `Province`, `Municipality`, `Barangay`, `Email`)
                                VALUES ('$studentId','$first_name','$last_name','$birthday','$gender','$region','$province','$city','$barangay','$email')";

    if (mysqli_query($connect, $sql)) {
        
        header("Location: app.php?success=1");
        exit(); 
    } else {
        echo "Error: " . mysqli_error($connect);
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Record Management System</title>
    <link rel="stylesheet" href="generalCSS.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.cdnfonts.com/css/text" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body style="padding-top: 60px;">
    <nav class="navbar navbar-light fixed-top justify-content-center fw-bolder fs-3 mb-5"
        style="background-color: #6A2C70">
        <div class="col">
            <button class="btn btn-dark my-2 ms-5" style="font-weight:bolder; background-color:#F08A5D; border:none;"
                type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
                aria-controls="offcanvasScrolling"> Add New
                Student</button>
            <button class="btn btn-danger">Sort</button>
        </div>

        <div class="col">
            <span style="color:white;">Student Record Management System</span>
        </div>
        <div class="col">
            <span style="color:white;"></span>
        </div>
    </nav>

    <!--------- TABLE LIST --------->
    <div class="container-fluid" style="max-width: 100%; padding: 20px;">


        <table class="table table-hover text-center">
            <thead class="">
                <tr>
                    <th style="width: 100px; background-color:#9ddb76;" scope="col">Student ID</th>
                    <th style="background-color:#9ddb76;" scope="col">First Name</th>
                    <th style="width: 100px; background-color:#9ddb76;" scope="col">Last Name</th>
                    <th style="background-color:#9ddb76;" scope="col">Gender</th>
                    <th style="width: 105px; background-color:#9ddb76;" scope="col">Birthdate</th>
                    <th style="background-color:#9ddb76;" scope="col">Region</th>
                    <th style="background-color:#9ddb76;" scope="col">Province</th>
                    <th style="background-color:#9ddb76;" scope="col">Municipality</th>
                    <th style="background-color:#9ddb76;" scope="col">Barangay</th>
                    <th style="background-color:#9ddb76;" scope="col">Email</th>
                    <th style="width: 100px; background-color:#9ddb76;" scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
            // Load JSON data for address information
            $regions = json_decode(file_get_contents("addressJSON/region.json"), true);
            $provinces = json_decode(file_get_contents("addressJSON/province.json"), true);
            $cities = json_decode(file_get_contents("addressJSON/city.json"), true);
            $barangays = json_decode(file_get_contents("addressJSON/barangay.json"), true);

            // Function to get the address name by code
            function getAddressName($data, $code, $type) {
                foreach ($data as $item) {
                    if ($type == 'region' && $item['region_code'] == $code) {
                        return $item['region_name'];
                    } elseif ($type == 'province' && $item['province_code'] == $code) {
                        return $item['province_name'];
                    } elseif ($type == 'city' && $item['city_code'] == $code) {
                        return $item['city_name'];
                    } elseif ($type == 'barangay' && $item['brgy_code'] == $code) {
                        return $item['brgy_name'];
                    }
                }
                return null;
            }

            // Fetch students from the database
            $sql = "SELECT * FROM `student_table`";
            $result = mysqli_query($connect, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $studentId = $row["StudentID"];
                $formattedId = substr($studentId, 0, 2) . '-' . substr($studentId, 2, 1) . '-' . substr($studentId, 3);

                // Get address names from JSON data by matching codes
                $regionName = getAddressName($regions, $row["Region"], 'region');
                $provinceName = getAddressName($provinces, $row["Province"], 'province');
                $cityName = getAddressName($cities, $row["Municipality"], 'city');
                $barangayName = getAddressName($barangays, $row["Barangay"], 'barangay');
            ?>
                <tr>
                    <!-- Display the formatted StudentID -->
                    <td><?php echo $formattedId; ?></td>
                    <td><?php echo $row["Firstname"]; ?></td>
                    <td><?php echo $row["Surname"]; ?></td>
                    <td><?php echo $row["Gender"]; ?></td>
                    <td><?php echo $row["BirthDate"]; ?></td>
                    <!-- Display the address names -->
                    <td><?php echo $regionName; ?></td>
                    <td><?php echo $provinceName; ?></td>
                    <td><?php echo $cityName; ?></td>
                    <td><?php echo $barangayName; ?></td>
                    <td><?php echo $row["Email"]; ?></td>
                    <td>
                        <!-- Trigger button -->
                        <a href="edit.php?id=<?php echo $row["StudentID"]; ?>" class="link-dark"><i
                                class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                        <a href="delete.php?id=<?php echo $row["StudentID"]; ?>" class="link-dark"><i
                                class="fa-solid fa-trash fs-5"></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>


    <!--------- OFF CANVAS ADD --------->
    <div class="offcanvas offcanvas-end" style="margin-top: 4.25rem; width:700px; background-color:#F9ED69;"
        data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling"
        aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header justify-content-center">
            <h3 class="offcanvas-title" id="offcanvasScrollingLabel">Adding New Student</h5>
        </div>
        <div class="container">
            <div class="text-center mb-3">
                <p>Complete the form below to add new student.</p>
            </div>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="mb-3">
                    <label class="form-label" style="font-weight: bold;">Student ID:</label>
                    <input type="number" class="form-control" name="student_id" placeholder="12345678"
                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                        maxlength="8" autocomplete="off" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" style="font-weight: bold;">First Name:</label>
                        <input type="text" class="form-control" name="first_name" placeholder="Jose Mari"
                            autocomplete="off" required>
                    </div>

                    <div class="col">
                        <label class="form-label" style="font-weight: bold;">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Chan" autocomplete="off"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-weight: bold;">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="josemarichan@christmas.com"
                        autocomplete="off" required>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="region" class="form-label" style="font-weight: bold;">Region</label>
                        <select id="region" name="region" class="form-select" required>
                            <option value="">Select Region</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="province" class="form-label" style="font-weight: bold;">Province</label>
                        <select id="province" name="province" class="form-select" disabled required>
                            <option value="">Select Province</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="city" class="form-label" style="font-weight: bold;">Municipality</label>
                        <select id="city" name="city" class="form-select" disabled required>
                            <option value="">Select Municipality</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="barangay" class="form-label" style="font-weight: bold;">Barangay</label>
                        <select id="barangay" name="barangay" class="form-select" disabled required>
                            <option value="">Select Barangay</option>
                        </select>
                    </div>

                </div>

                <div class="mb-3">
                    <label for="birthday" class="form-label" style="font-weight: bold;">Birthday:</label>
                    <div class="input-group">
                        <span class="input-text">
                            <i class="bi bi-calendar"></i>
                        </span>
                        <input type="date" id="birthday" name="birthday" class="form-control"
                            style="text-transform: uppercase;color:#0d0d1f" required />
                    </div>
                </div>


                <div class="form-group mb-3">
                    <label style="font-weight: bold;">Gender:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="male" value="Male" required>
                    <label for="male" class="form-input-label">Male</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="female" value="Female" required>
                    <label for="female" class="form-input-label">Female</label>
                </div>

                <div class="justify-content-center d-flex">
                    <button type="submit" class="btn btn-success me-3" name="submit">Submit</button>
                    <a href="app.php" class="btn btn-danger ms-3" data-bs-dismiss="offcanvas">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT SECTION -->

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        ...
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script type="module" src="ph-address-selector.js"></script>

</body>

</html>