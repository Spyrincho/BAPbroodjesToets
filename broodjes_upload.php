<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <title>Afbeeldingen uploaden!!</title>
</head>
<body>
<h1>Broodjes afbeeldingen uploaden</h1>
  <a href="broodjes.php">klik hier om terug te gaan</a>
  <br>
<form action="" method="post" enctype="multipart/form-data">
  <input type="file" name="image">
  <br>
  <br>
  <label for="title">Title</label><br>
  <input type="text" name="title" id="title"> <br>
  <label for="description">Description</label> <br>
  <input type="text" name="description" id="description"> <br>
  <input type="submit" value="Upload Bestand!">
</form>

</body>
</html>

<?php
$dbhost = "localhost";
$dbname = "baptoets2";
$user = "root";
$pass = "";

try {
    $connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Fout bij verbinding maken: " . $e->getMessage();
    exit;
}

if (isset($_FILES['image'])) {
  $errors = array();

  $file_name = time().$_FILES['image']['name'];
  $file_size = $_FILES['image']['size'];
  $file_tmp = $_FILES['image']['tmp_name'];
  $file_type = $_FILES['image']['type'];

  // de explode string-functie breekt een string in een array
  // hierbij breek je de string na de . (punt) waardoor je de bestands type hebt
  $filename_deel = explode('.',$_FILES['image']['name']);
  // end laat de laatste waarde van de array zoen
  $bestandstype = end($filename_deel);
  // voor het geval er JPG ipv jpg is geschreven
  $file_ext = strtolower($bestandstype);

  $bestandstypen = array("jpeg","jpg","png");

  if (in_array($file_ext,$bestandstypen) === false){
    $errors[] = "Dit bestandstype kan niet, kies een JPEG of een PNG bestand.";
  }

  if ($file_size > 10485761) {
    $errors[] = "Het bestand moet kleiner zijn dan 10 MB";
  }

  $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
  $detectedType = exif_imagetype($_FILES['image']['tmp_name']);
  $error = !in_array($detectedType, $allowedTypes);

  if ($file_type === $error) {
    $errors[] = "Dit mag niet!";
  }

  if (empty($errors) == true) {
    // move_uploaded_file stuurt je bestand naar een andere lokatie
    move_uploaded_file($file_tmp, "uploads/".$file_name);
    echo "Je afbeelding is geupload’";
  } else {
    print_r($errors);
}

  try {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $linkToImage = "uploads/" . $file_name;
    $sql = "INSERT INTO posts (title, description, imagelink) VALUES ('$title', '$desc', '$linkToImage')";

    $statement = $connection->query($sql);
  } catch (PDOException $err) {
    echo $err->getMessage();
  }
}

try {
  $connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $user, $pass);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Fout bij verbinding maken: " . $e->getMessage();
  exit;
}
?>
