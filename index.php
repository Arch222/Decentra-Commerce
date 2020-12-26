<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Decentra Commerce</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #	#228B22;
      padding: 25px;
    }


  /* Hide the carousel text when the screen is less than 600 pixels wide */
  @media (max-width: 600px) {
    .carousel-caption {
      display: none;
    }
  }
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><button style="background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 24px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
        margin: 4px 2px;
        cursor: pointer;" onclick="document.location='NewListing.php'">Sell Items</button></li>
        <li><a href="tips.html">Suggestions for potential bitcoin wallets</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>


<div class="container">
  <div class="column">
  <?php
     require_once "configuration.php";
     $result = mysqli_query($link,"SELECT * FROM listings");


     echo "<table style =  'color: #228B22;
    font-family: Helvetica, Arial, sans-serif;
    width: 640px;
    border-collapse:
    collapse; border-spacing: 0;
    width: 100%; '>
     <tr>
     <th>Seller Name</th>
     <th>Item</th>
     <th>Item Description</th>
     <th>Venmo or Bitcoinwalletid</th>
     <th>Image of Item</th>
     </tr>";

     while($row = mysqli_fetch_array($result))
     {
       echo "<tr>";
       echo "<td>" . $row['Seller'] . "</td>";
       echo "<td>" . $row['Item'] . "</td>";
       echo "<td>" . $row['Description'] . "</td>";
       echo "<td>" . $row['BitcoinwalletId'] . "</td>";
       echo "<td><img src='Uploads/$row[5]' height='150px' width='300px'></td>";
       echo "</tr>";
     }
     echo "</table>";


 ?>
  </div>
</div><br><br>



<div>
<p style = "border-top: 1px solid #fff; position: relative; top: 10px; margin-bottom: 15px;">Decentra Commerce is built on the principle that seamless and quick payments such as crytpocurrenices and mobile cash payments(Venmo) will eventually replace traditional forms of payment.
  It is a  networking website that connects sellers and buyers in the Georgia Tech community, and is open for use by members of the Georgia Tech community. Users can sell a myriad of items, including but not limited to: tickets to games, food, merchandise, used textbooks, and used electronics.</p>
</div>

<footer class="container-fluid text-center">
  <p>An Archie Chaudhury Creation</p>
</footer>

</body>
</html>
