<?php
/**
 * Classes en rapport avec les Dates
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW\CKernel;

/**
 * Classe de gestion des dates
 * Le format de la date est aaaa-mm-jj hh:mm:ss+OO:OO
 * @package BFW
 */
class Date extends Kernel implements \BFW\IKernel\IDate
{
	/**
	 * @var : L'instance de la classe php DateTime
	 */
	private $DateTime;
	
	/**
	 * @var $date : La date au format string
	 */
	private $date = '';
	
	/**
	 * @var $annee : L'année de la date
	 */
	private $annee = 0;
	
	/**
	 * @var $mois : Le mois de la date
	 */
	private $mois = 0;
	
	/**
	 * @var $jour : Le jour de la date
	 */
	private $jour = 0;
	
	/**
	 * @var $heure : L'heure de la date
	 */
	private $heure = 0;
	
	/**
	 * @var $minute : Les minutes de la date
	 */
	private $minute = 0;
	
	/**
	 * @var $seconde : Les secondes de la date
	 */
	private $seconde = 0;
	
	/**
	 * @var $zone : Le timezone à utiliser
	 */
	private $zone;
	
	/**
	 * Fonction magique, permet de lire les attributs directement
	 * @param string $name : Le nom de l'attribut auquel on veux accéder.
	 */
	public function __get($name)
	{
		return $this->$name;
	}
	
	/**
	 * Constructeur
	 * La date dans un format précis (aaaa-mm-jj hh:mm:ss+OO:OO)
	 * S'il n'y a pas ":00" à la fin, alors c'est géré.
	 * @param string $date [opt] : La date sur laquelle travailler. Si pas indiqué, il s'agit de la date actuelle.
	 */
	public function __construct($date="now")
	{
		if($date != "now")
		{
			$len = strlen($date);
			$len -= 3;
			if($date[$len] == '+')
			{
				$date .= ':00';
			}
		}
		
		$this->date = $date;
		$this->DateTime = new \DateTime($date);
		
		if($date == "now")
		{
			$this->date = $this->DateTime->format('Y-m-d H:i:sO');
		}
		
		$this->MAJ_Attributes();
	}
	
	/**
	 * Initialise les attributs avec leurs valeurs respectives. La méthode est appelé dans le constructeur
	 */
	final private function MAJ_Attributes()
	{
		$this->annee = $this->DateTime->format('Y');
		$this->mois = $this->DateTime->format('m');
		$this->jour = $this->DateTime->format('d');
		$this->heure = $this->DateTime->format('H');
		$this->minute = $this->DateTime->format('i');
		$this->seconde = $this->DateTime->format('s');
		$this->zone = $this->DateTime->format('P');
		
		$this->date = $this->DateTime->format('Y-m-d H:i:sO');
	}
	
	/**
	 * Modifie une données de la date
	 * @param string $cond : La partie à modifier : year, mouth, day, jour, minute, second
	 * @return bool : True la si modif à réussi, fales si erreur
	 */
	public function modify($cond)
	{
		$datetime = $this->DateTime;
		$dateOri = $datetime->format('Y-m-d H:i:s');
		$mod = @$datetime->modify($cond);
		$dateMod = $datetime->format('Y-m-d H:i:s');
		
		if($dateOri == $dateMod || $mod == false)
		{
			$match = array();
			preg_match('#(\+|\-)([0-9]+) ([a-z]+)#i', $cond, $match);
			$match[3] = strtolower($match[3]);
			
			if($match[3] == 'an' || $match[3] == 'ans')
			{
				$real = 'year';
			}
			elseif($match[3] == 'mois')
			{
				$real = 'month';
			}
			elseif($match[3] == 'jour' || $match[3] == 'jours')
			{
				$real = 'day';
			}
			elseif($match[3] == 'heure' || $match[3] == 'heures')
			{
				$real = 'hour';
			}
			elseif($match[3] == 'minutes')
			{
				$real = 'minute';
			}
			elseif($match[3] == 'seconde' || $match[3] == 'secondes')
			{
				$real = 'second';
			}
			
			$mod2 = @$this->DateTime->modify($match[1].$match[2].' '.$real);
			$dateMod2 = $datetime->format('Y-m-d H:i:s');
			
			if($dateOri == $dateMod2 || $mod2 == false)
			{
				return false;
			}
			else
			{
				$this->MAJ_Attributes();
				return true;
			}
		}
		else
		{
			$this->MAJ_Attributes(); return true;
		}
	}
	
	/**
	 * Renvoi au format pour SQL (postgresql) via un array
	 * @param bool $decoupe [opt=false] : Indique si on veux retourner un string ayant tout, 
	 * 										ou un array ayant la date et l'heure séparé
	 * @return string/array : Si string : aaaa-mm-jj hh:mm:ss
	 * 						  Si array : [0]=>partie date (aaaa-mm-jj), [1]=>partie heure (hh:mm:ss)
	 */
	public function getSql($decoupe=false)
	{
		$dateSql = new \DateTime($this->date, new \DateTimeZone(self::ZONE_DEFAULT));
		$date = $dateSql->format('Y-m-d');
		$heure = $dateSql->format('H:i:s');
		//$heure = substr($heure, 0, -3);
		
		if($decoupe)
		{
			return array($date, $heure);
		}
		else
		{
			return $date.' '.$heure;
		}
	}
	
	/**
	 * Modifie le timezone
	 * @param string le nouveau time zone
	 */
	public function setZone($NewZone)
	{
		$this->DateTime->setTimezone(new \DateTimeZone($NewZone));
	}
	
	/**
	 * Liste tous les timezone qui existe
	 * @return array : La liste des timezone possible
	 */
	public function lst_TimeZone()
	{
		$TimeZone = $this->DateTime->getTimezone();
		return $TimeZone->listIdentifiers();
	}
	
