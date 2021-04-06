<?php
namespace php\Klassen;
use php\Klassen\pdo\Datenbank;

class Aufgabe
{
	// Eigenschaften
    protected $nr_aufgabe 	   = "";     
    protected $nr_liste 	   = "";     
    protected $bezeichnung     = "";     
    protected $erledigt 	   = "";     
    protected $datum_eingabe   = "";     
    protected $datum_aenderung = "";   
	protected $temp_daten;
	
	protected $rows = 4;
	protected $pointer_ini = 0;
	protected $pointer_end = 0;
	protected $previous_next = 0; #0 = no prervious no next, previous, next
	
	#Traits
	use \php\traits\WerkFunktionen;
	
	# Constructor
    public function __construct($nr_liste = null,$nr_aufgabe = null)
    {
		if(!IS_NULL($nr_liste))
		{
			$this->nr_liste = $nr_liste;
		}
		if(!IS_NULL($nr_aufgabe))
		{
			$this->nr_aufgabe  = $nr_aufgabe;
		}
    }
	#Magische Function
	public function __toString()
	{
		return "test return toString class: Liste";
	}	
	// Methoden
	
	public function zeigen()
	{
		$meldung = "";
		$meldung2 = "";
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}
		$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
		$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
		$this->pagination_action = 0;
		if(isset($_POST['previous'])) $this->pagination_action = 'previous';
		if(isset($_POST['next'])) 	  $this->pagination_action = 'next';
		$db = new Datenbank();
		$antwort = $db->lesen("select * from aufgaben where nr_liste ='".$this->nr_liste."'");
		$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
		return include("seiten/aufgaben.php");
	}
	public function anlegen()
	{
		$this->temp_daten['bezeichnung'] = htmlspecialchars($_POST['bezeichnung']);
		
		if(empty($this->temp_daten['bezeichnung'])){
				return "bitte, das Feld Bezeichnung ausfüllen";
		}
		else{
			#insert BD
			$db = new Datenbank();
			$this->temp_daten["nr_liste"]  = $this->nr_liste;
			$this->temp_daten['erledigt'] = 0;
			#This will hold the server's current Date and Time.
			$datetime = date_create()->format('Y-m-d H:i:s');
			$this->temp_daten["datum_eingabe"]  = $datetime;  
			$this->temp_daten["datum_aenderung"]= $datetime;
			
			echo "<PRE>";
			print_r($this->temp_daten);
			echo "</PRE>";
			
			
			$nr_liste = $db->einfuegen("
					INSERT INTO aufgaben 
					(nr_liste, bezeichnung, erledigt, datum_eingabe, datum_aenderung)
					VALUES
					(:nr_liste,:bezeichnung,:erledigt,:datum_eingabe,:datum_aenderung)",$this->temp_daten);
					
			#insert erfolgreich
			if($nr_liste != -1){
				header("Location: /".BASIS_PFAD."/aufgaben_zeigen/".$this->nr_liste); #anem a la startseite
				# PHP Programm beenden
				exit;	
			}
		}
	}
	public function loeschen()
	{
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}

		$meldung = "";
		$db = new Datenbank();

		if(isset($_POST['loeschen_nein']) || isset($_POST['loeschen_ja'])){
			#$_POST['loeschen_nein'] --> Man braucht nichts zu tun, nur die Seite neu laden
			#$_POST['loeschen_ja'] --> delete BD
			if(isset($_POST['loeschen_ja'])){
				$antwort = $db->loeschen(" DELETE FROM aufgaben 
											WHERE nr_liste   ='".$this->nr_liste."'
											  AND nr_aufgabe ='".$this->nr_aufgabe."'");
			}
			header("Location: /".BASIS_PFAD."/aufgaben_zeigen/".$this->nr_liste); #anem a la startseite
			# PHP Programm beenden
			exit;	
		}
		else{
			$antwort_aufgabe = $db->lesen("SELECT bezeichnung 
											 FROM aufgaben 
											WHERE nr_liste ='".$this->nr_liste."'
											  AND nr_aufgabe ='".$this->nr_aufgabe."'");
			$action = "loeschen_besteatigen";
			$meldung = "Möchten Sie die Aufgabe <strong>".$antwort_aufgabe[0]['bezeichnung']."</strong> wirklich löschen? ";
			
			$this->pagination_action = "loeschen";
			if(isset($_POST['previous'])) $this->pagination_action = 'previous';
			if(isset($_POST['next'])) 	  $this->pagination_action = 'next';
			if($this->pagination_action == "loeschen"){
				#agafem els indicadors que ens venen com a parametres
				$this->pointer_ini   = isset($_POST['p_ini']) ? $_POST['p_ini'] : 0;
				$this->pointer_end   = isset($_POST['p_end']) ? $_POST['p_end'] : 0;
			}
			else{
				$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
				$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
			}
			$antwort = $db->lesen("select * from aufgaben where nr_liste ='".$this->nr_liste."'");
			$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
			return include("seiten/aufgaben.php");
		}
	}
	public function bearbeiten()
	{
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}

		$meldung = "";
		$db = new Datenbank();

		if(isset($_POST['bearbeiten_submit'])){
			#update a la bd
			$this->bezeichnung = htmlspecialchars($_POST['bezeichnung']);  
			
			#This will hold the server's current Date and Time.
			$datetime = date_create()->format('Y-m-d H:i:s');
			#$this->temp_daten["datum_aenderung"]= $datetime;

			$rows_nr = $db->aktualisieren("
					UPDATE aufgaben 
					   SET bezeichnung = '".$this->bezeichnung."',
						   datum_aenderung = '".$datetime."'
					 WHERE nr_liste   = '".$this->nr_liste."'
					   AND nr_aufgabe = '".$this->nr_aufgabe."'");
					 
			header("Location: /".BASIS_PFAD."/aufgaben_zeigen/".$this->nr_liste); #tornem a entrar des de la startseite
			# PHP Programm beenden
			exit;	
		}
		else{
			$nr_aufgabe_bearbeiten = $this->nr_aufgabe; #indiquem quina aufgabe volem modificar
			$this->pagination_action = "bearbeiten";
			if(isset($_POST['previous'])) $this->pagination_action = 'previous';
			if(isset($_POST['next'])) 	  $this->pagination_action = 'next';
			if($this->pagination_action == "bearbeiten"){
				#agafem els indicadors que ens venen com a parametres
				$this->pointer_ini   = isset($_POST['p_ini']) ? $_POST['p_ini'] : 0;
				$this->pointer_end   = isset($_POST['p_end']) ? $_POST['p_end'] : 0;
			}
			else{
				$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
				$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
			}
			$antwort = $db->lesen("select * from aufgaben where nr_liste ='".$this->nr_liste."'");
			$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
			return include("seiten/aufgaben.php");
		}
	}
	public function erledigen()
	{
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}
		#aqui cal fer un update de l atribut erledigt a 1 i carregar la pg de nou amb la tasca tatxada
		$db = new Datenbank();
		
		#This will hold the server's current Date and Time.
		$datetime = date_create()->format('Y-m-d H:i:s');
		
		$rows_nr = $db->aktualisieren("
				UPDATE aufgaben 
				   SET erledigt = '1',
					   datum_aenderung = '".$datetime."'
				 WHERE nr_liste   = '".$this->nr_liste."'
				   AND nr_aufgabe = '".$this->nr_aufgabe."'");
				   
			$this->pagination_action = "erledigen";
			if(isset($_POST['previous'])) $this->pagination_action = 'previous';
			if(isset($_POST['next'])) 	  $this->pagination_action = 'next';
			if($this->pagination_action == "erledigen"){
				#agafem els indicadors que ens venen com a parametres
				$this->pointer_ini   = isset($_POST['p_ini']) ? $_POST['p_ini'] : 0;
				$this->pointer_end   = isset($_POST['p_end']) ? $_POST['p_end'] : 0;
			}
			else{
				$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
				$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
			}
			$antwort = $db->lesen("select * from aufgaben where nr_liste ='".$this->nr_liste."'");
			$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
			return include("seiten/aufgaben.php");
	}
	public function recover()
	{
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}
		#aqui cal fer un update de l atribut erledigt a 1 i carregar la pg de nou amb la tasca tatxada
		$db = new Datenbank();
		
		#This will hold the server's current Date and Time.
		$datetime = date_create()->format('Y-m-d H:i:s');
		
		$rows_nr = $db->aktualisieren("
				UPDATE aufgaben 
				   SET erledigt = '0',
					   datum_aenderung = '".$datetime."'
				 WHERE nr_liste   = '".$this->nr_liste."'
				   AND nr_aufgabe = '".$this->nr_aufgabe."'");
				 
			$this->pagination_action = "recover";
			if(isset($_POST['previous'])) $this->pagination_action = 'previous';
			if(isset($_POST['next'])) 	  $this->pagination_action = 'next';
			if($this->pagination_action == "recover"){
				#agafem els indicadors que ens venen com a parametres
				$this->pointer_ini   = isset($_POST['p_ini']) ? $_POST['p_ini'] : 0;
				$this->pointer_end   = isset($_POST['p_end']) ? $_POST['p_end'] : 0;
			}
			else{
				$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
				$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
			}
			$antwort = $db->lesen("select * from aufgaben where nr_liste ='".$this->nr_liste."'");
			$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
			return include("seiten/aufgaben.php");	   
	}
}
?>