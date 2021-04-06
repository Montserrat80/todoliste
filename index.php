<?php
session_start();

require_once("php/funktionen/pfad_korrektur.php");
pfad_korrektur();
#echo "mostra el PFAD_KORREKTUR ".PFAD_KORREKTUR;
#echo "<br />";
#echo "mostra el BASIS_PFAD ".BASIS_PFAD;
#echo "<br />";
#echo "mostra el SEITENAUSWAHL ".SEITENAUSWAHL;


#no fem els require pq ara treballem amb namespace i a demés usem la funcio: spl_autoload_register, que s´executa automaticament el primer cop que creem una instancia d´una classe,
#en el moment que s´excuta el constructor. 
#Dins aquesta funcio farem el require pertinent. De fet podem definir N spl_autoload_register de forma que s´executin N funcions diferents.
function meiner_loadFunktion($ordner_und_datei)
{
	#fem un require i no un require_once, pq aquesta funcio es cridara només en el moment que es crea la primera instancia de la classe
	require($ordner_und_datei.".php");
}
#aquesta funcion s'executa a cada (new) creacio d´una instancia d´un objecte
#la classe ha de tenir el mateix nom que el fitxer
spl_autoload_register("meiner_loadFunktion"); 

##############################################################
#$navigation = new Navigation();
$navigation = new php\klassen\Navigation();
##############################################################
?>