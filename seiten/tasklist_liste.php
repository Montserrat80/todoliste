<?php
namespace php\klassen;

$this->seiteninhalt = "";

#sempre tractarem les llistes d un usuari en concret
#wir arbeiten immer mit den Listen eines bestimmten Benutzers

$liste = new Liste($this->nr,$_SESSION["nr_user"]);

switch($action)
{
	case "anlegen"      : $this->seiteninhalt = $liste->anlegen();		break;
	case "zeigen"		: $this->seiteninhalt = $liste->zeigen();		break;
	case "loeschen"		: $this->seiteninhalt = $liste->loeschen();		break;		
	case "bearbeiten"	: $this->seiteninhalt = $liste->bearbeiten();	break;		
	default:
		$this->seiteninhalt = "404 - Seite nicht gefunden";
}	

?>