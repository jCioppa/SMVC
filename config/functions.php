<?php
	
	if (!function_exists('dd')) {

		function dd($var)	 {
			print_r($var);
			die();
		}
	}