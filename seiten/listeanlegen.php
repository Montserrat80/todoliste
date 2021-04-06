<?php
$string ="
<div id='container'>
	<h2>Liste anlegen</h2>
	<p class='meldung'>$meldung</p>
	<form class='big' id='liestenanlegen' action='' method='post'>
	  <label for='bezeichnung'>Bezeichnung</label>
	  <input type='text' id='bezeichnung' name='bezeichnung' value=''>
	  <p class='zentriert'>
		<input class='space_top' type='submit' value='Submit' name='anlegen'>
	  </p>
	</form>
</div>";
return $string;
?>

