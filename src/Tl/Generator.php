<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl;

abstract class Generator {
	static public function start(mixed ...$tls) : void {
		var_dump($tls);
	}
}

?>
