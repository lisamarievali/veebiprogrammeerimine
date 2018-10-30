<?php require("p2is.php"); ?>
<div id="sisu">
<pre><?php
  if(isset($_REQUEST["liininr"])){
    $sisu=@file_get_contents("liiniandmed/".
    	intval($_REQUEST["liininr"]).".txt");
    if($sisu){
      echo $sisu;
    } else {
      echo "Andmed puuduvad";
    }
  } else {
    echo "Liininumber puudub";
  }
?>
</pre>
</div>
<?php require("jalus.php"); ?>