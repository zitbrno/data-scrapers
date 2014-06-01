<?php

namespace ZitBrno\Scrapers\MMB;

use \Katu\Types\TURL;

class ZapisyZeZastupitelstva extends \ZitBrno\Scrapers\Scraper {

	const BASE_URL = 'http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/';

	static private function __getYearURL($year) {
		return TURL::make(static::BASE_URL, array(
			'dokument' => 3,
			'rok'      => $year,
			'platnost' => 1,
		));
	}

	static function getYears() {
		$url = TURL::make(static::BASE_URL);
		$src = static::getURL($url);

		preg_match('#<select name="rok" onchange="this.form.submit\(this\)">(.*)</select>#s', $src, $matches);
		preg_match_all('#<option value="([0-9]{4})" (selected="selected")?>.*</option>#', $matches[1], $matches);

		$res = array();
		foreach ($matches[1] as $year) {
			$res[] = array(
				'year' => (int) $year,
				'url'  => static::__getYearURL($year),
			);
		}

		return $res;
	}

	static function getByYear($year) {
		$url = static::__getYearURL($year);
		$src = static::getURL($url);

		preg_match_all('#<tr>.*<td class="textseznam"><a href="http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/\?cislo=(?<id>[0-9]+)&amp;rok=([0-9]{4})&amp;dokument=[0-9]+&amp;platnost=[01]" title="Zápis ze ZMB">Zápis ze ZMB&nbsp;(?<ref>(Z[0-9]+)/([0-9]+))</a></td>.*<td class="textseznam">(?<date>.+)</td>.*</tr>#sU', $src, $matches, PREG_SET_ORDER);

		$res = array();
		foreach ($matches as $match) {
			$res[] = array(
				'id'   => $match['id'],
				'ref'  => $match['ref'],
				'date' => \Katu\Utils\DateTime::get($match['date']),
				'url'  => TURL::make(static::BASE_URL, array('cislo' => $match['id'])),
			);
		}

		return $res;
	}

}
