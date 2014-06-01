<?php

namespace ZitBrno\Scrapers\MMB\ZapisyZeZastupitelstva;

use \Katu\Types\TURL;

class Bod extends \ZitBrno\Scrapers\Scraper {

	public $cislo;
	public $nazev;
	public $text;

	public function __construct($cislo, $nazev, $text) {
		$this->cislo = (int)    ($cislo);
		$this->nazev = (string) (trim($nazev));
		$this->text  = (string) (trim($text));
	}

}
