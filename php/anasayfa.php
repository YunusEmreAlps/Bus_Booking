<?php
  include ("../mysql/connection.php");        // Database connection
  header("Access-Control-Allow-Origin: *");  
  session_start();

  // Variable
  $total_expedition_number = 0; 
  $origin_location = null;
  $destination_location = null;
  $departure_date = null;
  $purchase_seat = array();
  $cnt = false;                   // website login

  // Button click control
  if(isset($_POST['submit']))
  {
    $origin_location = $_POST['origin_location'];
    $destination_location = $_POST['destination_location'];
    $departure_date = $_POST['departure_date'];
    $departure_date = date("Y-m-d", strtotime($departure_date ));
 
    // Variable
    $i = 0;               // loop variable
    $access = false;     //  Database control        
    $error_counter = 0;
 
    if((empty($origin_location) || (strlen($origin_location) > 50) || (!preg_match('/^[A-Z0-9]*$/', $origin_location))) && (empty($destination_location) || (strlen($destination_location) > 50) || (!preg_match('/^[a-zA-Z0-9]*$/', $destination_location))) &&
       (empty($departure_date) || (strlen($departure_date) > 50) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $departure_date))))
     {
       $error_counter += 1;
        // Alert message
        $search_control_alert_message = array(
         "message" => "Please fill the required fields (Origin Location, Destination Location and Departure Date).",
         "type" => "danger"
       );
       $_SESSION['search_control_alert_message'] = $search_control_alert_message;
       header("Location: anasayfa.php");
     }
     else {
       // origin_location control
       if(empty($origin_location) || (strlen($origin_location) > 50) || (!preg_match('/^[A-Z0-9]*$/', $origin_location))) 
       {
         $error_counter += 1;
         // Alert message
         $search_control_alert_message = array(
           "message" => "Origin Location is required",
           "type" => "danger"
         );
         $_SESSION['search_control_alert_message'] = $search_control_alert_message;
         header("Location: anasayfa.php");
       }
       // destination_location control
       if(empty($destination_location) || (strlen($destination_location) > 50) || (!preg_match('/^[a-zA-Z0-9]*$/', $destination_location)))
       {
         $error_counter += 1;
         // Alert message
         $search_control_alert_message = array(
           "message" => "Destination location is required",
           "type" => "danger"
         );
         $_SESSION['search_control_alert_message'] = $search_control_alert_message;
         header("Location: anasayfa.php");
       }
        // departure_date control
        if (empty($departure_date) || (strlen($departure_date) > 50) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $departure_date)))
        {
          $error_counter += 1;
          // Alert message
          $search_control_alert_message = array(
            "message" => "Departure Date is required",
            "type" => "danger"
          );
          $_SESSION['search_control_alert_message'] = $search_control_alert_message;
          header("Location: anasayfa.php");
        }
     }
 
 
     // no error
     if($error_counter == 0)
     {
       // Database connection
       if($conn == true)
       { 
         $query = "SELECT * FROM tblschedule WHERE origin_location = '$origin_location' AND  destination_location = '$destination_location' AND departure_date = '$departure_date';";                
         $linker = mysqli_query($conn, $query);
         
         if (mysqli_num_rows($linker) > 0) {
           while($user_record = mysqli_fetch_assoc($linker)) { 
             $access = true; 
           }
         }
 
         //Access success
         if($access == true)
         {

         }
 
         // Access denied
         else
         {
           $error_counter += 1;
           // Alert message
           $search_control_alert_message = array(
             "message" => "Access denied.",
             "type" => "danger"
           );
           $_SESSION['search_control_alert_message'] = $search_control_alert_message;
           header("Location: anasayfa.php");
         }
       }
 
       // DB connection failed
       else{
         die(" - Database Connection failed: ".mysqli_connect_error());   
       }
     }
   }
?>

