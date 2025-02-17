<?php
include('Menu Bar.php');
include('connection.php');
if ($eid == "") {
  header('location:Login.php');
}
$sql = mysqli_query($con, "select * from create_account where email='$eid' ");
$result = mysqli_fetch_assoc($sql);
//print_r($result);
extract($_REQUEST);
error_reporting(1);
if (isset($savedata)) {
  $sql = mysqli_query($con, "select * from room_booking_details where email='$email' and room_type='$room_type' ");
  if (mysqli_num_rows($sql)) {
    $msg = "<h1 style='color:red'>You have already booked this room</h1>";
  } else {

    $sql = "insert into room_booking_details(name,email,phone,address,city,state,zip,contry,room_type,Occupancy,check_in_date,check_in_time,check_out_date) 
  values('$name','$email','$phone','$address','$city','$state','$zip','$country',
  '$room_type','$Occupancy','$cdate','$ctime','$codate')";
    if (mysqli_query($con, $sql)) {
      $msg = "<h1 style='color:blue'>You have Successfully booked this room</h1><h2><a href='order.php'>View </a></h2>";
    }
  }
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'credential.php';

if (isset($_POST["savedata"])) {
  $message = 'Hello ' . $_POST["name"] . ' You Have Booked A ' . $_POST["Occupancy"] . ' ' . $_POST['room_type'] . '';
  $host = 'Luxury Hotel';
  $msm = 'Please Check Your Email For Confirmation';
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL;  // Your gmail
    $mail->Password = PASS;  // Your gmail app password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom(EMAIL, 'Luxury Hotel'); // Your gmail

    $mail->addAddress($_POST["email"]);

    $mail->isHTML(true);

    $mail->Subject = 'Room Booking';
    $mail->Body    = '
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
  </head>
  <body>
  <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"></span>
    <div class="container">
             ' . $message . '<br/>
             <p>Your Checkin Date Starts From ' . $_POST["cdate"] . ' Checkout Is ' . $_POST["codate"] . '</p><br/>
             <p><i>Enjoy Your Stay In Luxury Hotel</i></p>
             <br/>
             <span><b>Thank You</b></span><br/>
                Regards<br/>
              ' . $host . '
    </div>
  </body>
  </html>
  ';
  
    $mail->send();
    echo $msm;
  } catch (Exception $e) {
    echo "Message could not be sent please check your internet or the email address";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Luxury Hotel.com</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="css/style.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="margin-top:50px;">
  <?php
  include('Menu Bar.php');
  ?>
  <div class="container-fluid text-center" id="primary">
    <!--Primary Id-->
    <h1>[ BOOKING Form ]</h1><br>
    <div class="container">
      <div class="row">
        <?php echo @$msg; ?>
        <!--Form Containe Start Here-->
        <form class="form-horizontal" action="Booking Form.php" method="post">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4> Name :</h4>
                </div>
                <div class="col-sm-8">
                  <input type="text" value="<?php echo $result['name']; ?>" readonly="readonly" class="form-control" name="name" placeholder="Enter Your Frist Name" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4>Email :</h4>
                </div>
                <div class="col-sm-8">
                  <input type="email" value="<?php echo $result['email']; ?>" readonly="readonly" class="form-control" name="email" placeholder="Enter Your Email-Id" required />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4>Mobile :</h4>
                </div>
                <div class="col-sm-8">
                  <input type="number" value="<?php echo $result['mobile']; ?>" readonly="readonly" class="form-control" name="phone" placeholder="Type Your Phone Number" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4>Address :</h4>
                </div>
                <div class="col-sm-8">
                  <textarea name="address" class="form-control" readonly="readonly" placeholder="Enter Your Address"><?php echo $result['address'];  ?></textarea>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4>Country</h4>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" readonly="readonly" value="<?php echo $result['country']; ?>" name="city" placeholder="Enter Your City Name" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4></h4>
                </div>
                <div class="col-sm-8">
                  <input type="hidden" name="state" class="form-control" placeholder="Enter Your State Name" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-4">
                  <h4></h4>
                </div>
                <div class="col-sm-8">
                  <input type="hidden" name="zip" class="form-control" placeholder="Enter Your Zip Code" required>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-5">
                  <h4>Room Type:</h4>
                </div>
                <div class="col-sm-7">
                  <select class="form-control" name="room_type" required>
                    <option>Deluxe Room</option>
                    <option>Luxurious Suite</option>
                    <option>Standard Room</option>
                    <option>Suite Room</option>
                    <option>Twin Deluxe Room</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-5">
                  <h4>check In Date :</h4>
                </div>
                <div class="col-sm-7">
                  <input type="date" name="cdate" class="form-control" required>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <!-- <div class="control-label col-sm-5"><h4>Check In Time:</h4></div>
                   <div class="col-sm-7">
                    <input type="time" name="ctime" class="form-control"required>
                  </div> -->
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <div class="control-label col-sm-5">
                  <h4>Check Out Date :</h4>
                </div>
                <div class="col-sm-7">
                  <input type="date" name="codate" class="form-control" required>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <div class="row">
                <label class="control-label col-sm-5">
                  <h4 id="top">Occupancy :</h4>
                </label>
                <div class="col-sm-7">
                  <div class="radio-inline"><input type="radio" value="Single" name="Occupancy" required>Single</div>
                  <div class="radio-inline"><input type="radio" value="Twin" name="Occupancy" required>Twin</div>
                  <div class="radio-inline"><input type="radio" value="Dubble" name="Occupancy" required>Dubble</div>
                </div>
              </div>
            </div>
            <input type="submit" value="submit" name="savedata" class="btn btn-danger" required />
          </div>
        </form><br>
      </div>
    </div>
  </div>
  </div>
  <script src="/js/jquery-3.6.0.min.js"></script>
  <script src="/js/sweetalert2.all.min.js"></script>
  <?php
  include('Footer.php')
  ?>
</body>

</html>