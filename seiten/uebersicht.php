<?php
$this->seiteninhalt ="
<div id='container_flex'>
	<div>
		<a class='box' href='listen_zeigen'>
			<i class='fas fa-list-alt symbol'></i> 
			<p>".@$listen_anzahl." Listen</p>
		</a>
		<a class='box' href='liste_anlegen'>
			<i class='fas fa-plus-square symbol'></i>
			<p>Liste anlegen</p>
		</a>
	</div>";
if($_SESSION["admin"] == 1)
{
	$this->seiteninhalt .="
	<div>
		<a class='box' href='users_zeigen'>
			<i class='fas fa-user symbol'></i>
			<p>".@$users_anzahl ."  Users</p>
		</a>
		<a class='box' href='registrieren'>
			<i class='fas fa-user-plus symbol'></i>
			<p>User Anlegen</p>
		</a>
	</div>";
}	
$this->seiteninhalt .="</div>";
?>
