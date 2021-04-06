<?php
$string = "
<div id='container_list_form'>
<h2>Listen</h2>";
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
	}
	else{
		$string .="<div class='meldung'>$meldung</div>";		
	}
	$string .="
	<table>
		<thead>
			<tr>
				<th colspan='2'></th>
				<th colspan='2'>Aufgaben</th>
				<th colspan='3'></th>
			  </tr>
		
			 <tr>
				 <th>Liste Nr.</th> 
				 <th>Bezeichnung</th>
				 <th> Aktiv</th>
				 <th> Erledig</th>
				 <th colspan='3'></th> 
			 </tr>
		</thead>
		<tbody>";
			foreach($antwort as $liste)
			{
				#mirem quantes tasques ja son fetes i quantes no
				$erledigt_antwort = $db->lesen("select count(*) as erledigt from aufgaben where nr_liste ='".$liste['nr_liste']."' and erledigt = 1");
				$erledigt_ja = $erledigt_antwort[0]['erledigt'];
				$erledigt_antwort = $db->lesen("select count(*) as no_erledigt from aufgaben where nr_liste ='".$liste['nr_liste']."' and erledigt = 0");
				$erledigt_nein = $erledigt_antwort[0]['no_erledigt'];
				
			$string .= "<tr>
					<td>".$liste['nr_liste']."</td>";
					if(isset($nr_liste_bearbeiten) && $nr_liste_bearbeiten == $liste['nr_liste']){
						$string .="
						<form id='listebearbeiten' action='' method='post'>
							<td><input class='klein2' type='text' id='bezeichnung' name='bezeichnung'  value='".$liste['bezeichnung']."'></td>
							<td>".$erledigt_nein."</td>   
							<td>".$erledigt_ja."</td> 
							<td><a href='/".BASIS_PFAD."/aufgaben_zeigen/".$liste['nr_liste']."'><i class='fas fa-tasks'></i></a></td>  
							<td><input type='submit' value='Submit' name='bearbeiten_submit'></td>
							<td><a href='/".BASIS_PFAD."/liste_loeschen/".$liste['nr_liste']."'><i class='fas fa-trash-alt'></i></a></td>    
						</form>";
					}
					else{
						$string .="<td>".$liste['bezeichnung']."</td> 
						<td>".$erledigt_nein."</td>   
						<td>".$erledigt_ja."</td>   
						<td><a href='/".BASIS_PFAD."/aufgaben_zeigen/".$liste['nr_liste']."'><i class='fas fa-tasks'></i></a></td>   
						<td><a href='/".BASIS_PFAD."/liste_bearbeiten/".$liste['nr_liste']."'><i class='fas fa-edit'></i></a></td>   
						<td><a href='/".BASIS_PFAD."/liste_loeschen/".$liste['nr_liste']."'><i class='fas fa-trash-alt'></i></a></td>";     			
					}
					$string .="</tr>";
			}
	$string .= "
	</table>
	<form class='klein' id='liestenanlegen' action='' method='post'>";
	if(isset($meldung2) && !empty($meldung2)){
		$string .= "<input type='text' id='bezeichnung' name='bezeichnung' placeholder='".$meldung2."' value=''>";
	}
	else{
		$string .= "<input  type='text' id='bezeichnung' name='bezeichnung' placeholder='Neue Liste' value=''>";
	}
		$string .= "<input class='space space_top' type='submit' value='Submit' name='anlegen'>
	</form>
</div>";
return $string;
?>

