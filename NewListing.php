<?php
session_start();
require_once "configuration.php";
$seller_name = $item_name = $Bitcoin_Id = $Item_Description = "";
$seller_error = $item_error = $Bitcoin_error = $ItemDes_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $target_dir = "Uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "You can only upload JPG,PNG,and JPEG files for your image.";
  $uploadOk = 0;
  }


  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The image". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
  }
  else {
      echo "Sorry, there was an error uploading your file.";
  }

  $image=basename( $_FILES["fileToUpload"]["name"]); // used to store the filename in a variable

  //Check to ensure that all of the required entries have been filled out.
  if(empty(trim($_POST['seller-name']))){
    $seller_error = "You did not specify the seller name.";
  }
  else{
    $seller_name = trim($_POST['seller-name']);
  }
  if(empty(trim($_POST['Item-Name']))){
    $item_error = "You did not specify the name of the item you are planning on selling.";
  }
  else{
    $item_name = trim($_POST['Item-Name']);
  }
  if(empty(trim($_POST['Item-Description']))){
    $ItemDes_error = "You did not give a description of the item";
  }
  else{
    $Item_Description = trim($_POST['Item-Description']);
  }
  if(empty(trim($_POST['Bitcoin-Id']))){
    $Bitcoin_error = "You did not give a Venmo name or public wallet key for the customer to send the payments to.";
  }
  else{
    $Bitcoin_Id = trim($_POST['Bitcoin-Id']);
  }
  //Check input errors before preparing insert statement.
  if(empty($seller_error)&&empty($item_error)&&empty($ItemDes_error)&&empty($Bitcoin_error)){
    $sql = "INSERT INTO listings(Seller,Item,Description,Bitcoinwalletid,image) VALUES (?,?,?,?,?)";
    if($stmt = mysqli_prepare($link, $sql)){
      mysqli_stmt_bind_param($stmt,"sssss", $parameter_sellername, $parameter_itemname, $parameter_itemdescription, $parameter_bitcoinid, $parameter_image);
      $parameter_sellername = $seller_name;
      $parameter_itemname = $item_name;
      $parameter_itemdescription = $Item_Description;
      $parameter_bitcoinid = $Bitcoin_Id;
      $parameter_image = $image;
      if(mysqli_stmt_execute($stmt)){
          // Redirect to main page
          header("location: index.php");
      } else{
          echo "Something just went wrong. No particular reason. Life is pain.";
      }
    }
  }
  mysqli_close($link);

}
//Need to update HTML part of code to ensure that values and error messages are properly parsed!
?>










<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Listing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="divison">
        <h2>Decentra Commerce:New Listing sale</h2>
        <h5>Make a new listing to be put on Decentra Commerce!</h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
          <?php echo (!empty($seller_error)) ? 'This has error' : ''; ?>
          <label>Name of Seller:</label>
          <input type = 'text' name = 'seller-name' value="<?php echo $seller_name; ?>">
          <?php echo (!empty($item_error)) ? 'This has error' : ''; ?>
          <label>Name of Item:</label>
          <input type = 'text' name = 'Item-Name' value="<?php echo $item_name; ?>">
          <?php echo (!empty($Bitcoin_error)) ? 'has-error' : ''; ?>
          <label>VenmoId or Bitcoin-Wallet-Id:</label>
          <input type = 'text' name = 'Bitcoin-Id' value="<?php echo $Bitcoin_Id; ?>">
          <?php echo (!empty($Item_Description)) ? 'has-error' : ''; ?>
          <label>Short Item Description and seller information(Please include phone-number or other social media for contact purposes):</label>
          <input type = 'text' name = 'Item-Description' value="<?php echo $Item_Description; ?>">
          <label>Image of item</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="7000000000"/>
          <input type = 'file' name = 'fileToUpload' id = 'fileToUpload'/>
          <input type="submit" value="Submit">
        </form>
    </div>
    <div class = "about">
      <h2>About</h2>
      <p>Decentra Commerce is built on the principle that cryptocurrencies can eventually replace fiat currencies.
        It is open for use by members of the Georgia Tech community.</p>
    </div>
    <h6 class = 'ending'>An Archie Chaudhury Creation</h6>
</body>
</html>
