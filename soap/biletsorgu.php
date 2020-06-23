<!DOCTYPE html>
<?php session_start(); ?>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ticket Control</title>

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
    <!-- Section -->
    <section class="signup_background">
      <div class="container">
        <!-- Grid row -->
        <div class="row d-flex justify-content-center">
          <!-- Grid column-->
          <div class="col-md-10">
            <div class="border border-dark bg-white">
              <!-- Header -->
              <div class="section-heading text-center mt-5">
                <img alt="avatar" src="../img/ticketcontrol.png" width="100" height="100" />
                <h2>Ticket Control</h2>
                <span class="text-muted">Online Bus Dispatch</span>
                <hr/>
                <br/>
                <!-- Log In-->
                <a class="d-inline p-2 text-primary " href="../index.php"><i class="fas fa-user-edit"></i> Log In</a>  
                <!-- Create an Account-->
                <a class="text-success" href="../php/giriskayit.php"><i class="fas fa-user-edit"></i> Create an account</a>  

                <hr/>
              </div>
              <!-- Header -->
              <div class="mt-3 ml-5 mb-5 mr-5">
                <!-- Error message -->
                <?php if(isset($_SESSION['ticket_control_alert_message'])) {?>
                  <div class="alert alert-<?php echo $_SESSION['ticket_control_alert_message']['type']?>"> 
                    <?php echo $_SESSION['ticket_control_alert_message']['message']?>
                  </div>
                  <?php unset($_SESSION['ticket_control_alert_message'])?>
                <?php } ?>
                <!-- Error message -->
                <form action="biletkontrol.php" method="POST" autocomplete="off">
                  <!-- Identity number -->
                  <div class="form-group mt-3 mb-3">
                    <label for="identity-number-label">ID Number</label>
                    <input class="form-control" type="text" maxlength="11" name="id_number" required/>
                  <div>
                  <!-- Identity number -->
                   <!-- Schedule Id -->
                   <div class="form-group mt-3 mb-3">
                    <label for="origin-location-label" data-toggle="tooltip" data-placement="top" title="From">Schedule Id</label>
                    <input class="form-control" type="text" maxlength="50" name="schedule_id" oninput="this.value = this.value.toUpperCase()" required/>
                  <div>
                  <!-- Schedule Id -->
                  <!-- Date -->
                  <div class="form-group mt-3 mb-3">
                    <label for="date-label">Date</label>
                    <input class="form-control" type="date" name="departure_date" required/>
                  <div>
                  <!-- Date -->
                  <!-- Date -->
                  <div class="form-group mt-3 mb-3">
                      <label for="time-label">Time</label>
                      <input class="form-control" type="time" name="departure_time" required/>
                    <div>
                  <!-- Date -->
                  <hr/> 
                  <button type="submit" class="btn btn-block btn-info mt-3 mb-3" style="border-radius:5px;" value="Submit" name="submit" id="submit">Control</button>
                  <button type="reset" class="btn btn-block btn-danger mt-3 mb-3"  style="border-radius:5px;" value="Reset">Reset</button>
                </form>
              </div>
            </div>
          </div>
          <!-- Grid column-->
        </div>
        <!-- Grid row -->
      </div>
    </section>
    <!-- Section -->
  </body>
</html>