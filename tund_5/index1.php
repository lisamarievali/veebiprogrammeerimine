<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Lisa-Marie";
  $lastName = "Väli";
  $dateToday = date("d.m.Y");
  $hourNow = date("G");
  $weekdayNow = date("N");
  $weekdayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  echo $weekdayNamesET[1];
  //var_dump($weekdayNamesET)[1];
  //$weekdayNow = date("N"):
  //echo $weekdayNow;
  $partOfDay = "";
  if ($hourNow < 8) {
	 $partOfDay = "varajane hommik";
  }
 if ($hourNow >= 8 and $hourNow < 16){
	 $partOfDay = "koolipäev";
  }
 if ($hourNow >= 16){
	 $partOfDay = "vaba aeg"; 
 }
	 $picNum = mt_rand(2, 43);
	 //echo $picNum;
	 $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
	 $picEXT = ".jpg";
	 $picFile = $picURL .$picNum .$picEXT;
	 
	 ?>
	 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>
    <?php
	echo $firstName;
	echo " ";
	echo $lastName;
	?>
  
  , õppetöö</title>
</head>
<body>
<h1>
  <?php
  echo $firstName ." " .$lastName;
  ?>
, IF18</h1>
<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ<a> õppetöö raames, ärge pange pahaks, kui tundub algeline, siin pole tõsiseltvõetavat sisu!</p>

<p>Tundides tehtu: <a href="photo_php.php"> photo.php </a></p>

<?php
  //echo "<p>Tänane kuupäev on: " .$dateToday .".</p>\n";
  echo "<p>Täna on " .$weekdayNamesET[$weekdayNow -1] .", " .$dateToday . ".</p>\n";
  echo "<p>Lehe avamise hetkel oli kell " .date("H:i:s") .". Käes oli " .$partOfDay .".</p> \n";
  ?>
<p>Seoses kodutööga lisan siia veel ühe lause. <p>

<!--<img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
   <!--<img src="../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
   
  <img src="<?php echo $picFile; ?>" alt="TLÜ hoone">

<p> Mul on ka sõber, kes teeb oma <a href="../../../~emmatae" target="_blank">veebi<a> </p>

 
</body>
</html>
