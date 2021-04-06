<?php
namespace php\klassen\pdo;
/*
Aufgaben:
- Verbindung aufbauen
- Verbindung schließen
- Tabelle auswählen
- Antwort der Datenbank auswerten
- select (Antwort: Die getroffene Auswahl an Datensätze)
- insert (Antwort: Der Primärschlüssel)
- update (Antwort: Die Anzahl der geänderten Datensätze)
- delete (Antwort: Die Anzahl der gelöschten Datensätze)
*/
class Datenbank
{
	public $verbindung;
	public $host = "localhost";
	#public $user = "root";
	public $user = "aplicuser";
	public $passwort = "12345678";
	public $datenbank = "todoliste";
	
	public function __construct()
	{
		#echo "<h1>Konstruktor wird gestartet (PDO)</h1>";
		
		$this->verbindung = new \PDO("mysql:host=".$this->host."; dbname=".$this->datenbank.";", 
									$this->user, 
									$this->passwort,
									
									array(
										\PDO::ATTR_ERRMODE 					=> \PDO::ERRMODE_WARNING,
										\PDO::ATTR_DEFAULT_FETCH_MODE 		=> \PDO::FETCH_ASSOC,
										\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY 	=> true,
										\PDO::MYSQL_ATTR_INIT_COMMAND 		=> "SET NAMES utf8"
									)
									
									);		
	}
	public function __destruct()
	{
		#echo "<h1>Destruktor wird gestartet (PDO)</h1>";
	}
	
	public function sql_abfrage($befehl, $daten = array())
	{
		$prepare = $this->verbindung->prepare($befehl); # Vorbereiten
		$prepare->execute($daten); # Ausführen
		return $prepare;
	}		
	
	public function einfuegen($befehl, $daten = array())
	{
		$antwort = $this->sql_abfrage($befehl, $daten);	
		if($this->verbindung->lastInsertId() > 0)
		{		
			return $this->verbindung->lastInsertId(); # der neue Primärschlüssel
		}
		else
		{
			#echo "Fehler beim Insert:";
			#echo $befehl;
			return -1;
		}
	}		
	public function aktualisieren($befehl, $daten = array())
	{
		$antwort = $this->sql_abfrage($befehl, $daten);	
		if($antwort == true)
		{
			$string = "Änderungen erfolgreich:";
			$string .= $antwort->rowCount()."x Datensätze verändert";
			#return $string;
			return $antwort->rowCount();
		}
		else
		{
			#return "Fehler:".$befehl;
			return -1;
		}		
	}	
	public function loeschen($befehl, $daten = array())
	{
		$antwort = $this->sql_abfrage($befehl, $daten);	
		if($antwort == true)
		{
			#$string = "Löschen erfolgreich:";
			#$string .= $antwort->rowCount()."x Datensätze gelöscht";
			
			#return $string;
			return $antwort->rowCount();
		}
		else
		{
			#return "Fehler:".$befehl;
			return -1;
		}		
	}	
	public function lesen($befehl, $daten = array())
	{
		$antwort = $this->sql_abfrage($befehl, $daten);	
		$datensaetze = $antwort->fetchAll();
		return $datensaetze;		
	}		
}

###################################################
/*
$db = new Datenbank();
echo $db->einfuegen("insert into farben (bezeichnung) values('orange')");
echo $db->einfuegen("insert into farben (bezeichnung) values(?)", array("orange"));
echo $db->einfuegen("insert into farben (bezeichnung) values(:farbe)", array("farbe" => "navy"));

echo $db->aktualisieren("update farben set bezeichnung = 'Orange' where bezeichnung='orange'");
echo $db->loeschen("delete from farben where bezeichnung = 'Orange'");
$datensaetze = $db->lesen("select * from farben where bezeichnung = :farbe", 
								array("farbe" => "navy"));
echo "<pre>";
print_r($datensaetze);
echo "<pre>";


echo $db->einfuegen("insert into farben (bezeichnung, hexwert) values(?,?)", 
array("orange","#fc0"));
*/

?>