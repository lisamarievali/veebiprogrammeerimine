<?php
  //laen andmebaasi info
  require("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_lisam_va_1";
  
  //võtan kasutusele sessiooni
  session_start();
  $mydescription="";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kasutaja profiil</title>
</head>

<body>
 <h1>Profiil</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p><hr>
    <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <form>
  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
  <input name="submitUserData" type="submit" value="Salvesta profiil">
  </form>
  
</body>

</html>