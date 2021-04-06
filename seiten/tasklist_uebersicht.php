<?php
namespace php\klassen;


#$this->seiteninhalt = "";

#sempre tractarem les llistes d un usuari en concret
#wir arbeiten immer mit den Listen eines bestimmten Benutzers
$liste = new Liste(@$_POST['nr'],$_SESSION["nr_user"]);
$listen_anzahl = $liste->uebersicht();


if($_SESSION["admin"] == 1)
{
	$user = new User(@$_SESSION['nr_user']);
	$users_anzahl = $user->uebersicht();
}
return include("seiten/uebersicht.php");
?>