<?php

namespace ZitBrno\Scrapers\MMB\ZapisyZeZastupitelstva;

use \Katu\Types\TURL;

class Rok extends \ZitBrno\Scrapers\Scraper {

	const BASE_URL = 'http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/';

	public $rok;

	public function __construct($rok) {
		$this->rok = (int) $rok;
	}

	public function getUrl() {
		return TURL::make(static::BASE_URL, array(
			'dokument' => 3,
			'rok'      => $this->rok,
			'platnost' => 1,
		));
	}

	public function getZapisy() {
		$src = static::scrape($this->getUrl());

		preg_match_all('#<tr>.*<td class="textseznam"><a href="http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/\?cislo=(?<id>[0-9]+)&amp;rok=([0-9]{4})&amp;dokument=[0-9]+&amp;platnost=[01]" title="Zápis ze ZMB">Zápis ze ZMB&nbsp;(?<ref>(Z[0-9]+)/([0-9]+))</a></td>.*<td class="textseznam">(?<date>.+)</td>.*</tr>#sU', $src, $matches, PREG_SET_ORDER);

		$res = array();
		foreach ($matches as $match) {
			$res[] = new Zapis($match['id']);
		}

		return $res;
	}

}
