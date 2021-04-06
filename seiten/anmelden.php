<?php
$string = "
<div id='container'>
	<h2>Anmeldungsformular</h2>
	<p class='meldung'>$meldung</p>
	<form class='big'id='anmeldungsFormular' action='' method='post'>
	  
	  <label for='email'> Email</label>
	  <input type='text' id='email' name='email' value='".@$_POST["email"]."'>
	  <label for='pwd'> Password</label>
	  <input type='password' id='pwd' name='pwd' value='".@$_POST["pwd"]."'>
	  
	  <p class='zentriert'>
		<input class='space_top' type='submit' value='Submit' name='anmelden'>
	  </p>
	  
	  <p class='zentriert registrieren'>Noch nicht registriert? <a href='registrieren'>Hier registrieren</a></p>
	</form>
</div>";
return $string;
?>
