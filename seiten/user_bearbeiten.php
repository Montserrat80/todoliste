<?php
$string = "
<div id='container'>
	<h2>User bearbeiten</h2>
	<p class='meldung'>$meldung</p>
	<form class='big' id='registrierungsFormular' action='' method='post'>
	
		<label for='benutzername'>Benutzername<small><sup>*</sup></small></label>
		<input type='text' id='benutzername' name='benutzername' value='".@$this->benutzername."'>
		<label for='email'> Email<small><sup>*</sup></small></label>
		<input type='text' id='email' name='email' value='".@$this->email."'>
		<label for='pwd'>Neue Password</label>
		<input type='password' id='pwd' name='pwd' value='".@$this->pwd."'>
		<div id='zweispalter' class='clearfix'>
			<div>
				<label class='container_chkbox'>Admin";
				if(@$this->admin == 1){
					$string .= "<input type='checkbox' id='admin' name='admin' checked >";
				}
				else{
					$string .= "<input type='checkbox' id='admin' name='admin' >";
				}
				$string .="
				<span class='checkmark'></span>
				</label>
			</div>
			<div>
				<small><sup>*</sup> Pflicht Felder</small>
			</div>
		</div>
	   <p class='zentriert'>
		<input type='submit' value='Submit' name='bearbeiten_submit'>
	   </p>
	   <p><input type='hidden' name='nr_user' value='".@$this->nr_user."'/></p>
	</form>
	<div class='zurueck'>
		<a href='/".BASIS_PFAD."/users_zeigen'>Zurück</a>
	</div>
</div>";
return $string;
?>
