<?php
  //laen andmebaasi info
  require("../../../config.php");
  
  require("functions.php");
  
  //echo $GLOBALS["serverUsername"];
  $database = "if18_lisam_va_1";
  
  //võtan kasutusele sessiooni

  $mydescription="Sisu ";
  
  $description= "";
  $bgcolor= "";
  $txtcolor= "";
  $notice= "";

	if(isset($_POST["submitProfile"])){
		if(isset($_POST["description"]) and isset($_POST["bgcolor"]) and isset($_POST["txtcolor"])){
			if(empty($_POST["description"])){
			 	$description = "Pole iseloomustust lisanud.";
			} else {
				$description = test_input($_POST["description"]);
			}
			$bgcolor = $_POST["bgcolor"];
			$txtcolor = $_POST["txtcolor"];
			$notice = saveprofile($description, $bgcolor, $txtcolor);
		}
	}
	
	$data= userprofileload();
 
 
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  
  <title>Kasutaja profiil</title>
  
  <?php
  echo "<style>
    body{
      background-color: " .$data[1] .";
      color: " .$data[2] ."
      }
  </style>";
  ?>
  
</head>

<body>
 <h1>Profiil</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p><hr>
    <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <form>
  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $data[1]; ?>"><br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $data[2]; ?>"><br>
  <input name="submitUserData" type="submit" value="Salvesta profiil">
  </form>
<p><?php echo $notice; ?> </p>
  
</body>

</html>