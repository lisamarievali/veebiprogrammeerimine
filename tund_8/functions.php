<?php
  //laen andmebaasi info
  require("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_lisam_va_1";
  
  //võtan kasutusele sessiooni
  session_start();
  
  //function $mydescription(){
	  
  //}
  //userprofile seadistamine
  function saveprofile($description, $bgcolor, $txtcolor){
    $notice= "";
    $mysqli= new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    
    $stmt= $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE user_id=?");
    echo $mysqli->error;
    
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($description, $bgcolor, $txtcolor);
    $stmt->execute();
  
  

  if($stmt->fetch()){
  
    $stmt->close();
    $stmt= $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE user_id=?");
    echo $mysqli->error;
    $stmt->bind_param("sssi", $description, $bgcolor, $txtcolor, $_SESSION["userId"]);
    $stmt->execute();
    
    if($stmt->execute()){
      $notice= "Profiil salvestatud";
    } else {
      $notice= "Tekkis viga: " .$stmt->error;
      }
      
  } else {
  
    $stmt->close();
    $stmt= $mysqli->prepare("INSERT INTO vpuserprofiles (user_id, description, bgcolor, txtcolor) VALUES (?, ?, ?, ?)");
    echo $mysqli->error;
    $stmt->bind_param("isss", $_SESSION["userId"], $description, $bgcolor, $txtcolor);
    $stmt->execute();
  
    if($stmt->execute()){
      $notice= "Profiil salvestatud";
    } else {
      $notice= "Tekkis viga: " .$stmt->error;
    }
  }
  
  $stmt->close();
  $mysqli->close();
  return $notice;
}
  function userprofileload(){
    $data=[];
    $mysqli= new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    
    $stmt= $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE user_id=?");
    echo $mysqli->error;
     
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($description, $bgcolor, $txtcolor);
    $stmt->execute();
  
    if($stmt->fetch()){
      $data= [$description, $bgcolor, $txtcolor];
    } else {
      $description= "Pole iseloomustust lisatud.";
      $bgcolor= "#FFFFFF";
      $txtcolor= "#000000";
      $data= [$description, $bgcolor, $txtcolor];
    }
  
  $stmt->close();
  $mysqli->close();
  return $data;
  }
  
  //kõigi valideeritud sõnumite lugemine kasutajate kaupa
  function readallvalidatedmessagesbyuser(){
	  $msghtml = "";
	  $temphtml = "";
	  $hasvalidated = 0;
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	  echo $mysqli->error;
	  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	  
	  $stmt2=$mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
	  echo $mysqli->error;
	  $stmt2->bind_param("i", $idFromDb);
	  $stmt2->bind_result($msgFromDb, $acceptedFromDb);
	  

	  $stmt->execute();
	  //et hoida ab loetud andmeid kauem mälus,( et saaks edasi kasutada)
	  $stmt->store_result();
	  while ($stmt->fetch()){
		 $temphtml .= "<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
		 $stmt2->execute();
		
		 while($stmt2->fetch()){
			 $hasvalidated .= 1;
			 
		 
			 $temphtml .= "<p><b>";
			 if($acceptedFromDb == 1){
				 $temphtml .= "Lubatud: ";
			 } else {
				 $temphtml .= "Keelatud: ";
			 }
			 $temphtml .= "</b>" .$msgFromDb ."</p> \n";
		 }
		 if($hasvalidated > 0){
			 $msghtml .= $temphtml;
			 $hasvalidated = 0;
		 }
		 $temphtml = "";
		 
	  }
	  $stmt2->close();
	  $stmt->close();
	  $mysqli->close();
	  return $msghtml;
  }
    
  //kasutajate nimekiri
  function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id !=?");
	
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function allvalidmessages(){
	$html = "";
	$valid = 1;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
	echo $mysqli->error;
	$stmt->bind_param("i", $valid);
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	if(empty($html)){
		$html = "<p>Kontrollitud sõnumeid pole.</p>";
	}
	return $html;
  }
  
  function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }
  
  //loen sõnumi valideerimiseks
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //valideerimata sõnumite lugemine
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$notice .= "</ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //sisselogimine
  function signin($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
	$mysqli->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()){
	  //kui õnnestus andmebaasist lugenine
	  if($stmt->fetch()){
		//leiti selline kasutaja
	    if(password_verify($password, $passwordFromDb)){
		  //parool õige
		  $notice = "Logisite õnnelikult sisse!";
		  $_SESSION["userId"] = $idFromDb;
		  $_SESSION["firstName"] = $firstnameFromDb;
		  $_SESSION["lastName"] = $lastnameFromDb;
		  $stmt->close();
	      $mysqli->close();
		  header("Location: main.php");
		  exit();
		  
		} else {
		  $notice = "Sisestasite vale salasõna!";
        }
	  } else {
		$notice = "Sellist kasutajat (" .$email .") ei leitud!";  
	  }		  
	} else {
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //kasutaja salvestamine
  function signup($name, $surname, $email, $gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//kontrollime, ega kasutajat juba olemas pole
	$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->execute();
	if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
		$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
    	echo $mysqli->error;
	    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	    $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	    if($stmt->execute()){
		  $notice = "ok";
	    } else {
	      $notice = "error" .$stmt->error;	
	    }
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  //anonüümse sõnumi salvestamine
  function saveamsg($msg){
	$notice = "";
	//serveri ühendus (server, kasutaja, parool, andmebaas
	$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistan ette SQL käsu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
	echo $mysqli->error;
	//asendame SQL käsus küsimargi päris infoga (andmetüüp, andmed ise)
	//s - string; i - integer; d - decimal
	$stmt->bind_param("s", $msg);
	if ($stmt->execute()){
	  $notice = 'Sõnum: "' .$msg .'" on salvestatud.';
	} else {
	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function listallmessages(){
	$msgHTML = "";
    $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT message FROM vpamsg");
	echo $mysqli->error;
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$msgHTML .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $msgHTML;
  }
  
  //tekstsisestuse kontroll
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  

  
?>