	/**
	 * Liste les continents possible pour les timezones
	 * @return array : La liste des continents
	 */
	public function lst_TimeZoneContinent()
	{
		$lst_continent = array(
			'africa', 
			'america', 
			'antartica', 
			'arctic', 
			'asia', 
			'atlantic', 
			'australia', 
			'europe', 
			'indian', 
			'pacific'
		);
		
		return $lst_continent;
	}
	
	/**
	 * Liste des pays possible pour un continent donné
	 * @param string : Le continent dans lequel on veux la liste des pays
	 * @return array : La liste des pays pour le continent donné
	 */
	public function lst_TimeZonePays($continent)
	{
		$lst_all = $this->lst_TimeZone();
		$return = array();
		
		$pos = false;
		foreach($lst_all as $val)
		{
			$pos = strpos($val, $continent);
			if($pos !== false)
			{
				$return[] = $val;
			}
		}
		
		return $return;
	}
	
	/**
	 * Transforme la date en un format plus facilement lisible.
	 * @param bool $tout : Affiche la date en entier (true) ou non (false). Par défault "true"
	 * @param bool $minus : Affiche la date en minuscule (true) ou non (false). Par défault "false"
	 */
	public function aff_simple($tout=1, $minus=false)
	{
		/*
			Paramètre en entrée
			$tout :
				1 : On affiche la date en entier (jour et heure)
				0 : On affiche que le jour
			$minus : 
				1 : On affiche le texte en minuscule (hier, le)
				0 : On affiche le texte en normal (Hier, Le)
			
			-------------------------------------
			Paramètre en sortie
			Plusieurs possibilitées
			
			$tout == 1
				Il y 1s
				Il y 1min
				Il y 1h
				Hier à 00:00
				Le 00/00 à 00:00
				Le 00/00/0000 à 00:00 (si l'année n'est pas la même)
			
			$tout == 0
				Il y 1s
				Il y 1min
				Il y 1h
				Hier
				Le 00/00
				Le 00/00/0000 (si l'année n'est pas la même)
			
			Ou "Maintenant" (qu'importe la valeur de $tout)
		*/
		
		//Découpage de la date donnée dans l'instance de la classe
		$annee = $this->annee;
		$mois = $this->mois;
		$jour = $this->jour;
		$heure = $this->heure;
		$minute = $this->minute;
		
		//La date actuelle
		$time = new Date();
		$timeDT = $time->DateTime;
		
		
		/*
		Si c'est à l'instant même
			Afficher "Maintenant"
		Si c'est le même jour OU (c'est jour-1 ET différence d'heure < 2h)
			Afficher "il y a xxh"
		Si c'est jour-1 ET différence d'heure > 2h
			Afficher "Hier"
		Sinon
			Afficher au format date
		*/
		
		$diff = $this->DateTime->diff($timeDT);
		$diffAnnee = $diff->format('%Y');
		$diffMois = $diff->format('%M');
		$diffJour = $diff->format('%D');
		$diffHeure = $diff->format('%H');
		$diffMinute = $diff->format('%I');
		$diffSeconde = $diff->format('%S');
		
		if($diffAnnee == 0 && $diffMois == 0 && $diffJour == 0 && $diffHeure == 0 && $diffMinute == 0 && $diffSeconde == 0)
		{
			$aff_date = 'Maintenant';
			$aff_heure = '';
		}
		elseif($diffAnnee == 0 && $diffMois == 0 && $diffJour == 0 && $diffHeure <= 2)
		{
			$aff_date = 'Il y a '; //On commence par déclaré le début de l'affichage
			
			if($diffJour > 1) //Dans le cas de 23h -> $heure_diff = -1
			{
				$h = 24+$diffHeure; //24 + (-1)
				$aff_date .= $h.'h';
			}
			else
			{
				if($diffHeure > 0)
				{
					$aff_date .= $diffHeure.'h';
				}
				elseif($diffHeure <= 0 && $diffMinute > 0)
				{
					$aff_date .= $diffMinute.'min';
				}
				else
				{
					$aff_date .= $diffSeconde.'s';
				}
			}
			
			$aff_heure = ''; #Partie prévu pour l'affichage
		}
		elseif($diffAnnee == 0 && $diffMois == 0 && ($diffJour == 0 && $diffHeure > 2) || ($diffJour == 1 && $diffHeure == 0))
		{
			//C'était hier
			$aff_date = 'Hier'; #On affiche donc en première partie "hier"
			$aff_heure = ' à '.$heure.':'.$minute; #et en seconde partie, l'heure et les minutes
		}
		else
		{
			//Sinon et bien c'était il y a plus de 48h, et on affiche la date au format habituel
			$aff_date = 'Le '.$jour.'/'.$mois; //D'abord le jour et le mois
			$aff_heure = ' à '.$heure.':'.$minute; //Et ensuite l'heure et les minutes
			
			//Et si l'année n'est pas la meme que l'actuel, alors on rajoute l'année à la fin de la première partie l'année
			if($diffAnnee != 0)
			{
				$aff_date .= '/'.$annee;
			}
		}
		
		//Maintenant on arrive à la partie qui dit, affiche moi toutes les infos ou seulement une partie
		if($tout == 1) //Si on veut tout afficher (la date et l'heure)
		{
			$aff = $aff_date.$aff_heure;
		}
		else //Ou si on ne veut afficher que la date
		{
			$aff = $aff_date;
		}
		
		//Met la première lettre en minuscule dans le cas où l'ont veuille du minuscule
		if($minus == true)
		{
			$aff = mb_strtolower($aff);
		}
		
		return $aff; //Et on retour la date parser :D
	}
}
