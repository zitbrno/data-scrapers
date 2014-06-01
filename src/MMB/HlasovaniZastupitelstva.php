<?php

namespace ZitBrno\Scrapers\MMB;

class HlasovaniZastupitelstva extends \ZitBrno\Scrapers\Scraper {

	static function scrape() {
		var_dump(static::getURL('http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/'));
	}

}
