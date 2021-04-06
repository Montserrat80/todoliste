<?php
$string = "
<div id='container_list_form'>
<h2>Aufgaben <a class='space' title ='reload' href='/".BASIS_PFAD."/aufgaben_zeigen/".$this->nr_liste."'><i class='fas fa-redo'></i></a></h2>";
	if(isset($action)){
		if($action == 'loeschen_besteatigen'){
			$string .="
				<div class='meldung'>
					".@$meldung."
					
					<form class='zentriert' action='' method='POST'>
						<button type='submit' name='loeschen_ja'>JA</button>
						<button type='submit' name='loeschen_nein'>NEIN</button>
					</form>
				</div>";
		}
	}
	else{
		$string .="<div class='meldung'>".@$meldung."</div>";		
	}
	$string .="
	<table>
		<thead>
			 <tr>
				 <th>Aufgabe Nr.</th> 
				 <th>Bezeichnung</th>
				 <th colspan='3'></th> 
			 </tr>
		</thead>
		<tbody>";
			foreach($antwort as $aufgabe)
			{	
				$string .= "
				<tr>
				<td>".$aufgabe['nr_aufgabe']."</td>";
				if(isset($nr_aufgabe_bearbeiten) && $nr_aufgabe_bearbeiten == $aufgabe['nr_aufgabe']){
					$string .="
					<form id='aufgabebearbeiten' action='' method='post'>
						<td><input class='klein2' type='text' id='bezeichnung' name='bezeichnung'  value='".$aufgabe['bezeichnung']."'></td>";
						if($aufgabe['erledigt'] == 1){
							$string .="
							<td><a href='/".BASIS_PFAD."/aufgabe_recover/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-undo'></i></a></td>";   
						}
						else{
							$string .= "
							<td><a href='/".BASIS_PFAD."/aufgabe_erledigen/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-check'></i></a></td>";  
						} 
						$string .= "
						<td><input type='submit' value='Submit' name='bearbeiten_submit'></td>
						<td><a href='/".BASIS_PFAD."/aufgabe_loeschen/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-trash-alt'></i></a></td>    
					</form>";
				}
				else{
					if($aufgabe['erledigt'] == 1){
						$string .="<td class='erledigt'>".$aufgabe['bezeichnung']."</td> 
						<td><a href='/".BASIS_PFAD."/aufgabe_recover/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-undo'></i></a></td>";   
					}
					else{
						$string .= "<td>".$aufgabe['bezeichnung']."</td> 
						<td><a href='/".BASIS_PFAD."/aufgabe_erledigen/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-check'></i></a></td>";  
					}
					$string .= "<td><a href='/".BASIS_PFAD."/aufgabe_bearbeiten/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-edit'></i></a></td>   
					<td><a href='/".BASIS_PFAD."/aufgabe_loeschen/".$this->nr_liste."/".$aufgabe['nr_aufgabe']."/".$this->pointer_ini."/".$this->pointer_end."'><i class='fas fa-trash-alt'></i></a></td>";     			
				}
				$string .="</tr>";
			}
	$string .= "
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
	<form class='klein' id='liestenanlegen' action='' method='post'>";
	if(isset($meldung2) && !empty($meldung2)){
		$string .= "<input type='text' id='bezeichnung' name='bezeichnung' placeholder='".$meldung2."' value=''>";
	}
	else{
		$string .= "<input  type='text' id='bezeichnung' name='bezeichnung' placeholder='Neue Aufgabe' value=''>";
	}
		$string .= "<input class='space space_top' type='submit' value='Submit' name='anlegen'>
	</form>
	<div class='zurueck'>
		<a href='/".BASIS_PFAD."/listen_zeigen/".$_SESSION['nr_user']."'>Zurück</a>
	</div>
</div>";
return $string;
?>

