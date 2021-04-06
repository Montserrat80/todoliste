<?php
namespace php\klassen;

$this->seiteninhalt = "";

$user = new User($this->nr);

switch($action)
{
	case "anmelden"     : $this->seiteninhalt = $user->anmelden();		break;
	case "abmelden"     : $this->seiteninhalt = $user->abmelden();		break;
	case "registrieren" : $this->seiteninhalt = $user->registrieren();	break;		
	case "zeigen" 		: $this->seiteninhalt = $user->zeigen();		break;
	case "loeschen"		: $this->seiteninhalt = $user->loeschen();		break;		
	case "bearbeiten"	: $this->seiteninhalt = $user->bearbeiten();	break;		
	default:
		$this->seiteninhalt = "404 - Seite nicht gefunden";
}	

?>