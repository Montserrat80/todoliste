<?php
namespace php\klassen;
/*
Aufgaben:
-	HTML-Code zur Verfügung stellen
*/
class Webseite
{
	# Eigenschaften
	public $navigationslinks = 'Links';	
	public $seiteninhalt 	 = 'Unbekannt, noch nicht definierter Inhalt';
	public $footerlinks 	 = 'Links';
	
	# Methoden
	public function __construct($linksNavigation,$seiteninhalt,$footerlinks)
	{
		$this->navigationslinks = $linksNavigation;
		$this->seiteninhalt = $seiteninhalt;
		$this->footerlinks = $footerlinks;		
	}	

	public function grundgeruest()
	{
		$string = file_get_contents('html/grundgeruest.html');	
		
		if(isset($_SESSION['benutzername'])){
			$string = str_replace("#BENUTZER#",'Hallo, '.$_SESSION['benutzername'],$string);
		}
		else{
			$string = str_replace("#BENUTZER#",'',$string);
		}
		$string = str_replace("#NAVIGATION#",$this->navigationslinks,$string);
		$string = str_replace("#FOOTER_LINKS#",$this->footerlinks,$string);
		$string = str_replace("#PATH#",PFAD_KORREKTUR,$string);
		$string = str_replace("#INHALT#",$this->seiteninhalt,$string);
		
		return $string;
	}

	public function __toString()
	{
		return $this->grundgeruest();
	}	
}
?>