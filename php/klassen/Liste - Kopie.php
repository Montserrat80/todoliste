<?php
namespace php\Klassen;
use php\Klassen\pdo\Datenbank;

class Liste
{
	// Eigenschaften
    protected $nr_liste 	   = "";     
    protected $bezeichnung     = "";     
    protected $nr_user 		   = "";     
    protected $datum_eingabe   = "";     
    protected $datum_aenderung = "";   
	
	protected $temp_daten;	#array on hi guardem les dades de l´usuari, ens va molt be com a parametre per fer inserts, updates ...
	protected $rows = 4;
	
	protected $pointer_ini = 0;
	protected $pointer_end = 0;
	protected $previous_next = 0; #0 = no prervious no next, previous, next
	# Constructor
    public function __construct($nr_liste = null,$nr_user = null)
    {
		if(!IS_NULL($nr_liste))
		{
			$this->nr_liste = $nr_liste;
		}
		if(!IS_NULL($nr_user))
		{
			$this->nr_user = $nr_user;
		}
    }
	
	#Magische Function
	public function __toString()
	{
		return "test return toString class: Liste";
	}	
	
	protected function pagination($db,&$ini,&$end,&$previous_next)
	{
		$antwort = $db->lesen("select * from listen where nr_user ='".$this->nr_user."'");
		$count = count($antwort);
		if($count > $this->rows){
			if($previous_next == 'previous' && $ini > 0){
				$ini = $ini - $this->rows;
			}
			if($previous_next == 'next' && $end < $count-1){
				$ini = $end + 1;
			}
			$end = ($ini + $this->rows)-1;
			if($end >= $count) $end = $count - 1;
			for($i = $ini; $i <= $end; $i++){
				$a[$i] = $antwort[$i];
			}
			$previous_next = 1;
		}
		else{
			$previous_next = 0;
			$a = $antwort;
		}
		return $a;
	}

