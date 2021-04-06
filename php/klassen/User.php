<?php
namespace php\Klassen;
use php\Klassen\pdo\Datenbank;

class User
{
	// Eigenschaften
    protected $nr_user 		   = "";     
    protected $benutzername    = "";     
    protected $email 		   = "";     
    protected $pwd 			   = "";      
    protected $admin 		   = "";     
    protected $datum_eingabe   = "";     
    protected $datum_aenderung = "";   
	protected $temp_daten;	#array on hi guardem les dades de l´usuari, ens va molt be com a parametre per fer inserts, updates ...

	protected $rows = 5;
	protected $pointer_ini = 0;
	protected $pointer_end = 0;
	protected $previous_next = 0; #0 = no prervious no next, previous, next
	
	#Traits
	use \php\traits\WerkFunktionen;

	# Constructor
    public function __construct($nr_user = null)
    {
		if(!IS_NULL($nr_user))
		{
			$this->nr_user  = $nr_user;
		}
    }
	
	#Magische Function
	public function __toString()
	{
		return "test return toString class: User";
	}	

	// Methoden
	public function zeigen()
	{
		$meldung = "";
		if(isset($_POST['registrieren'])){
			# automatische Weiterleitung zur Verwaltung
			header("Location: /".BASIS_PFAD."/registrieren"); #anem a la startseite
			# PHP Programm beenden
			exit;
		}
		$this->pointer_ini   = isset($_POST['pointer_ini']) ? $_POST['pointer_ini'] : 0;
		$this->pointer_end   = isset($_POST['pointer_end']) ? $_POST['pointer_end'] : 0;
		$this->pagination_action = 0;
		if(isset($_POST['previous'])) $this->pagination_action = 'previous';
		if(isset($_POST['next'])) 	  $this->pagination_action = 'next';

		$db = new Datenbank();
		$antwort = $db->lesen("select * from users");
		$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
		return include("seiten/users.php");
	}
	public function uebersicht()
	{
		$db = new Datenbank();
		$antwort = $db->lesen("select count(*) as anzahl from users");
		return $antwort[0]["anzahl"];
	}
	protected function anmeldung_setzen($admin)
	{	
		$_SESSION["nr_user"] = $this->nr_user;
		$_SESSION["benutzername"] = $this->benutzername;
		$_SESSION["login_erfolgreich"] = true;
		$_SESSION["admin"] = $admin;
		# automatische Weiterleitung zur startseite
		header("Location: /".BASIS_PFAD."/uebersichtseite"); 
		# PHP Programm beenden
		exit; 
	}
	public function abmelden()
	{	
		if(isset($_POST["abmeldenBesteatig"]))
		{
			unset($_SESSION["nr_user"]);
			unset($_SESSION["benutzername"]);
			unset($_SESSION["login_erfolgreich"]);			
			unset($_SESSION["admin"]);	
			# automatische Weiterleitung zur Verwaltung
			header("Location: /".BASIS_PFAD.""); #anem a la startseite
			# PHP Programm beenden
			exit;			
		}
		else{
			return include("seiten/abmelden.php");
		}
	}
	public function anmelden()
	{	
		$meldung="";
		if(isset($_POST["anmelden"]))
		{
			$db = new Datenbank();
			$antwort = $db->lesen("select * from users where email='".htmlspecialchars($_POST["email"]."'"));
			
			#print_r($antwort);
			if(count($antwort) == 1)
			{	
				if(password_verify($_POST["pwd"], $antwort[0]["pwd"]))
				{
					$this->nr_user 		   = $antwort[0]["nr_user"];      
					$this->benutzername    = $antwort[0]["benutzername"];      
					$this->email 		   = $antwort[0]["email"];      
					$this->pwd 			   = $antwort[0]["pwd"];     
					$this->admin 		   = $antwort[0]["admin"];      
					#$this->datum_eingabe   = $antwort[0]["datum_eingabe"];    
					#$this->datum_aenderung = $antwort[0]["datum_aenderung"]; 
					
					$this->anmeldung_setzen($this->admin);
				}
				else
				{
					$meldung="Das Passwort stimmt nicht";
					$_SESSION["login_erfolgreich"] = false;
					return include("seiten/anmelden.php");
				}
			}
			else
			{
				$meldung="Die E-Mail ist nicht vorhanden";
				$_SESSION["login_erfolgreich"] = false;
				return include("seiten/anmelden.php");
			}
		}
		else{
			return include("seiten/anmelden.php");
		}
	}
	public function registrieren()
	{	
		$meldung="";
		#si venim del formulari havent premut el boto submit: registrieren
		if(isset($_POST["registrieren"]))
		{
			$fehlerMeldung = 0;
			
			$this->temp_daten["benutzername"]	= htmlspecialchars($_POST['benutzername']);  
			$this->temp_daten["email"]			= htmlspecialchars($_POST['email']); 
			$this->temp_daten["pwd"]			= htmlspecialchars($_POST['pwd']);  
			$pwd2			   					= htmlspecialchars($_POST['pwd2']);  
			$this->temp_daten["admin"]			= isset($_POST['admin'])? 1:0;  
			#$this->temp_daten["datum_eingabe"]  = "";  
			#$this->temp_daten["datum_aenderung"]= "";
			
			#Prüfung: kein Feld darf leer sein
			if(empty($this->temp_daten["benutzername"]) || empty($this->temp_daten["email"]) || empty($this->temp_daten["pwd"]) || empty($pwd2)){
				$meldung = "bitte alle Pflichtfelder ausfüllen";
				$fehlerMeldung++;
			}
			#Prüfung: 1.- Mail ist bereits vorhanden? - 2.- pwd == pwd2? - 3.- pwd mindestens 5 Zeichnen?
			else{
				$db = new Datenbank();
				$antwort = $db->lesen("select * from users where email='".$this->temp_daten["email"]."'");
				#print_r($antwort);
				
				if(count($antwort) == 1){
					$meldung = "E-Mail ist bereits vorhanden";
					$fehlerMeldung++;
				}
				else{
					if($this->temp_daten["pwd"] != $pwd2){
						$meldung = "Password und Confirm-Password sollten übereinstimmen ! ";
						$fehlerMeldung++;
					}
					else{
						if(strlen($this->temp_daten["pwd"]) < 5){
							$meldung = "Das Kennwort muss aus mindestens 5 Zeichen bestehen ! ";
							$fehlerMeldung++;
						}
						
					}
				}
			}		
			#wenn kein Fehlermeldung -> insert bd
			if($fehlerMeldung == 0)
			{
				#This will hold the server's current Date and Time.
				$datetime = date_create()->format('Y-m-d H:i:s');
				$this->temp_daten["datum_eingabe"]  = $datetime;  
				$this->temp_daten["datum_aenderung"]= $datetime;
				
				#pwd verschlüsseln
				$this->temp_daten["pwd"] = password_hash($this->temp_daten["pwd"], PASSWORD_DEFAULT);
				
				$this->nr_user = $db->einfuegen("
						INSERT INTO users 
						(benutzername, email, pwd, admin, datum_eingabe, datum_aenderung)
						VALUES
						(:benutzername,:email,:pwd,:admin,:datum_eingabe,:datum_aenderung)",$this->temp_daten);
						
				#insert erfolgreich
				if($this->nr_user != -1){
					#nomes canviem d usuari en cas que no sigui administrador, ja que aquest pot donar usuaris d alta
					#i no cal que canviem de login 
					if(!isset($_SESSION["login_erfolgreich"])){
						$this->anmeldung_setzen($this->temp_daten["admin"]);
					}
					else{
						header("Location: /".BASIS_PFAD."/users_zeigen"); #tornem a entrar des de la startseite
						# PHP Programm beenden
						exit;	
					}
				}
					
			}
			else{
				return include("seiten/registrieren.php");
			}
		}
		else{
			return include("seiten/registrieren.php");
		}
	}
	public function loeschen()
	{
		if(isset($_POST['registrieren'])){
			header("Location: /".BASIS_PFAD."/registrieren"); #tornem a entrar des de la startseite
			# PHP Programm beenden
			exit;	
		}
		
		$meldung = "";
		$db = new Datenbank();
	
		if(isset($_POST['loeschen_nein']) || isset($_POST['loeschen_ja'])){
			#$_POST['loeschen_nein'] --> Man braucht nichts zu tun, nur die Seite neu laden
			#$_POST['loeschen_ja'] --> delete BD
			if(isset($_POST['loeschen_ja'])){
				$antwort = $db->loeschen(" DELETE FROM users WHERE nr_user ='".$this->nr_user."'");
			}
			header("Location: /".BASIS_PFAD."/users_zeigen"); #tornem a entrar des de la startseite
			# PHP Programm beenden
			exit;	
		}
		else{
			if(($_SESSION['nr_user']) == $this->nr_user){
				$action = "loeschen_warning";
				$meldung = "Man darf den Benutzer, mit dem Sie gerade angemeldet sind, nicht löschen";
			}
			else{
				$antwort_user = $db->lesen("select benutzername from users where nr_user ='".$this->nr_user."'");
				$antwort = $db->lesen("select count(*) as anzahl from listen where nr_user ='".$this->nr_user."'");
				if($antwort[0]["anzahl"] > 0){
					$action = "loeschen_warning";
					$meldung = "Der Benutzer <strong> ".$antwort_user[0]['benutzername']." </strong>darf nicht gelöscht werden, da noch zugehörige Listen hat";
				}
				else{	
					$action = "loeschen_besteatigen";
					$meldung = "Möchten Sie der User <strong>".$antwort_user[0]['benutzername']."</strong> wirklich löschen? ";
				}
			}
			#----------------
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
			$antwort = $db->lesen("select * from users");
			$antwort = $this->pagination($antwort, $this->pointer_ini,$this->pointer_end,$this->pagination_action);
		
			
			#-----------------
			
			
			return include("seiten/users.php");
		}
		
		
	}
	public function bearbeiten()
	{
		$meldung="";
		$fehlerMeldung = 0;
		if(isset($_POST['bearbeiten_submit'])){
			$this->benutzername	= htmlspecialchars($_POST['benutzername']);  
			$this->email		= htmlspecialchars($_POST['email']); 
			$this->pwd			= htmlspecialchars($_POST['pwd']);  		   					
			$this->admin		= isset($_POST['admin'])? 1:0;  
			#$this->nr_user 		= $_POST['nr_user'];
			
			#Prüfung: kein Feld darf leer sein
			if(empty($this->benutzername) || empty($this->email)){
				$meldung = "bitte alle Pflichtfelder ausfüllen";
				$fehlerMeldung++;
			}
			#Prüfung: 1.- comprovar que Mail ist bereits vorhanden? 
			#2.- si hem introduit una pwd mindestens 5 Zeichnen?
			else{
				$db = new Datenbank();
				$antwort = $db->lesen("
					SELECT * 
					  FROM users 
					 WHERE email='".$this->email."' 
					   AND nr_user !='".$this->nr_user."'");
				if(count($antwort) == 1){
					$meldung = "E-Mail ist bereits vorhanden";
					$fehlerMeldung++;
				}
				else{
					#si hem entrat una nova pwd comprovar que com a minim tingui 5 caracters
					#si la llargada es ok --> encrptem la pwd
					if(!empty($this->pwd)){
						if(strlen($this->pwd) < 5){
							$meldung = "Das Kennwort muss aus mindestens 5 Zeichen bestehen ! ";
							$fehlerMeldung++;
						}
						else{
							#pwd verschlüsseln
							$this->pwd = password_hash($this->pwd, PASSWORD_DEFAULT);
						}
					}
				}
			}	
			
			#wenn kein Fehlermeldung -> update bd
			if($fehlerMeldung == 0){
				#This will hold the server's current Date and Time.
				$datetime = date_create()->format('Y-m-d H:i:s');
				#$this->temp_daten["datum_aenderung"]= $datetime;
				
				#si em modificat la pwd farem el seu update, sino farem update sense tenir-la en compte
			
				if(!empty($this->pwd)){
					echo "gravar 2";
					$rows_nr = $db->aktualisieren("
						UPDATE users 
						   SET benutzername = '".$this->benutzername."',
						       email        = '".$this->email."',
							   pwd			= '".$this->pwd."',
							   admin		= '".$this->admin."',
							   datum_aenderung = '".$datetime."'
						 WHERE nr_user = '".$this->nr_user."'");
				}
				else{
					echo "gravar 3";
					$rows_nr = $db->aktualisieren("
						UPDATE users 
						   SET benutzername = '".$this->benutzername."',
						       email        = '".$this->email."',
							   admin		= '".$this->admin."',
							   datum_aenderung = '".$datetime."'
						 WHERE nr_user = '".$this->nr_user."'");
				}
				header("Location: /".BASIS_PFAD."/users_zeigen"); #tornem a entrar des de la startseite
				# PHP Programm beenden
				exit;	
			}
			else{
				return include("seiten/user_bearbeiten.php");
			}
		}
		#$this->nr_user = $_POST['nr'];
		$db = new Datenbank();
		$antwort = $db->lesen("select benutzername,email, admin from users where nr_user ='".$this->nr_user."'");
		$this->benutzername    = $antwort[0]["benutzername"];      
		$this->email 		   = $antwort[0]["email"];       
		$this->admin 		   = $antwort[0]["admin"]; 
		#caldra anar en compte amb la pwd, pq si no la modifiquem no cal posar-la dins el update, cal conservar la que ja
		#hi ha a la bd
		$this->pwd = "";
		return include("seiten/user_bearbeiten.php");
		
	}
}
?>