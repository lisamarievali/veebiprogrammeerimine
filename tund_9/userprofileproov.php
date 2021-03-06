<?php
  require("functions.php");
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_1.php");
	exit();
  }
  
  $mydescription = "Pole tutvustust lisanud!";
  $mybgcolor = "#FFFFFF";
  $mytxtcolor = "#000000";
  
  if(isset($_POST["submitProfile"])){
	$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	if(!empty($_POST["description"])){
	  $mydescription = $_POST["description"];
	}
	$mybgcolor = $_POST["bgcolor"];
	$mytxtcolor = $_POST["txtcolor"];
  } else {
	$myprofile = showmyprofile();
	if($myprofile->description != ""){
	  $mydescription = $myprofile->description;
    }
    if($myprofile->bgcolor != ""){
	  $mybgcolor = $myprofile->bgcolor;
    }
    if($myprofile->txtcolor != ""){
	  $mytxtcolor = $myprofile->txtcolor;
    }
  }
    $pageTitle="";
  require("header.php");
  
  
  //piltide laadimise osa
	$target_dir = "../vp_pic_uploads/";
	
	$uploadOk = 1;
	
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
				echo "Valitud faili ei saa üles laadida!";
			// Kui kõik on korras
			} else {
				//sõltuvalt failitüübist loon sobiva pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//pildi originaalsuurus
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian suuruse muutmise suhtarvu
				/* if ($imageWidth < 600 and $imageHeight < 400){
					$myImage = $image;
				} else { */
				
				
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 600;
				} else {
					$sizeRatio = $imageHeight / 400;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//vesimärk
				$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$waterMarkWidth = imagesx($waterMark);
				$waterMarkHeight = imagesy($waterMark);
				$waterMarkPosX = $newWidth - $waterMarkWidth - 10;
				$waterMarkPosY = $newHeight - $waterMarkHeight - 10;
				imagecopy($myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
				
				//tekst vesimärgina
				$textToImage = "Veebiprogrammeerimine";
				//mis pilt, r, g, b, alpha 0...127
				$textColor = imagecolorallocatealpha($myImage, 255, 255, 255, 70);
				imagettftext($myImage, 20, 0, 10, 30, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
				
				
				
				//faili salvestamine,jälle sõltuvalt failitüübist
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($waterMark);
				
				/* if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
				} else {
					echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
				} */
				//}
			}
		}//if !empty lõppeb
	}//siin lõppeb nupuvajutuse kontroll
	
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	}
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<style>
	  <?php
        echo "body{background-color: " .$mybgcolor ."; \n";
		echo "color: " .$mytxtcolor ."} \n";
	  ?>
	</style>
	<title>
	  <?php
	    echo $_SESSION["userFirstName"];
		echo " ";
		echo $_SESSION["userLastName"];
	  ?>
	, õppetöö</title>
  </head>
  <body>
    <h1>
	  <?php
	    echo $_SESSION["firstName"] ." " .$_SESSION["lastName"];
	  ?>
	profiil</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br><br>
	  <label>Vali profiilipilt (soovitavalt mahuga kuni 2,5MB):</label><br>
      <input type="file" name="fileToUpload" id="fileToUpload"><br>
	  <label>Alt tekst: </label>
	  <input type= "text" name="altText">
	  <input type="submit" value="Lae pilt üles" name="submitImage"><br><br>
	  
	<input name="submitProfile" type="submit" value="Salvesta profiil">
	</form>
	
	
  </body>
</html>