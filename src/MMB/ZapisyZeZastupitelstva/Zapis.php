<?php

namespace ZitBrno\Scrapers\MMB\ZapisyZeZastupitelstva;

use \Katu\Types\TURL;

class Zapis extends \ZitBrno\Scrapers\Scraper {

	const BASE_URL = 'http://www.brno.cz/sprava-mesta/dokumenty-mesta/zapisy-ze-zastupitelstva-mesta-brna/';

	public $id;

	public function __construct($id) {
		$this->id = (int) $id;
	}

	public function getURL() {
		return TURL::make(static::BASE_URL, array(
			'cislo' => $this->id,
		));
	}

	public function getDocumentURL() {
		$src = static::scrape($this->getURL());

		preg_match('#<iframe src="(http://www2.brno.cz/dokumenty/soubor.php\?cislo=([0-9]+)&typ=[0-9]+)" width="800" height="600" align="center" frameborder="0" class="textdokumenty">#', $src, $match);

		return TURL::make($match[1]);
	}

	public function getSeznamHlasovani() {
		$src = static::scrape($this->getDocumentURL());

		preg_match_all('#<A HREF="(.+)"><B><I><U><FONT COLOR="\#0000ff"><SPAN LANG="CS">protokol o hlasování zde</B></I></U></FONT></SPAN></A>#', $src, $matches, PREG_SET_ORDER);


		$res = array();
		foreach ($matches as $match) {
			$res[] = new Hlasovani($match[1]);
		}

		return $res;
	}

}
