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
    <h1>Sklep Sportex <small>Zamówienie numer <?php echo $_GET["order_id"]?></small></h1>
  </div>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Strona główna</a></li>
        <li><a href="rates.php">Stawki VAT</a></li>
        <li><a href="products.php">Produkty</a></li>
        <li><a href="clients.php">Klienci</a></li>
        <li><a href="orders.php">Zamówienia</a></li>
      </ul>
    </div>
  </nav>
  <h2>Dane klienta</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nazwa klienta</th>
        <th>Kraj</th>
        <th>Kod pocztowy</th>
        <th>Miasto</th>
        <th>Ulica</th>
        <th>Numer</th>
      </tr>
    </thead>
    <tbody>
<?php
  $sql = "SELECT nazwa, kraj, kod_pocztowy, miasto, ulica, numer FROM klienci, zamowienia " .
         "WHERE klienci.id_klienta = zamowienia.id_klienta and id_zamowienia = " . $_GET["order_id"];
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["nazwa"]."</td>".
            "<td>".$row["kraj"]."</td>".
            "<td>".$row["kod_pocztowy"]."</td>".
            "<td>".$row["miasto"]."</td>".
            "<td>".$row["ulica"]."</td>".
            "<td>".$row["numer"]."</td>".
          "</tr>";
      }
  } 
?>
    </tbody>
  </table>  
  <h2>Status zamówienia</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Data</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
<?php
  
  $sql = "SELECT data, status FROM statusy_zamowien WHERE data = ".
         " (SELECT MAX(data) FROM statusy_zamowien WHERE data<=NOW() AND id_zamowienia = " . $_GET["order_id"] . ") ".
         " AND id_zamowienia = " . $_GET["order_id"];
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["data"]."</td>".
            "<td>".$row["status"]."</td>".
          "</tr>";
      }
  } 
?>
    </tbody>
  </table>

  <h2>Szczegóły zamówienia</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>LP</th>
        <th>Nazwa produktu</th>
        <th>Ilość</th>
        <th>Cena netto</th>
        <th>Rabat</th>
        <th>Wartość netto</th>
        <th>Stawka VAT</th>
        <th>VAT</th>
        <th>Wartość brutto</th>
      </tr>
    </thead>
    <tbody>
<?php
  $sql = "SELECT LP, nazwa_produktu, ilosc, cena_netto, rabat, wartosc_netto, stawka_vat, VAT, wartosc_brutto ".
         "FROM szczegoly_zamowien where id_zamowienia = " . $_GET["order_id"];
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["LP"]."</td>".
            "<td>".$row["nazwa_produktu"]."</td>".
            "<td>".$row["ilosc"]."</td>".
            "<td>".$row["cena_netto"]."</td>".
            "<td>".$row["rabat"]."</td>".
            "<td>".$row["wartosc_netto"]."</td>".
            "<td>".$row["stawka_vat"]."</td>".
            "<td>".$row["VAT"]."</td>".
            "<td>".$row["wartosc_brutto"]."</td>".
          "</tr>";
      }
  } 
?>
    </tbody>
  </table>
  <h2>Historia zamówienia</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Data</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
<?php
  
  $sql = "SELECT data, status FROM statusy_zamowien where data <= NOW() and id_zamowienia = " . $_GET["order_id"] . " ORDER BY data DESC";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>".
            "<td>".$row["data"]."</td>".
            "<td>".$row["status"]."</td>".
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