<?php

namespace ZitBrno\Scrapers\MMB;

use \Katu\Types\TURL;

class ZapisyZeZastupitelstva extends \ZitBrno\Scrapers\Scraper {

	const BASE_URL = 'http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/';

	static function getRoky() {
		$src = static::scrape(TURL::make(static::BASE_URL));

		preg_match('#<select name="rok" onchange="this.form.submit\(this\)">(.*)</select>#s', $src, $matches);
		preg_match_all('#<option value="([0-9]{4})" (selected="selected")?>.*</option>#', $matches[1], $matches);

		$res = array();
		foreach ($matches[1] as $rok) {
			$res[] = new ZapisyZeZastupitelstva\Rok($rok);
		}

		return $res;
	}

}
