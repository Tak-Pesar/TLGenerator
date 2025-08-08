<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl;

abstract class Generator {
	static public function start(mixed ...$tls) : void {
		print('start : ');
		var_dump($tls);
	}
	static public function fuck(mixed ...$tls) : void {
		print('fuck : ');
		var_dump($tls);
	}
}

?>
