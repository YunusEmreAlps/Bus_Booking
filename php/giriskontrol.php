<?php
  include ("../mysql/connection.php");        // Database connection
  header('Access-Control-Allow-Origin: *');
  session_start();

  // Button click control
  if(isset($_POST['submit']))
  {
    // USER
    $id_number = $_POST['id_number'];
    $password = $_POST['password'];

    // Variable
    $i = 0;               // loop variable
    $control = false;     // User record control        
    $error_counter = 0;

    if((empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number))) && (empty($password) || (strlen($password) > 10) || (!preg_match('/^[A-Z0-9]*$/', $password))))
    {
      $error_counter += 1;
       // Alert message
       $login_control_alert_message = array(
        "message" => "Please fill the required fields (ID Number, Password).",
        "type" => "danger"
      );
      $_SESSION['login_control_alert_message'] = $login_control_alert_message;
      header("Location: ../index.php");
    }
    else {
      // id_number control
      if(empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number)))
      {
        $error_counter += 1;
        // Alert message
        $login_control_alert_message = array(
          "message" => "ID Number is required",
          "type" => "danger"
        );
        $_SESSION['login_control_alert_message'] = $login_control_alert_message;
        header("Location: ../index.php");
      }
      // password control
      if(empty($password) || (strlen($password) > 10) || (!preg_match('/^[A-Z0-9]*$/', $password)))
      {
        $error_counter += 1;
        // Alert message
        $login_control_alert_message = array(
          "message" => "Password is required",
          "type" => "danger"
        );
        $_SESSION['login_control_alert_message'] = $login_control_alert_message;
        header("Location: ../index.php");
      }
    }

    // ID Number length control
    if(strlen($id_number) != 11)
    {
      $error_counter += 1;
      // Alert message
      $login_control_alert_message = array(
        "message" => "ID Number length error",
        "type" => "danger"
      );
      $_SESSION['login_control_alert_message'] = $login_control_alert_message;
      header("Location: ../index.php");
    }
    else
    {
      // ID Number control
      // ID Number : 3 3 8 4 6 6 1 0 8 4  
      // index     : 1 2 3 4 5 6 7 8 10 11              

      $odd_total = 0;   // index(1, 3, 5, 7, 9)
      $even_total = 0;  // index(2, 4, 6, 8)
      $digit_total = 0;

      for($i=0; $i<11; $i++)
      {
          if(($i%2 == 0) && ($i != 10))
              $odd_total = $odd_total + ((int)$id_number[$i]);
          
          if(($i%2 != 0) && ($i != 9))
              $even_total = $even_total + ((int)$id_number[$i]);
                  
          
          if($i != 10)  
              $digit_total = $digit_total + ((int)$id_number[$i]);
      }

      if( ((($odd_total*7)- $even_total)%10  != $id_number[9]) && ($digit_total%10 != $id_number[10]))
      {
        $error_counter += 1;
        // Alert message
        $login_control_alert_message = array(
          "message" => "ID Number not valid",
          "type" => "danger"
        );
        $_SESSION['login_control_alert_message'] = $login_control_alert_message;
        header("Location: ../index.php");
      }
    }

    // no error
    if($error_counter == 0)
    {
      // Database connection
      if($conn == true)
      { 
        $query = "SELECT * FROM tbluser WHERE user_identity = '$id_number' AND  user_password = '$password';";                
        $linker = mysqli_query($conn, $query);

        if (mysqli_num_rows($linker) > 0) {
          while($user_record = mysqli_fetch_assoc($linker)) { 
            $control = true;
            $user_name = $user_record["user_name"];
            $user_surname = $user_record["user_surname"];
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_surname'] = $user_surname;
          }
        }

        //Access success
        if($control == true){
          $error_counter += 1;
          // Alert message
          $login_control_alert_message = array(
            "message" => "Log In success",
            "type" => "success"
          );
          $_SESSION['login_control_alert_message'] = $login_control_alert_message;
          header("Location: anasayfa.php"); 
        }

        // Access denied
        else{
          $error_counter += 1;
          // Alert message
          $login_control_alert_message = array(
            "message" => "Incorrect ID Number or Password",
            "type" => "danger"
          );
          $_SESSION['login_control_alert_message'] = $login_control_alert_message;
          header("Location: ../index.php");
        }
      }

      // DB connection failed
      else{
        die(" - Database Connection failed: ".mysqli_connect_error());   
      }
    }
  }
?>