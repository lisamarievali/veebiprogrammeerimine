
<!DOCTYPE html>
 <head>
     <title>PHP katsetused</title>
 </head>
 <body>
   <h1>PHP katsetused</h1>
   <?php
      if(isSet($_REQUEST["firstName"])){
         if(strlen($_REQUEST["firstName"])>0){
           echo "Tere, $_REQUEST[firstName]! ";
         } else {
           echo "Nimi kirjutamata! ";
         }
}
      echo "Kell on: ".date("H:i:s");
   ?>
<br /> 
<?php
     if(isset($_REQUEST["age"])){
       if(strlen($_REQUEST["age"])>0){
         $v=intval($_REQUEST["age"]);
         for($i=0; $i<$v; $i++){
         echo "Palju onne!! ";
         }
} 
} 
?>
   <form action="proov2.php">
      Eesnimi: <input type="text" name="firstName" />
      Vanus: <input type="text" name="age" />
      <input type="submit" value="Sisesta" />
   </form>
 </body>
</html>
