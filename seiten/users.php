<?php
$string = "
<div id='container_list_form'>
	<h2>Benutzer <a class='space' title ='reload' href='/".BASIS_PFAD."/users_zeigen'><i class='fas fa-redo'></i></a></h2>";
	
	if(isset($action)){
		if($action == 'loeschen_besteatigen'){
			$string .="
				<div class='meldung'>
					$meldung
					<form class='zentriert' action='' method='POST'>
						<button type='submit' name='loeschen_ja'>JA</button>
						<button type='submit' name='loeschen_nein'>NEIN</button>
					</form>
				</div>";
		}
		elseif($action == 'loeschen_warning'){
			$string .="<div class='meldung'>$meldung</div>";		
		}
	}
	$string .="
	<table>
		<thead>
			 <tr>
				 <th>Nr.</th> 
				 <th>Name</th>
				 <th>Admin</th>
				 <th colspan='2'></th> 
			 </tr>
		</thead>
		<tbody>";
			foreach($antwort as $user)
			{
				$string .= "
				<tr>
					 <td>".$user['nr_user']."</td>   
					 <td>".$user['benutzername']."</td>";
					if($user['admin'] == 1){
						$string .= "<td><i class='fas fa-check-circle'></i></td>";
					}
					else{
						$string .= "<td></td>";
					}
					$string .= "   
					 <td><a href='/".BASIS_PFAD."/user_bearbeiten/".$user['nr_user']."'><i class='fas fa-edit'></i></a></td>   
					 <td><a href='/".BASIS_PFAD."/user_loeschen/".$user['nr_user']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-trash-alt'></i></a></td>     
				</tr>";
			}
	$string .= "
		<tbody>
	</table>";
	if($this->pagination_action){
	$string .= "
		<form class='klein' id='' action='' method='post'>
		<div id='previous_next'>
			<p><button type='submit' name='previous'><i class='fas fa-chevron-left'></i></button></p>
			<p><button type='submit' name='next'><i class='fas fa-chevron-right'></i></button></p>
		</div>
		<input type='hidden' id='' name='pointer_ini'  value='".$this->pointer_ini."'>
		<input type='hidden' id='' name='pointer_end'  value='".$this->pointer_end."'>
		</form>";
	}
	$string .= "
	
	<form class='klein submit_pos' id='useranlegen' action='' method='post'> 
		<input type='submit' value='Hinzufügen' name='registrieren'>
	</form>	
</div>";
return $string;
?>