<!-- Desing -->
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/new-age.min.css" rel="stylesheet">
  </head>
  
  <body>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top display-1" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
          <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="../img/icon2.png" width="150" height="50"></img></a>  
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars" style="font-size:18px;"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger text-dark" style="font-size: small;" href="../soap/biletsorgu.php">Ticket Control</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Schedule -->
    <section class="container">
      <div class="container">
        <div class="section-heading text-center p-3">
          <h2>Schedules</h2>

          <!-- Welcome message -->
          <?php if((isset($_SESSION['user_name'])) && (isset($_SESSION['user_surname']))) { ?>
            <span class="text-muted" style="font-size:24px;">
              <?php echo "Welcome ".$_SESSION['user_name']." ".$_SESSION['user_surname'].""; ?>
            </span>
            <?php unset($_SESSION['user_name'])?>
            <?php unset($_SESSION['user_surname'])?>
          <?php } ?>

          <hr/>

          <!-- List All Schedule -->
            <a type="button" data-toggle="collapse" class="p-3" href="#schLister" role="button" aria-expanded="false" aria-controls="schLister">
            <i class="fa fa-calendar-check text-info pt-3 mt-3"></i> All Schedule
          </a>   
          <div class="collapse" id ="schLister">
            <div class="card card-body">
              <form action="" method="POST" autocomplete="off">
              <select class="form-control">
                <?php 
                     $linker = mysqli_query($conn, "SELECT * FROM tblschedule;");
                     while($schedule_record = mysqli_fetch_assoc($linker)) {
                      echo "<option value='".$schedule_record["schedule_id"]."'><span class='pl-5 pr-5'>".$schedule_record["origin_location"]."  ->   ".$schedule_record["destination_location"]." &nbsp;&nbsp;</span><span>".$schedule_record["departure_date"]." &nbsp;&nbsp; ".$schedule_record["departure_time"]."</span></option>";
                    }
                  ?>
              </select>
              </form>
            </div>
          </div>
          <hr/>  
          <!-- List All Schedule -->          

          <!-- Schedule Search -->
          <div class="mt-3 ml-5 mb-5 mr-5">
            <!-- Error message -->
            <?php if(isset($_SESSION['search_control_alert_message'])) {?>
              <div class="alert alert-<?php echo $_SESSION['search_control_alert_message']['type']?>"> 
                <?php echo $_SESSION['search_control_alert_message']['message']?>
              </div>
              <?php unset($_SESSION['search_control_alert_message'])?>
            <?php } ?>
            <!-- Error message -->
            <form action="anasayfa.php" method="POST" autocomplete="off">
              <div class="row mt-3 mb-3">
                <!-- Origin Location -->
                <div class="col-md-4 col-sm-8 text-left mt-2 mb-2">
                  <label for="origin-location-label" data-toggle="tooltip" data-placement="top" title="From">From <i class="fa fa-home" aria-hidden="true"></i></label>
                  <input class="form-control" type="text" maxlength="50" name="origin_location" oninput="this.value = this.value.toUpperCase()"  required/>
                </div>
                <!-- Origin Location -->
                <!-- Destination Location-->
                <div class="col-md-4 col-sm-8 text-left mt-2 mb-2">
                  <label for="destination-location-label" data-toggle="tooltip" data-placement="top" title="To">To <i class='fas fa-hotel'></i></label>
                  <input class="form-control" type="text" maxlength="50" name="destination_location" oninput="this.value = this.value.toUpperCase()" required/>
                </div>
                <!-- Destination Location-->
                <!-- Date -->
                <div class="col-md-4  col-sm-8 text-left mt-2 mb-2">
                  <label for="date-label">Date</label>
                  <input class="form-control" type="date" name="departure_date" required required/>
                </div> 
                <!-- Date -->
              </div>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-block btn-primary" style="border-radius:5px;" value="Submit" name="submit" id="submit">Search</button> 
                </div>
                <div class="col">
                  <button type="reset" class="btn btn-block btn-danger"  style="border-radius:5px;" value="Reset">Reset</button>
                </div>
              </div> 
            </form>
          </div>
          <hr/> 
        </div>

        <!-- List data -->
        <ul class="container" type="none" style="list-style-type: none;">
          <?php
            require_once ("../mysql/connection.php");
            $main_linker = mysqli_query($conn, "SELECT * FROM tblschedule WHERE origin_location = '$origin_location' AND destination_location = '$destination_location' AND departure_date = '$departure_date';");
              if($main_linker)
              {
                while($expedition_record = mysqli_fetch_assoc($main_linker)) { 
                  $GLOBALS["total_expedition_number"] += 1;
              }
            }
          ?>

          <?php if($GLOBALS["total_expedition_number"] != 0) { ?>
          <?php 
            $main_linker = mysqli_query($conn, "SELECT * FROM tblschedule WHERE origin_location = '$origin_location' AND destination_location = '$destination_location' AND departure_date = '$departure_date';");
            while($expedition_record = mysqli_fetch_assoc($main_linker)) {
          ?>
          <li id="el<?php echo $GLOBALS["total_expedition_number"];?>">
            <div class="accordion" id="accordionExample2">
              <div class="card border-dark mt-1 mb-1" style="border-radius:5px;">
                <div class="card-header bg-dark" type="button" data-toggle="collapse" data-target="#collapse<?php echo $GLOBALS["total_expedition_number"]; ?>" aria-expanded="true" aria-controls="collapseOne">
                  <h5 class="mb-0">
                    <button class="btn btn-link text-dark">
                      <span class="text-light text-md-left text-sm-center pl-md-5">
                      <?php // Schedule            
                        $linker = mysqli_query($conn, "SELECT * FROM tblschedule;");
                        while($schedule_record = mysqli_fetch_assoc($linker)) {
                          if($schedule_record["schedule_id"] == $expedition_record["schedule_id"]){
                            echo " ".$schedule_record["schedule_id"]." &nbsp;&nbsp; ".$schedule_record["origin_location"]." (From) -> ".$schedule_record["destination_location"]." (To)" ;  
                            break;
                          }
                        }
                      ?>
                      
                      </span>
                      <span class="text-light ml-md-5 pl-md-5 text-sm-center">
                        <?php // Destination information           
                          $linker = mysqli_query($conn, "SELECT * FROM tblschedule;");
                          while($schedule_record = mysqli_fetch_assoc($linker)) {
                            if($schedule_record["schedule_id"] == $expedition_record["schedule_id"]){
                              echo " ".$schedule_record["departure_date"]." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$schedule_record["departure_time"]."" ;  
                              break;
                            }
                          }
                        ?>
                      </span>
                      <span class="text-light ml-md-5 pl-md-5 text-sm-center">
                        <?php // Destination information           
                          $linker = mysqli_query($conn, "SELECT * FROM tblticket WHERE ticket_schedule_id = ".$expedition_record["schedule_id"]." ;");
                          $ticket_num = mysqli_num_rows($linker);
                          $linker = mysqli_query($conn, "SELECT * FROM tblcouch;");
                          $allseat_num = mysqli_num_rows($linker);
                          if(($allseat_num - $ticket_num) > 0)
                          {
                            echo $allseat_num - $ticket_num ." Empty Seat"; 
                          }
                          else if(($allseat_num - $ticket_num) == 0)
                          {
                            echo "Full";   
                          }
                        ?>
                      </span>    
                    </button>
                  </h5>
                </div>

                <div id="collapse<?php echo $GLOBALS["total_expedition_number"];?>" class="collapse show">

                  <!-- All Seat-->
                  <div class="card-body mt-2">
                    <div class="card-header"> 
                      <h5 class="text-center">All Seat</h5>
                    </div> 
                    <div class="card-body text-center">
                      <span class="text-info">
                        <?php // seat
                          $linker = mysqli_query($conn, "SELECT * FROM tblcouch;");
                          while($seat_record = mysqli_fetch_assoc($linker)) {
                            echo  " ".$seat_record['couch_id'].""; 
                          }
                        ?>
                      </span>
                  </div>   
                  </div>     
                  <!-- All Seat -->          
                
                  <!-- Purchased Seat-->
                  <div class="card-body mt-2">
                    <div class="card-header"> 
                      <h5 class="text-center">Purchased Seat</h5>
                    </div> 
                    <div class="card-body text-center">
                      <span class="text-danger">
                        <?php // seat
                          $linker = mysqli_query($conn, "SELECT * FROM tblticket;");
                          while($ticket_record = mysqli_fetch_assoc($linker)) {
                            if($ticket_record['ticket_schedule_id'] == $expedition_record['schedule_id'])
                            {
                              echo " ".$ticket_record['ticket_couch_id'].""; 
                              $purchase_seat[''.$ticket_record['ticket_couch_id'].''] = $ticket_record['ticket_schedule_id'];
                            }
                          }
                          if(count($purchase_seat) == 0)
                            echo "Empty";
                        ?>
                      </span>
                  </div>   
                  </div>     
                  <!-- Purchased Seat -->
     
                  <!-- Buy -->
                  <div class="card-body mt-2">
                    <div class="card-header"> 
                      <h5 class="text-center">Buy</h5>
                    </div> 
                    <div class="card-body border-dark">
                    <div class="mt-3 ml-5 mb-5 mr-5">
                      <form action="satinkontrol.php" method="POST" autocomplete="off">
                        <!-- Identity number -->
                        <div class="form-group mt-3 mb-3">
                          <label for="identity-number-label">ID Number</label>
                          <input class="form-control" type="text" maxlength="11" name="id_number" required/>
                        <div>
                        <!-- Identity number -->
                        <!-- Schedule number -->
                        <div class="form-group mt-3 mb-3">
                          <label for="schedule-number-label">Schedule Id</label>
                          <input class="form-control" type="text" maxlength="10" name="schedule_id" value="<?php echo $expedition_record['schedule_id']; ?>"required readonly/>
                        <div>
                        <!-- Schedule number -->
                        <!-- Seat number -->
                        <div class="form-group mt-3 mb-3">
                          <label for="couch-number-label">Seat Number</label>
                          <input class="form-control" type="text" maxlength="10" name="couch_id" oninput="this.value = this.value.toUpperCase()" required/>
                        <div>
                        <!-- Seat number -->   

                          <hr/> 
                          <button type="submit" class="btn btn-block btn-warning mt-3 mb-3" style="border-radius:5px;" value="Submit" name="submit" id="submit">BUY</button>
                          <button type="reset" class="btn btn-block btn-danger mt-3 mb-3"  style="border-radius:5px;" value="Reset">Reset</button>
                      </form>
                      </div>
                    </div>
                  </div>  
                  <!-- Buy -->          

                        
                </div>

              </div>
            </div>
          </li>
          <?php $GLOBALS["total_expedition_number"] -= 1; } ?>
          <?php } ?>
        </ul>

        <!-- DB is Empty -->
        <?php
          if (!$main_linker)
          {
          ?>
            <div class="">
            </div>
            <div class="ml-5 mr-5 alert alert-danger">No Scheduled Bus Expedition</div>
        <?php
          } 
        ?>
      <div>
    </section>

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="../js/new-age.min.js"></script>
    <script lang="javascript">
      function buyticket(delid,delusid){
        if(confirm("Do you want to buy ?")){
          window.location.href='delete.php?accident_id='+delid+'&accuser_id='+delusid+'';
          return true;
        }   
      }
    </script>
  </body>
</html>
