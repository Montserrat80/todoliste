<?php
namespace php\klassen;
#use php\klassen\pdo\Datenbank;
/*
Aufgaben:
-	Benutzerinterkation auswerten
-	Unterseite auswählen
*/
class Navigation
{
	protected $linksNavigation = array("Startseite" => "/".BASIS_PFAD."");	
									   
	protected $linksFooter	   = array("Impressum" =>  "/".BASIS_PFAD."/impressumseite",
									   "Kontakt"   =>  "/".BASIS_PFAD."/kontaktseite",	
									   "Datenschutz" =>"/".BASIS_PFAD."/datenschutzseite");	
	public $seiteninhalt = "leer";
	public $nr  = null;
	public $nr2 = null;

	#Traits
	use \php\traits\LinksNav;

	# Methoden
	public function __construct()
	{
		$this->linkauswertung();
	}
	
	protected function linkauswertung()
	{
		
		$seitenauswahl = SEITENAUSWAHL;
		
		$seitenauswahl = SEITENAUSWAHL;
		
		# dynamische Links anzeuigen, je nach Loginzustand
		if(isset($_SESSION["login_erfolgreich"] ) && $_SESSION["login_erfolgreich"])
		{
			$this->linksNavigation["Übersicht"] = "/".BASIS_PFAD."/uebersichtseite";
			$this->linksNavigation["Abmelden"] = "/".BASIS_PFAD."/abmeldenseite";
		}
		else{
			$this->linksNavigation["Anmelden"] = "/".BASIS_PFAD."/anmeldenseite";
		}
		
		$liste_param = explode("/", $seitenauswahl);
		if(isset($liste_param[2])){
			$seitenauswahl = "/".$liste_param[1];
			$this->nr = htmlspecialchars($liste_param[2]);#nr_liste oder nr_user
			if(isset($liste_param[3])){ #nr_aufgabe oder pointer_ini nur wenn lister_param[4] vorhandent ist (LISTE / USER)
				if(isset($liste_param[4])){ #pointer_end
					if(isset($liste_param[5])){ #pointer_end (només quan estem en les Aufgaben
						$this->nr2 = htmlspecialchars($liste_param[3]);#nr_aufgabe
						$_POST['p_ini'] = htmlspecialchars($liste_param[4]);#pointer_ini
						$_POST['p_end'] = htmlspecialchars($liste_param[5]);#pointer_end
					}
					else{
						$_POST['p_ini'] = htmlspecialchars($liste_param[3]);#pointer_ini
						$_POST['p_end'] = htmlspecialchars($liste_param[4]);#pointer_end
					}
				}
				else{
					$this->nr2 = htmlspecialchars($liste_param[3]);#nr_aufgabe
				}
			}
			
		}
		
		switch($seitenauswahl)
		{
			case "/"                  	: $this->startseite();			break;
			case "/uebersichtseite"   	: $this->uebersicht();			break;
			case "/anmeldenseite"     	: $this->anmelden();			break;
			case "/abmeldenseite"     	: $this->abmelden();			break;
			case "/registrieren"      	: $this->registrieren();		break;
			case "/users_zeigen"      	: $this->users_zeigen();		break;
			case "/user_loeschen"    	: $this->user_loeschen();		break;
			case "/user_bearbeiten"   	: $this->user_bearbeiten();		break;
			case "/liste_anlegen"     	: $this->liste_anlegen();		break;
			case "/listen_zeigen"    	: $this->listen_zeigen();		break;
			case "/liste_loeschen"    	: $this->liste_loeschen();		break;
			case "/liste_bearbeiten"  	: $this->liste_bearbeiten();	break;
			case "/aufgabe_anlegen"  	: $this->aufgabe_anlegen();		break;
			case "/aufgaben_zeigen"		: $this->aufgaben_zeigen();		break;
			case "/aufgabe_loeschen"    : $this->aufgabe_loeschen();	break;
			case "/aufgabe_bearbeiten"  : $this->aufgabe_bearbeiten();	break;
			case "/aufgabe_erledigen"   : $this->aufgabe_erledigen();	break;
			case "/aufgabe_recover"     : $this->aufgabe_recover(); 	break;
			case "/impressumseite"    	: $this->impressum();			break;	
			case "/datenschutzseite"  	: $this->datenschutz();			break;					
			case "/kontaktseite"      	: $this->kontakt();				break;			
			
			default:
				$this->seiteninhalt = "404 - Seite nicht gefunden";
		}	

				
		echo new Webseite($this->links_erzeugen($this->linksNavigation),
						  $this->seiteninhalt, 
						  $this->links_erzeugen($this->linksFooter));# aqui fem echo de l obj. Webseite, en aquest moment es crirda el toString Methode de la classe Webeite!!!
	
	}	
	protected function startseite()
	{
		include("seiten/startseite.php");
	}
	protected function uebersicht()
	{
		if(isset($_SESSION["login_erfolgreich"] ) && $_SESSION["login_erfolgreich"])
		{
			include("seiten/tasklist_uebersicht.php");
		}
		/*else{
			include("seiten/startseite.php");
		}*/
	}		
	protected function anmelden()
	{
		$action = "anmelden";
		include("seiten/tasklist_user.php");
	}
	protected function abmelden()
	{
		$action = "abmelden";
		include("seiten/tasklist_user.php");
	}
	protected function registrieren()
	{
		$action = "registrieren";
		include("seiten/tasklist_user.php");
	}
	protected function users_zeigen()
	{
		$action = "zeigen";
		include("seiten/tasklist_user.php");
		
	}
	protected function user_loeschen()
	{
		$action = "loeschen";
		include("seiten/tasklist_user.php");
		
	}
	protected function user_bearbeiten()
	{
		$action = "bearbeiten";
		include("seiten/tasklist_user.php");
		
	}
	protected function liste_anlegen()
	{
		$action = "anlegen";
		include("seiten/tasklist_liste.php");
		
	}
	protected function listen_zeigen()
	{
		$action = "zeigen";
		include("seiten/tasklist_liste.php");
		
	}
	protected function liste_loeschen()
	{
		$action = "loeschen";
		include("seiten/tasklist_liste.php");
		
	}
	protected function liste_bearbeiten()
	{
		$action = "bearbeiten";
		include("seiten/tasklist_liste.php");
		
	}
	protected function aufgabe_anlegen()
	{
		$action = "anlegen";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function aufgaben_zeigen()
	{
		$action = "zeigen";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function aufgabe_loeschen()
	{
		$action = "loeschen";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function aufgabe_bearbeiten()
	{
		$action = "bearbeiten";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function aufgabe_erledigen()
	{
		$action = "erledigen";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function aufgabe_recover()
	{
		$action = "recover";
		include("seiten/tasklist_aufgabe.php");
		
	}
	protected function impressum()
	{
		include("seiten/impressum.php");
	}	
	protected function kontakt()
	{
		include("seiten/kontakt.php");
	}	
	protected function datenschutz()
	{
		include("seiten/datenschutz.php");
	}	
}
?>