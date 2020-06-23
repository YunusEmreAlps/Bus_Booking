<?php
  header('Access-Control-Allow-Origin: *');
  session_start();

  // Button click control
  if(isset($_POST['submit']))
  {
    // USER
    $id_number = $_POST['id_number'];
    $schedule_id = $_POST['schedule_id'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $departure_date = date("Y-m-d", strtotime($departure_date ));

    // Variable
    $i = 0;               // loop variable
    $control = false;     // User record control        
    $error_counter = 0;

    if((empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number))) && (empty($schedule_id) || (strlen($schedule_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $schedule_id))) && 
       (empty($departure_date) || (strlen($departure_date) > 10) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $departure_date))) && (empty($departure_time) || (strlen($departure_time) > 10) || (!preg_match("/^(2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $departure_time))))
    {
      $error_counter += 1;
       // Alert message
       $ticket_control_alert_message = array(
        "message" => "Please fill the required fields (ID Number, Schedule Id, Departure Date, Departure Time).",
        "type" => "danger"
      );
      $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
      header("Location: biletsorgu.php");
    }
    else 
    {
      // id_number control
      if(empty($id_number) || (strlen($id_number) > 11) || (!preg_match('/^[0-9]*$/', $id_number)))
      {
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "ID Number is required",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }
      // schedule_id control
      if(empty($schedule_id) || (strlen($schedule_id) > 10) || (!preg_match('/^[A-Z0-9]*$/', $schedule_id)))
      {
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "Schedule Id is required",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }
      // departure_date control
      if (empty($departure_date) || (strlen($departure_date) > 50) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $departure_date)))
      {
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "Departure Date is required",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }


       // departure_time control
       if (empty($departure_time) || (strlen($departure_time) > 50) || (!preg_match("/^(2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $departure_time)))
       {
         $error_counter += 1;
         // Alert message
         $ticket_control_alert_message = array(
           "message" => "Departure Time is required",
           "type" => "danger"
         );
         $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
         header("Location: biletsorgu.php");
       }

    }

    // ID Number length control
    if(strlen($id_number) != 11)
    {
      $error_counter += 1;
      // Alert message
      $ticket_control_alert_message = array(
        "message" => "ID Number length error",
        "type" => "danger"
      );
      $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
      header("Location: biletsorgu.php");
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
        $ticket_control_alert_message = array(
          "message" => "ID Number not valid",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }
    }

    // no error
    if($error_counter == 0)
    {
      require_once('lib/nusoap.php');
      $client = new nusoap_client('http://localhost/17010011005_FinalOdev/lib/soap/biletbul.php');

     //  $tickets = array('id_number' => $id_number, 'schedule_id' => ''.$schedule_id.'', 'departure_date' => ''.$departure_date.'', 'departure_time' => ''.$departure_time.''); 
      $ticketresult = $client->call('ticketcontrol', array('id_number' => $id_number, 'schedule_id' => $schedule_id, 'departure_date' => $departure_date, 'departure_time' => $departure_time));
      print_r($ticketresult);

      if($ticketresult  == null)
      {
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "Nothing Found",
          "type" => "danger"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }
      else{
        $error_counter += 1;
        // Alert message
        $ticket_control_alert_message = array(
          "message" => "".$ticketresult."",
          "type" => "success"
        );
        $_SESSION['ticket_control_alert_message'] = $ticket_control_alert_message;
        header("Location: biletsorgu.php");
      }
    }
  }
?>