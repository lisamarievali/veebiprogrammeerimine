<?php
  require("functions.php");
  
  $pageTitle = "Fotode üleslaadimine";
  require("header.php");	
  
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
  
  require("classes/Photoupload.class.php");
  
  
  
  //piltide laadimise osa
	$target_dir = "../vp_pic_uploads/";
	$uploadOk = 1;
	$notice1 = "";
	$uploadNotice = "";
	
	// Kontrollime, kas tegemist on pildiga või mitte
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			//var_dump($_FILES["fileToUpload"]["name"]);
		    //echo $_FILES["fileToUpload"]["fileName"];
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on " . $check["mime"] . " pilt.";
				//$uploadOk = 1;
			} else {
				echo "Fail pole pilt!";
				$uploadOk = 0;
			}
			
			// Kontrollime, kas fail juba eksisteerib
			if (file_exists($target_file)) {
				echo "Selle nimega fail on juba olemas!";
				$uploadOk = 0;
			}
			// Kontrollime faili suurust
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Pilt on liiga suur!";
				$uploadOk = 0;
			}
			
			// Ainult teatud vormid lubatud
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud!";
				$uploadOk = 0;
			}
			
			// Kui tuleb error
				
			if ($uploadOk == 0) {
				$notice .= " Valitud faili ei saa üles laadida.";
			// if everything is ok, try to upload file
			} else {
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->changePhotoSize(600, 400);
				$myPhoto->addWatermark();
				$myPhoto->addTextToImage();
				$notice = $myPhoto->savePhoto($target_file);
				unset($myPhoto);
				
				if($notice == 1){
					addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					$uploadNotice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
				} else {
					$uploadNotice = "Faili üleslaadimisel tekkis tehniline viga.";
				}
			}
		}//if !empty lõppeb
	}//siin lõppeb nupuvajutuse kontroll
	
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
  

  
	}
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
    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<label>Alt tekst: </label>
	<input type= "text" name="altText">
	<br><br>
	<label>Määra pldi kasutusõigused</label>
	<br>
	<input type="radio" name="privacy" value="1"><label> Avalik pilt</label>
	<input type="radio" name="privacy" value="2"><label> Ainult sisseloginud kasutajatele</label>
	<input type="radio" name="privacy" value="3"><label> Privaatne profiil</label>
	<br>
    <input type="submit" value="Lae pilt üles" name="submitImage">
</form>
	
  </body>
</html>