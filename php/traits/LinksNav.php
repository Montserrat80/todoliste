<?php
namespace php\traits;

trait LinksNav
{	
	public function url_erzeugen($beschriftung, $seite)
	{
		return "<li><a href='$seite'>$beschriftung</a></li>\n"; 
	}

	public function links_erzeugen($array_links)
	{
		$string = "";
		foreach($array_links as $beschriftung => $seite)
		{
			$string .= $this->url_erzeugen($beschriftung, $seite);
		}
		return $string;
	}
}	
?>