	// Methoden
	public function zeigen()
	{
		$meldung = "";
		$meldung2 = "";
		$db = new Datenbank();
		
		if(isset($_POST['anlegen'])){
			$meldung2 = $this->anlegen("listen");
		}
		if(isset($_POST['previous']) || isset($_POST['next'])){
			$this->pointer_ini = $_POST['pointer_ini'];
			$this->pointer_end = $_POST['pointer_end'];
			if(isset($_POST['previous'])) $this->previous_next = 'previous';
			if(isset($_POST['next'])) 	  $this->previous_next = 'next';
		}
		$antwort = $this->pagination($db, $this->pointer_ini,$this->pointer_end,$this->previous_next);
		
		return include("seiten/listen.php");
	}
	public function uebersicht()
	{
		$db = new Datenbank();
		$antwort = $db->lesen("select count(*) as anzahl from listen where nr_user ='".$this->nr_user."'");
		return $antwort[0]["anzahl"];
	}
	public function anlegen($herkunft="uebersicht")
	{	
		#aqui puc venir des de la pg de llistes o be des del dashboard (Übersicht))- liste anlegen, em d anar en comtpe pq el comportament
		#es diferent només en el cas de que el camp input estigui e blanc.
		#si venim de les llistes i el camp esta en blanc voldrem treure el $meldug2
		$meldung="";
		#si venim del formulari havent premut el boto submit: hinzufuegen
		if(isset($_POST["anlegen"]))
		{
			$fehlerMeldung = 0;
			$this->temp_daten["bezeichnung"] = htmlspecialchars($_POST['bezeichnung']);
			
			
			if(empty($this->temp_daten["bezeichnung"])){
				if($herkunft=="listen"){
					return "bitte, das Feld Bezeichnung ausfüllen";
				}
				else{
					$meldung = "bitte, das Feld Bezeichnung ausfüllen";
					$fehlerMeldung++;
					return include("seiten/listeanlegen.php");
					exit;
				}
			}
			
			if($fehlerMeldung == 0){
				#insert BD
				$db = new Datenbank();
				#$this->temp_daten["nr_user"] = $_SESSION["nr_user"];
				$this->temp_daten["nr_user"] = $this->nr_user;
				
				#This will hold the server's current Date and Time.
				$datetime = date_create()->format('Y-m-d H:i:s');
				$this->temp_daten["datum_eingabe"]  = $datetime;  
				$this->temp_daten["datum_aenderung"]= $datetime;
				
				$nr_liste = $db->einfuegen("
						INSERT INTO listen 
						(bezeichnung, nr_user, datum_eingabe, datum_aenderung)
						VALUES
						(:bezeichnung,:nr_user,:datum_eingabe,:datum_aenderung)",$this->temp_daten);
						
				#insert erfolgreich
				if($nr_liste != -1){
					header("Location: /".BASIS_PFAD."/listen_zeigen"); #anem a la startseite
					# PHP Programm beenden
					exit;	
				}
			}
		}
		else{
			return include("seiten/listeanlegen.php");
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
				$antwort = $db->loeschen(" DELETE FROM listen WHERE nr_liste ='".$this->nr_liste."'");
			}
			header("Location: /".BASIS_PFAD."/listen_zeigen"); #anem a la startseite
			# PHP Programm beenden
			exit;	
		}
		else{
			$antwort_liste = $db->lesen("select bezeichnung from listen where nr_liste ='".$this->nr_liste."'");
			$antwort = $db->lesen("select count(*) as anzahl from aufgaben where nr_liste ='".$this->nr_liste."' and erledigt = 0");
			if($antwort[0]["anzahl"] > 0){
				#$action = "loeschen_warning";
				$meldung = "Die Liste <strong>".$antwort_liste[0]['bezeichnung']."</strong> darf nicht gelöscht werden, da sie noch zugehörige aktive aufgaben hat";
			}
			else{
				$action = "loeschen_besteatigen";
				$meldung = "Möchten Sie die Liste <strong>".$antwort_liste[0]['bezeichnung']."</strong> wirklich löschen? ";
			}
			
			if(isset($_POST['previous']) || isset($_POST['next'])){
				$this->pointer_ini = $_POST['pointer_ini'];
				$this->pointer_end = $_POST['pointer_end'];
				if(isset($_POST['previous'])) $this->previous_next = 'previous';
				if(isset($_POST['next'])) 	  $this->previous_next = 'next';
			}
			$antwort = $this->pagination($db, $this->pointer_ini,$this->pointer_end,$this->previous_next);
		
			return include("seiten/listen.php");
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
			#$this->nr_liste    = htmlspecialchars($_POST['nr_liste']);  
			
			#This will hold the server's current Date and Time.
			$datetime = date_create()->format('Y-m-d H:i:s');
			#$this->temp_daten["datum_aenderung"]= $datetime;
		
			$rows_nr = $db->aktualisieren("
					UPDATE listen 
					   SET bezeichnung = '".$this->bezeichnung."',
						   datum_aenderung = '".$datetime."'
					 WHERE nr_liste = '".$this->nr_liste."'");
					 
			header("Location: /".BASIS_PFAD."/listen_zeigen"); #tornem a entrar des de la startseite
			# PHP Programm beenden
			exit;	
		}
		else{
			$nr_liste_bearbeiten = $this->nr_liste; #indiquem quina llista volem modificar
			#$antwort = $db->lesen("select * from listen where nr_user ='".$this->nr_user."'");
			if(isset($_POST['previous']) || isset($_POST['next'])){
				$this->pointer_ini = $_POST['pointer_ini'];
				$this->pointer_end = $_POST['pointer_end'];
				if(isset($_POST['previous'])) $this->previous_next = 'previous';
				if(isset($_POST['next'])) 	  $this->previous_next = 'next';
			}
			$antwort = $this->pagination($db, $this->pointer_ini,$this->pointer_end,$this->previous_next);
			return include("seiten/listen.php");
		}
	}
}
?>