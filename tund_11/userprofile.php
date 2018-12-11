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
  
  
	//profiilipildi laadimine
	if(!empty($_FILES["fileToUpload"]["name"])){
			$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file_name = "vpuser_" .$timeStamp ."." .$imageFileType;
			$target_file = $profilePicDirectory .$target_file_name;
						
			// kas on pilt, kontrollin pildi suuruse küsimise kaudu
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				//echo "Fail on pilt - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "Fail ei ole pilt.";
				$uploadOk = 0;
			}
			
			// faili suurus
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Kahjuks on fail liiga suur!";
				$uploadOk = 0;
			}
			
			// kindlad failitüübid
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
				$uploadOk = 0;
			}
			
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				echo "Vabandame, faili ei laetud üles!";
			// kui kõik korras, laeme üles
			} else {
				//sõltuvalt failitüübist, loome pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//vaatame pildi originaalsuuruse
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian vajaliku suurendusfaktori, siin arvestan, et lõikan ruuduks!!!
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageHeight / 300;//ruuduks lõikamisel jagan vastupidi
				} else {
					$sizeRatio = $imageWidth / 300;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = $newWidth;
				$myImage = resizeImagetoSquare($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//lisame vesimärgi
				$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$waterMarkWidth = imagesx($waterMark);
				$waterMarkHeight = imagesy($waterMark);
				$waterMarkPosX = $newWidth - $waterMarkWidth - 10;
				$waterMarkPosY = $newHeight - $waterMarkHeight - 10;
				//kopeerin vesimärgi pikslid pildile
				imagecopy($myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
				
				//muudetud suurusega pilt kirjutatakse pildifailiks
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				  if(imagejpeg($myImage, $target_file, 90)){
                    //echo "Korras!";
					//ja kohe see uus profiilipilt
		            $profilePic = $target_file;
					//kui pilt salvestati, siis lisame andmebaasi
					$addedPhotoId = addUserPhotoData($target_file_name);
					//echo "Lisatud pildi ID: " .$addedPhotoId;
				  } else {
					//echo "Pahasti!";
				  }
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($waterMark);
				
			}
		}//pildi laadimine lõppes
		//profiili salvestamine koos pildiga

  
  function resizeImageToSquare($image, $ow, $oh, $w, $h){
	$newImage = imagecreatetruecolor($w, $h);
	if($ow > $oh){
		$cropX = round(($ow - $oh) / 2);
		$cropY = 0;
		$cropSize = $oh;
	} else {
		$cropX = 0;
		$cropY = round(($oh - $ow) / 2);
		$cropSize = $ow;
	}
    //imagecopyresampled($newImage, $image, 0, 0 , 0, 0, $w, $h, $ow, $oh);
	imagecopyresampled($newImage, $image, 0, 0, $cropX, $cropY, $w, $h, $cropSize, $cropSize); 
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