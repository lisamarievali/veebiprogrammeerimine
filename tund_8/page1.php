<?php
  //kutsume välja funktsioonide faili
  require("functions.php");
  
  $firstName = "Kodanik";
  $lastName = "Tundmatu";
  $birthMonth = null;
  $birthDay = null;
  $birthYear = null;
  $fullName = "";
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  
  //kontrollime, kas kasutaja on midagi kirjutanud
  //var_dump($_POST);
  if (isset($_POST["firstName"])){
	  //$firstName = $_POST["firstName"];
	  $firstName = test_input($_POST["firstName"]);
  }
  if (isset($_POST["lastName"])){
	  $lastName = test_input($_POST["lastName"]);
  }
  
  //täiesti mõttetu, harjutamiseks mõeldud funktsioon
  function fullname(){
	$GLOBALS["fullName"] = $GLOBALS["firstName"] ." " .$GLOBALS["lastName"]; 
  }
  
  fullname();
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
	  ?>, IF18</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim väljanäha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
	
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Eesnimi:</label><br>
	  <input type="text" name="firstName"><br>
	  <label>Perekonnanimi:</label><br>
	  <input type="text" name="lastName"><br>
	  <label>Sünnipäev: </label>
	  <?php
	    echo '<select name="birthDay">' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthDay){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <!--<input name="birthYear" type="number" min="1914" max="2003" value="1998">-->
	  <?php
	    echo '<select name="birthYear">' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>

	  <br>
	  <input type="submit" name="submitUserData" value="Saada andmed">
    </form>
	<hr>
	<?php
	  if (isset($_POST["firstName"])){
	    echo "<p>" .$fullName .", olete elanud järgnevatel aastatel: </p> \n";
		echo "<ol> \n";
		  for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++){
			echo "<li>" .$i ."</li> \n";  
		  }
		echo "</ol> \n";
      }
	?>
</body>
</html>