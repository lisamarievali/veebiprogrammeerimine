<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Lisa-Marie";
  $lastName = "Väli";
  //loeme piltide kataloogi sisu
  $dirToRead = "../../pics/";
  $allFiles = scandir($dirToRead);
  $picFiles = array_slice($allFiles, 2);
  var_dump($allFiles);
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
  
  - õppetöö</title>
</head>
<body>
<h1>
  <?php
  echo $firstName ." " .$lastName;
 
  ?>
, IF18</h1>
<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ<a> õppetöö raames, ärge pange pahaks, kui tundub algeline, siin pole tõsiseltvõetavat sisu!</p>

<?php
//<img src="" alt="Tallinn">
for ($i = 0; $i < count($picFiles); $i ++) {
  echo '<img src="' .$dirToRead .$picFiles[$i] .'" alt="pilt"><br>' ."\n";
}

?>

</body>
</html>
