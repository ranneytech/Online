<?php

session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $studentname = $_POST['studentname'];
        $studentregno = $_POST['studentregno'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Use password_hash for secure password storage
        $pincode = rand(100000, 999999);

        // Insert data into the database
        $stmt = $con->prepare("INSERT INTO students (studentName, StudentRegno, password, pincode) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $studentname, $studentregno, $password, $pincode);

        if ($stmt->execute()) {
            // Student registered successfully
            echo '<script>alert("Student Registered Successfully. Pincode is ' . $pincode . '")</script>';
            echo '<script>window.location.href="manage-students.php"</script>';
        } else {
            // Something went wrong
            echo '<script>alert("Something went wrong. Please try again.")</script>';
            echo '<script>window.location.href="manage-students.php"</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin | Student Registration</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>
    <!-- LOGO HEADER END-->
    <?php if ($_SESSION['alogin'] != "") {
        include('includes/menubar.php');
    }
    ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Student Registration </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Student Registration
                        </div>
                        <font color="green" align="center"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg'] = ""); ?></font>


                        <div class="panel-body">
                            <form name="dept" method="post" autocomplete="off">
                                <div class="form-group">
                                    <label for="studentname">Student Name </label>
                                    <input type="text" class="form-control" id="studentname" name="studentname" placeholder="Student Name" required />
                                </div>

                                <div class="form-group">
                                    <label for="studentregno">Student Reg No </label>
                                    <input type="text" class="form-control" id="studentregno" name="studentregno" onBlur="userAvailability()" placeholder="Student Reg no" required />
                                    <span id="user-availability-status1" style="font-size:12px;"></span>
                                </div>

                                <!--form for mail-->

                                <div class="form-group">
                                    <label for="password">Password </label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
                                </div>

                                <button type="submit" name="submit" id="submit" class="btn btn-default">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script>
        function userAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'regno=' + $("#studentregno").val(),
                type: "POST",
                success: function(data) {
                    $("#user-availability-status1").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
</body>

</html>