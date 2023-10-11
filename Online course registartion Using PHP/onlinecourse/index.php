<?php
session_start();
error_reporting(0);
include("includes/config.php");
if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = $con->prepare("SELECT * FROM students WHERE StudentRegno=?");
    $query->bind_param("s", $regno);
    $query->execute();
    $result = $query->get_result();
    $num = $result->fetch_assoc();

    if ($num > 0) {
        $_SESSION['login'] = $_POST['regno'];
        $_SESSION['id'] = $num['studentRegno'];
        $_SESSION['sname'] = $num['studentName'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log = mysqli_query($con, "insert into userlog(studentRegno,userip,status) values('" . $_SESSION['login'] . "','$uip','$status')");
        header("location:http:change-password.php");
    } else {
        $_SESSION['errmsg'] = "Invalid Reg no or Password";
        header("Location: change-password.php");
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

    <title>Student Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Student Log In </h4>
                </div>
            </div>
            <span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg'] = ""); ?></span>
            <form name="admin" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label>Enter Reg no : </label>
                        <input type="text" name="regno" class="form-control" />
                        <label>Enter Password : </label>
                        <input type="password" name="password" class="form-control" />
                        <hr />
                        <button type="submit" name="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In </button>&nbsp;
                    </div>
            </form>
            <div class="col-md-6">
                <div class="alert alert-info">

                    <strong> Latest News / Updates</strong>
                    <marquee direction='up' scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                        <ul>
                            <?php
                            $sql = mysqli_query($con, "select * from news");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                                <li>
                                    <a href="news-details.php?nid=<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['newstitle']); ?>-<?php echo htmlentities($row['postingDate']); ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>