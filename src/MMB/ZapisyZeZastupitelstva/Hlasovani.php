<?php

namespace ZitBrno\Scrapers\MMB\ZapisyZeZastupitelstva;

use \Katu\Types\TURL;

class Hlasovani extends \ZitBrno\Scrapers\Scraper {

	public $url;

	public function __construct($url) {
		$this->url = TURL::make($url);
	}

	static function scrape($url, $timeout = NULL) {
		return iconv('Windows-1250', 'UTF-8', parent::scrape($url, $timeout));
	}

	public function getHlasy() {
		$src = static::scrape($this->url);

		preg_match_all('#<td width="20%" align="right" nowrap style="font-size:8pt;font-family:Arial, Arial CE, Courier">(?<person>.+)\s+(?<party>.+): </td><td width="10%" align="left" nowrap style="font-size:8pt;font-family:Arial, Arial CE, Courier">(?<vote>.*)</td>#U', $src, $matches, PREG_SET_ORDER);

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
