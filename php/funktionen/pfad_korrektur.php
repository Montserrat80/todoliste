<?php
function pfad_korrektur()
{
	#echo "<pre>";
	#print_r($_SERVER["REQUEST_URI"]);
	#echo "</pre>";

	# WELCHE URL WIRD EINGEGEBEN?
	$anfrage_url = explode("/",$_SERVER["REQUEST_URI"]);	
	#echo "<pre>";
	#print_r($anfrage_url);
	#echo "</pre>";	
	
	unset($anfrage_url[0]); # leerer Eintrag entfernen
	$anfrage_url = implode("/", $anfrage_url);
	
	#echo "<pre>";
	#print_r($anfrage_url);
	#echo "</pre>";	
	

	# WELCHE DATEI WIRD GESTARTET?
	$script_name = explode("/",$_SERVER["SCRIPT_NAME"]);	
	unset($script_name[0]); # leerer Eintrag entfernen
	unset($script_name[ count($script_name) ]); # letzte Element entfernen
	$script_name = implode("/",$script_name); # umwandeln in einen String
	#       Name von der Konstante
	#					 Inhalt von der Konstante
	define("BASIS_PFAD", $script_name);
	
	#echo "<pre>";
	#echo $_SERVER["SCRIPT_NAME"]."<br />";
	#print_r($script_name);
	#echo "</pre>";	
								# Suchtext	  # Ersatztext		# Vorlage
	$differenz = str_replace($script_name, 		"", 			$anfrage_url);
	#echo $differenz;
	define("SEITENAUSWAHL", $differenz);
	
	# WO LIEGT DIE CSS DATEI ODER DIE BILDER
	$ordner = explode("/", $differenz);	
	unset($ordner[0]); # erster eintrag weg
	#unset($ordner[ count($ordner) ]); # letzter eintrag weg
	$anzahl = count($ordner);
	#echo "<pre>";
	#print_r($ordner);
	#echo "</pre>";	
	#echo "<h1>$anzahl</h1>";
	
	$pfad_korrektur = "";
	for($zahl = 1; $zahl < $anzahl; $zahl++)
	{
		$pfad_korrektur .= "../";
	}
	define("PFAD_KORREKTUR", $pfad_korrektur);
	#echo "<h1>".BASIS_PFAD."</h1>";
	#echo "<h1>".SEITENAUSWAHL."</h1>";
	#echo "<h1>".PFAD_KORREKTUR."</h1>";
}
?>