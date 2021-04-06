<?php
$string = "
<div id='container'>
	<h2>Registrierungsformular</h2>
	<p class='meldung'>$meldung</p>
	<form class='big' id='registrierungsFormular' action='' method='post'>
	
		<label for='benutzername'>Benutzername<small><sup>*</sup></small></label>
		<input type='text' id='benutzername' name='benutzername' value='".@$this->temp_daten["benutzername"]."'>
		<label for='email'> Email<small><sup>*</sup></small></label>
		<input type='text' id='email' name='email' value='".@$this->temp_daten["email"]."'>
		<label for='pwd'> Password<small><sup>*</sup></small></label>
		<input type='password' id='pwd' name='pwd' value='".@$this->temp_daten["pwd"]."'>
		<label for='pwd2'> Confirm Password<small><sup>*</sup></small></label>
		<input type='password' id='pwd2' name='pwd2' value='".@$pwd2."'>
		<div id='zweispalter' class='clerarfix'>
			<div>";
				if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
					$string .= "<label class='container_chkbox'>Admin";
					if(@$this->temp_daten["admin"] == 1){
						$string .= "<input type='checkbox' id='admin' name='admin' checked >";
					}
					else{
						$string .= "<input type='checkbox' id='admin' name='admin' >";
					}
					$string .="
					<span class='checkmark'></span>
					</label>";
				}
			$string .="
			</div>
			<div>
				<small><sup>*</sup> Pflicht Felder</small>
			</div>
		</div>
	   <p class='zentriert'>
		<input class='space_top' type='submit' value='Submit' name='registrieren'>
	   </p>
	</form>
</div>";
return $string;
?>
