<?php
  require_once('lib/nusoap.php');
  $server = new nusoap_server;
  $server->register('ticketcontrol');
  // session_start();

  function ticketcontrol($id_number, $schedule_id, $departure_date, $departure_time)
  {
    $DB_HOST = "localhost:3308";
    $DB_USER = "root";
    $DB_PASSWORD = "";
    $DB_NAME = "online_bus_dispatch";
    
    // Database connection
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    // Variable
    $i = 0;               // loop variable
    $control = false;     // User record control        
    $error_counter = 0;

    if($conn == true)
    { 
      $query = "SELECT * FROM tblticket WHERE ticket_user_identity = '$id_number' AND ticket_schedule_id = '$schedule_id' AND ticket_departure_date = '$departure_date' AND ticket_departure_time = '$departure_time';";                
      $linker = mysqli_query($conn, $query);
      $couch_id = null;
      $ticket_price = null;    
      
      if (mysqli_num_rows($linker) > 0) {
        while($user_record = mysqli_fetch_assoc($linker)) { 
          $access = true; 
          $couch_id = $user_record["ticket_couch_id"];
          $ticket_price = $user_record["ticket_price"];
        }
      }
     
      //Access success
      if($access == true)
      {
        $query = "SELECT * FROM tblUser WHERE user_identity = '$id_number'";                
        $linker = mysqli_query($conn, $query);
               
        if (mysqli_num_rows($linker) > 0) {
          while($user_record = mysqli_fetch_assoc($linker)) { 
            $full_name = "".$user_record["user_name"]." ".$user_record["user_surname"]."";
          }

          $message = "Hi, ".$full_name.". You have a record. Your couch number is ".$couch_id." and ticket price ".$ticket_price.".";
          return $message;
        }
      }

      // Access denied
      else
      {
        /*
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "You don't have a ticket.",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");*/
        return "You don't have a ticket.";
        }
    }
    // DB connection failed
    else{
      die(" - Database Connection failed: ".mysqli_connect_error());   
    }
  }
  $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
  $server->service($HTTP_RAW_POST_DATA);
?>