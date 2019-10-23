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
        <li class="active"><a href="products.php">Produkty</a></li>
        <li><a href="clients.php">Klienci</a></li>
        <li><a href="orders.php">Zamówienia</a></li>
      </ul>
    </div>
  </nav>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Identyfikator</th>
        <th>Nazwa produktu</th>
        <th>Cena netto</th>
        <th>Stawka VAT</th>
      </tr>
    </thead>
    <tbody>
<?php
  $sql = "SELECT id_produktu, produkty.nazwa as nazwa_produktu, cena_netto, stawka_VAT.nazwa as nazwa_stawki ".
         "FROM produkty, stawka_VAT where produkty.id_stawka_VAT = stawka_VAT.id_stawka_VAT order by id_produktu";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["id_produktu"]."</td>".
            "<td>".$row["nazwa_produktu"]."</td>".
            "<td>".$row["cena_netto"]."</td>".
            "<td>".$row["nazwa_stawki"]."</td>".
          "</tr>";
      }
  } 
?>
    </tbody>
  </table>
</div>
</body>
<?php  
  mysqli_close($conn);
?>
</html>