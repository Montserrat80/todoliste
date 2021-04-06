<?php
namespace php\traits;

trait WerkFunktionen
{	
	public function pagination($daten_bd,&$ini,&$end,&$pagination_action)
	{
		$count = count($daten_bd);
		if($count > $this->rows){
			#si venim d esborrar o modificar no cal fer correr els punters, hem de visualituar les mateixes rows de la bd
			if(!($pagination_action == "loeschen" || $pagination_action == "bearbeiten" || $pagination_action == "erledigen" || $pagination_action == "recover")){
				
				if($pagination_action == 'previous' && $ini > 0){
					$ini = $ini - $this->rows;
				}
				if($pagination_action == 'next' && $end < $count-1){
					$ini = $end + 1;
					
				}
				$end = ($ini + $this->rows)-1;
				if($end >= $count) $end = $count - 1;
				
			}
			for($i = $ini; $i <= $end; $i++){
				$a_result[$i] = $daten_bd[$i];
			}
			#finalment posem aquesta variable a 1 per indicar que necessitem que es mostrin els botons previous i next a la pàgina
			$pagination_action = 1;
		}
		else{
			$pagination_action = 0; #no cal mostrar els botos previous i next a la pàgina
			$a_result = $daten_bd;
		}
		
		return $a_result;
	}
}	
?>