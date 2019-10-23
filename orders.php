<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sklep SQL/PHP</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<?php
  $config = parse_ini_file('db.ini');
  // Create connection
  $conn = mysqli_connect($config["servername"], $config["username"], $config["password"], $config["dbname"]);
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }
?>

<div class="container-fluid">
  <div class="page-header">
    <h1>Sklep Sportex <small>Import/eksport produktów czekoladopodobnych</small></h1>
  </div>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Strona główna</a></li>
        <li><a href="rates.php">Stawki VAT</a></li>
        <li><a href="products.php">Produkty</a></li>
        <li><a href="clients.php">Klienci</a></li>
<?php
  if ($_GET["client_id"] != "") {
    echo "<li><a href=\"orders.php\">Zamówienia</a></li>";
  } else {
    echo "<li class=\"active\"><a href=\"orders.php\">Zamówienia</a></li>";
  }  
?>
        
      </ul>
    </div>
  </nav>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Identyfikator</th>
        <th>Nazwa klienta</th>
        <th>Szczegóły</th>
      </tr>
    </thead>
    <tbody>
<?php
  if ($_GET["client_id"] != "") {
    $sql = "SELECT id_zamowienia, nazwa as nazwa_klienta FROM zamowienia, klienci where zamowienia.id_klienta = klienci.id_klienta and klienci.id_klienta=" . $_GET["client_id"] . " order by id_zamowienia";
  } else {
    $sql = "SELECT id_zamowienia, nazwa as nazwa_klienta FROM zamowienia, klienci where zamowienia.id_klienta = klienci.id_klienta order by id_zamowienia";
  }  
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["id_zamowienia"]."</td>".
            "<td>".$row["nazwa_klienta"]."</td>".
            "<td><a href=\"order_details.php?order_id=".$row["id_zamowienia"]."\">Szczegóły</a></td>".
          "</tr>";
      }
  } 
?>
    </tbody>
  </table>
  </div>
</div>
</body>
<?php  
  mysqli_close($conn);
?>
</html>