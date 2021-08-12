<?php
require 'flight/Flight.php';
//Load Composer's autoloader
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


Flight::register('db', 'PDO', array('mysql:host=localhost;port=3306;dbname=flighttest', 'root', ''), function($db) {
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

Flight::route('/', function(){
    echo 'hello basic flight RESTFul api!';
});


  
  // This route responds to only POST requests for the '/users' url
  Flight::route('POST /register', function () {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);  
    $data = Flight::request()->data;
    $name = $data['name'];
    $email = $data['email'];
    $dob = $data['dob'];
    $password = $data['password'];
    $activation = md5(uniqid(rand(), true));
    $conn = Flight::db();
    $result = $conn->query("INSERT INTO user (name, email, dob, password,authkey) VALUES ('$name', '$email', '$dob','$password','$activation' )");

    try {
      //Server settings
     
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'mail.savaserturk.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = 'test@savaserturk.com';                     //SMTP username
      $mail->Password   = '1a2KnOB6G*k%';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
      //Recipients
      $mail->setFrom('test@savaserturk.com');
      $mail->addAddress($email);                                 //Name is optional
  
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Activate your account';
      $mail->Body    = 'Click on the link for activate your account <br> <a href="http://localhost:8080/api/activate/'.$activation.'">Click</a></b>';
  
      $mail->send();
      echo json_encode(array('status' => 200, 'message' => 'success'));
  } catch (Exception $e) {
    echo json_encode(array('status' => 200, 'message' => 'success',$mail->ErrorInfo));
  }
    
  });

  Flight::route('POST /login', function () {
    $data = Flight::request()->data;
    $email = $data['email'];
    $password = $data['password'];
    $conn = Flight::db();
   $result = $conn->query("SELECT * FROM user WHERE email = '".$email."' AND password='".$password."'  ");
   $info = $result->fetchAll();
   $rowcount = $result->rowCount();
   

    if($rowcount==1){
      echo json_encode(array('status' => 'success', 'message' => 'Logged in','authKey'=>$info[0]['authKey'],'isActive'=>$info[0]['isActive']));
    }else{
      echo json_encode(array('status' => 'fail', 'message' => 'Email not found'));
    }

  });

  Flight::route('POST /updateinfo', function () {
    $data = Flight::request()->data;
    $name = $data['name'];
    $newEmail = $data['email'];
    $dob = $data['dob'];
    $password = $data['password'];
    $authKey = $data['authKey'];
    $conn = Flight::db();
    $checkAuthKey = $conn->query("SELECT * FROM user WHERE authKey = '".$authKey."'  ");
    $rowcount = $checkAuthKey->rowCount();
    if($rowcount>0){
      $activateUser = $conn->query("UPDATE user SET name = '".$name."' , email = '".$newEmail."', dob='".$dob."', password='".$password."' WHERE authKey='".$authKey."'  ");
      echo json_encode(array('status' => 200, 'message' => "success"));
    }else{
      echo json_encode(array('status' => 204, 'message' => "error content not found"));
    }
    // $result = $conn->query("UPDATE user SET name = '".$name."', email = '$newEmail', dob = '1995-12-12',
    // password = '$password' WHERE authKey='".$authKey."' ");
    // echo json_encode(array('name' => $nameEE, 'email' => $dataEmail, 'dob' => "1995-12-12", 'password' =>$password ));

  

  });

  Flight::route('GET /activate/@activate', function ($activate) {
    $conn = Flight::db();
    $checkAuthKey = $conn->query("SELECT * FROM user WHERE authKey = '".$activate."'  ");
    $rowcount = $checkAuthKey->rowCount();
    if($rowcount>0){
      $activateUser = $conn->query("UPDATE user SET isActive = 'TRUE' WHERE authKey='".$activate."'  ");
      echo "<script>window.location.href='http://localhost:8080/client/login.php';</script>";
     
    }else{
      echo json_encode(array('status' => 204, 'message' => "error content not found"));
    }
    
  });

  Flight::route('GET /getUser/@authKey', function ($authKey) {
    $data = Flight::request()->data;
    $conn = Flight::db();
    $result = $conn->query("SELECT * FROM user WHERE authKey = '".$authKey."'  ");
    $getUser = $result->fetchAll();
    echo json_encode($getUser);
  
  });
  

Flight::start();
