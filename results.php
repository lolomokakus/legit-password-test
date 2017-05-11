<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Resultat – Testa ditt lösenord!</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
  <h1>Resultat</h1>
  <table>
    <tr>
      <td>Innehåller små bokstäver:</td>
      <td>
        <?php
          $password = $_POST["password"];
          $lower = "abcdefghijklmnopqrstuvwxyz";
          $contains = 0;
          for($x = 0; $x < strlen($password); $x++) {
            for($y = 0; $y < strlen($lower); $y++) {
              if($password[$x] == $lower[$y]) {
                echo ✓;
                $contains = 1;
                break 2;
              };
            };
          };
          if($contains == 0) {
            echo ✗;
          };
        ?>
      </td>
    </tr>
    <tr>
      <td>Innehåller stora bokstäver:</td>
      <td>
        <?php
          $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          $contains = 0;
          for($x = 0; $x < strlen($password); $x++) {
            for($y = 0; $y < strlen($upper); $y++) {
              if($password[$x] == $upper[$y]) {
                echo ✓;
                $contains = 1;
                break 2;
              };
            };
          };
          if($contains == 0) {
            echo ✗;
          };
        ?>
      </td>
    </tr>
    <tr>
      <td>Innehåller siffror:</td>
      <td>
        <?php
          $numbers = "0123456789";
          $contains = 0;
          for($x = 0; $x < strlen($password); $x++) {
            for($y = 0; $y < strlen($numbers); $y++) {
              if($password[$x] == $numbers[$y]) {
                echo ✓;
                $contains = 1;
                break 2;
              };
            };
          };
          if($contains == 0) {
            echo ✗;
          };
        ?>
      </td>
    </tr>
    <tr>
      <td>Innehåller symboler:</td>
      <td>
        <?php
          $contains = 0;
          for($x = 0; $x < strlen($password); $x++) {
            for($y = 0; $y <= 255; $y++) {
              if((65 <= $y and $y <= 90) or (97 <= $y and $y <= 122)) {
                break 1;
              };
              if(ord($password[$x]) == $y) {
                echo ✓;
                $contains = 1;
                break 2;
              };
            };
          };
          if($contains == 0) {
            echo ✗;
          };
        ?>
      </td>
    </tr>
  </table>
</body>
