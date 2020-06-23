<?php
  include ("../mysql/connection.php");        // Database connection
  header('Access-Control-Allow-Origin: *');
  session_start();

  // Button click control
  if(isset($_POST['submit']))
  {
    // USER
    $id_number = $_POST['id_number'];
    $schedule_id = $_POST['schedule_id'];
    $couch_id = $_POST['couch_id'];

    // Variable
    $i = 0;                                 // loop variable
    $control = false;                       // User record control        
    $error_counter = 0;
    $couch_status = false;                  // empty seat


    if((empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number))) && (empty($schedule_id) || (strlen($schedule_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $schedule_id))) &&
       (empty($couch_id) || (strlen($couch_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $couch_id))))
    {
      $error_counter += 1;
       // Alert message
      $search_control_alert_message = array(
        "message" => "Please fill the required fields (ID Number, Schedule ID, Couch ID).",
        "type" => "danger"
      );
      $_SESSION['search_control_alert_message'] = $search_control_alert_message;
      header("Location: anasayfa.php");
    }
    else 
    {
      // id_number control
      if(empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number)))
      {
        $error_counter += 1;
        // Alert message
        $search_control_alert_message = array(
          "message" => "ID Number is required",
          "type" => "danger"
        );
        $_SESSION['search_control_alert_message'] = $search_control_alert_message;
        header("Location: anasayfa.php");
      }
      // schedule_id control
      if(empty($schedule_id) || (strlen($schedule_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $schedule_id)))
      {
        $error_counter += 1;
        // Alert message
        $search_control_alert_message = array(
          "message" => "Schedule Id is required",
          "type" => "danger"
        );
        $_SESSION['search_control_alert_message'] = $search_control_alert_message;
        header("Location: anasayfa.php");
      }
        // couch_id control
        if(empty($couch_id) || (strlen($couch_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $couch_id)))
        {
          $error_counter += 1;
          // Alert message
          $search_control_alert_message = array(
            "message" => "Couch Id is required",
            "type" => "danger"
          );
          $_SESSION['search_control_alert_message'] = $search_control_alert_message;
          header("Location: anasayfa.php");
        }
    }

    // ID Number length control
    if(strlen($id_number) != 11)
    {
      $error_counter += 1;
      // Alert message
      $search_control_alert_message = array(
        "message" => "ID Number length error",
        "type" => "danger"
      );
      $_SESSION['search_control_alert_message'] = $search_control_alert_message;
      header("Location: anasayfa.php");
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
        $search_control_alert_message = array(
          "message" => "ID Number not valid",
          "type" => "danger"
        );
        $_SESSION['search_control_alert_message'] = $search_control_alert_message;
        header("Location: anasayfa.php");
      }
    }


    // Seat Control
    if($conn == true)
    {
      $query = "SELECT * FROM tblticket;";
      $linker = mysqli_query($conn, $query);
  
      while($ticket_record = mysqli_fetch_assoc($linker)) 
      {
        if(($ticket_record['ticket_couch_id'] == $couch_id) && ($ticket_record['ticket_schedule_id'] == $schedule_id))
        {
          $couch_status = true;
          break;
        }
      }
    }

    if($couch_status == true)
    {
      $error_counter += 1;
      // Alert message
      $search_control_alert_message = array(
        "message" => "Seat is Not Empty",
        "type" => "danger"
      );
      $_SESSION['search_control_alert_message'] = $search_control_alert_message;
      header("Location: anasayfa.php");
    }

    if($error_counter == 0)
    {
      // Database connection
      if($conn == true)
      {
        $query = "SELECT * FROM tbluser WHERE user_identity = '$id_number';";
        $linker = mysqli_query($conn, $query);


        if (mysqli_num_rows($linker) > 0) {
          while($user_record = mysqli_fetch_assoc($linker)) { 
            $control = true; 
          }
        }

        //Access success  
        if($control == true)
        {
          $query = "SELECT * FROM tblschedule WHERE schedule_id = '$schedule_id';";                
          $linker = mysqli_query($conn, $query);
          $origin_location = null;
          $destination_location = null;
          $departure_date = null;
          $departure_time = null;

          if (mysqli_num_rows($linker) > 0) {
            while($schedule_record = mysqli_fetch_assoc($linker)) { 

              $origin_location = $schedule_record["origin_location"]; 
              $destination_location = $schedule_record["destination_location"]; 
              $departure_date = $schedule_record["departure_date"];
              $departure_time = $schedule_record["departure_time"];
            }
          }

          // Database addition
          $linker = mysqli_query($conn, "INSERT INTO tblticket(ticket_user_identity, ticket_schedule_id, ticket_departure_date, ticket_departure_time, ticket_couch_id, ticket_price) VALUES('$id_number', '$schedule_id', '$departure_date', '$departure_time', '$couch_id', 75);");
       
          if(!$linker)
          {
            // Alert message
            $search_control_alert_message  = array(
              "message" => "Query error",
              "type" => "danger"
            );
            $_SESSION['search_control_alert_message'] = $search_control_alert_message;
            header("Location: anasayfa.php"); 
          }
          if($linker)
          {
            $query = "SELECT * FROM tbluser WHERE user_identity = '$id_number';";                
            $linker = mysqli_query($conn, $query);
            $user_name = null;
            $user_surname = null;
  
            if (mysqli_num_rows($linker) > 0) {
              while($user_record = mysqli_fetch_assoc($linker)) { 
                $user_name = $user_record ["user_name"];
                $user_surname = $user_record["user_surname"];
              }
            }
  
            // file open
            $file = fopen("kullaniciadi_bilet.txt", "a") or die("Unable to open file!"); // append mode

            $message = "".$id_number." (".$user_name." ".$user_surname.")   ".$schedule_id." ".$origin_location." ".$destination_location." ".$departure_date." ".$departure_time." ".$couch_id."   75 Turkish Liras";
            fwrite($file, $message);
            fwrite($file,"<br>\r\n");
            fclose($file);
           
            // Alert message
            $search_control_alert_message= array(
            "message" => "Your purchase was successfull!",
            "type" => "success"
            );
            $_SESSION['search_control_alert_message'] = $search_control_alert_message;
            header("Location: anasayfa.php"); 
          }
        }
        else{
          $error_counter += 1;
          // Alert message
          $search_control_alert_message = array(
            "message" => "Incorrect ID Number",
            "type" => "danger"
          );
          $_SESSION['search_control_alert_message'] = $search_control_alert_message;
          header("Location: anasayfa.php"); 
        }
      } 
      else
      {
        die(" - Database Connection failed: ".mysqli_connect_error());   
      }
    }
  }
?>