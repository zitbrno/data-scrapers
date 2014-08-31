<?php

namespace ZitBrno\Scrapers\MMB\ZapisyZeZastupitelstva;

use \Katu\Types\TURL;

class Hlasovani extends \ZitBrno\Scrapers\Scraper {

	public $url;

	public function __construct($url) {
		$this->url = TURL::make($url);
	}

	static function scrape($url, $timeout = NULL) {
		return iconv('Windows-1250', 'UTF-8//TRANSLIT//IGNORE', parent::scrape($url, $timeout));
	}

	public function getZapisCislo() {
		$src = static::scrape($this->url);

		if (preg_match('#<p class="header">Zastupitelstvo města Brna č. (.+)<br/>#', $src, $match)) {
			return $match[1];
		} else {
			#echo $this->url;
		}
	}

	public function getCislo() {
		$src = static::scrape($this->url);

		if (preg_match('#<p class="header">.*Hlasování č. ([0-9]+)<br>.*</p>#sU', $src, $match)) {
			return (int) $match[1];
		}
	}

	public function getBodCislo() {
		$src = static::scrape($this->url);

		$nazev = $this->getNazev();

		if (preg_match('#^Bod č. ([0-9]+)#i', $nazev, $match)) {
			return array((int) $match[1]);
		} elseif (preg_match('#^Body č. ([0-9]+) a ([0-9]+)#i', $nazev, $match)) {
			return array((int) $match[1], (int) $match[2]);
		} elseif (preg_match('#^Body č. ([0-9]+)-([0-9]+)#i', $nazev, $match)) {
			return range($match[1], $match[2]);
		} elseif (preg_match('#^hlasování o zařazení bodu č. ([0-9]+)$#i', trim($nazev), $match)) {
			return array((int) $match[1]);
		} elseif (preg_match('#^hlasování o zařazení bodu č. ([0-9]+) - opakované hlasování$#i', trim($nazev), $match)) {
			return array((int) $match[1]);
		} elseif (preg_match('#^hlasování o stažení bodu č. ([0-9]+)$#i', trim($nazev), $match)) {
			return array((int) $match[1]);
		} elseif (preg_match('#^body č. ([0-9]+)#i', $nazev, $match)) {
			return array((int) $match[1]);
		} elseif (preg_match('#^opětovné zařazení bodu č. ([0-9]+)#i', $nazev, $match)) {
			return array((int) $match[1]);
		}

		return array();
	}

	public function getNazev() {
		$src = static::scrape($this->url);

		if (preg_match('#<center><b>(.+)</b></center>#sU', $src, $match)) {
			return trim($match[1]);
		}
	}

	public function getDatum() {
		$src = static::scrape($this->url);

		if (preg_match('#<p class="header">Zastupitelstvo města Brna č. (.+)<br/> (.+)</p>#', $src, $match)) {
			return \Katu\Utils\DateTime::get($match[2]);
		}
	}

	public function getHlasy() {
		$src = static::scrape($this->url);

		preg_match_all('#<td width="20%" align="right" nowrap style="font-size:8pt;font-family:Arial, Arial CE, Courier">(?<person>.{1,20})\s+(?<party>.{1,15}): </td><td width="10%" align="left" nowrap style="font-size:8pt;font-family:Arial, Arial CE, Courier">(?<vote>.*)</td>#sU', $src, $matches, PREG_SET_ORDER);

		$res = array();
		foreach ($matches as $match) {
			$res[] = array(
				'person' => trim($match['person']),
				'party'  => trim($match['party']),
				'vote'   => trim($match['vote']),
			);
		}

		return $res;
	}

}
