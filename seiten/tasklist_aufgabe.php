<?php
namespace php\klassen;

$this->seiteninhalt = "";

#sempre tractarem les llistes d un usuari en concret
#wir arbeiten immer mit den Listen eines bestimmten Benutzers
$aufgabe = new Aufgabe($this->nr, $this->nr2);

switch($action)
{
	case "anlegen"      : $this->seiteninhalt = $aufgabe->anlegen();	break;
	case "zeigen"		: $this->seiteninhalt = $aufgabe->zeigen();		break;
	case "loeschen"		: $this->seiteninhalt = $aufgabe->loeschen();	break;		
	case "bearbeiten"	: $this->seiteninhalt = $aufgabe->bearbeiten();	break;		
	case "erledigen"	: $this->seiteninhalt = $aufgabe->erledigen();	break;		
	case "recover"		: $this->seiteninhalt = $aufgabe->recover();	break;		
	default:
		$this->seiteninhalt = "404 - Seite nicht gefunden";
}	

?>