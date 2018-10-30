<?php
  require("functions.php");
  
  //kui pole sisse loginud, siis logimise lehele
  if(!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  }
  
  //ul'i ja li vahel on välja logimine
  //logime välja
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index_1.php");
	  exit();
  }
  //piltide laadimise osa
	$target_dir = "../vp_pic_uploads/";
	
	$uploadOk = 1;
	
	// Kontrollitakse, kas on pildifail või mitte
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			//var_dump($_FILES["fileToUpload"]["name"]);
			$timeStamp = microtime(1) * 10000;
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$target_file = $target_dir . "vp_" .$timeStamp ."." .$imageFileType;
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			$timeStamp = microtime(1) * 10000;
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail " . $check["mime"] . "pilt.";
				//$uploadOk = 1;
			} else {
				echo "Fail ei ole pilt.";
				$uploadOk = 0;
			}
				// Kontrollime kas fail on juba olemas
			if (file_exists($target_file)) {
				echo "Selle nimega fail on juba olemas.";
				$uploadOk = 0;
			}
			// Kontrollime faili suurust
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Pildi suurus ületab limiidi.";
				$uploadOk = 0;
			}
			// Lubatakse ainult teatud faile
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Lubatud on ainult JPG, JPEG, PNG ja GIF failid.";
				$uploadOk = 0;
			}
			// Error, kui pilti ei saa laadida üles
			if ($uploadOk == 0) {
				echo "Vabandage, valitud faili ei saa üles laadida.";
			// Kui kõik korras, laadida pilt üles
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo " Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
				} else {
					echo "Vabandage, faili üleslaadimisel tekkis viga.";
				}
			}//if empty
	}
	}//siin lõppeb nupuvajutuse kontroll
	

  //lehe päise laadimise osa
  
  $pageTitle = "Fotode üleslaadimine";
  require("header.php");
  
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] .".";?></p>
	<ul>
      <li><a href="?logout=1">Logi välja.</a></li> 
	  <li>Tagasi <a href="main.php">pealehele</a>.</li>
	</ul>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pildifail (soovitavalt mahuga kuni 2,5MB):</label><br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Lae pilt üles" name="submitImage">
</form>
	
  </body>
</html>