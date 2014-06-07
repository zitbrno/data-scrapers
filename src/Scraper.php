<?php

namespace ZitBrno\Scrapers;

class Scraper {

	static function getCacheDirectory() {
		return \Katu\Utils\FS::joinPaths(TMP_PATH, implode('/', array_map('strtolower', array_slice(explode('\\', get_called_class()), 1))));
	}

	static function scrape($url, $timeout = NULL) {
		$cacheOptions = array(
			'dir' => static::getCacheDirectory(),
		);

		return \Katu\Utils\Cache::getUrl($url, $timeout, $cacheOptions);
	}

}
