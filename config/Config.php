<?php

	namespace Config;

	class Config {

		/* get environmental variable from .env file */
		public static function get($var) {

			if (file_exists(__DIR__ .'/../.env')) {

				$file = file_get_contents(__DIR__ . '/../.env');
				$lines = explode("\n", $file);

				foreach($lines as $line => $l) {

					if ($l[0] == "#")	
						continue;

					$tokens = explode("=", $l);

					if (count($tokens) == 2)	
						if ($tokens[0] == $var)
							return $tokens[1];

				}
			}

			return null;

		}
	}