<?php

declare(strict_types=1);

namespace Tak\Liveproto\Tl;

use Composer\Composer;

use Composer\IO\IOInterface;

use Composer\Plugin\PluginInterface;

use Composer\Script\Event;

use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface {
	private Composer $composer;
	private IOInterface $io;

	public function activate(Composer $composer,IOInterface $io) : void {
		$this->composer = $composer;
		$this->io = $io;
		$util = new Util($composer);
		$path = $util->findInstallPath('tak/liveproto');
		if(is_null($path) === false):
			$tlpath = realpath($path);
			var_dump($tlpath);
			# putenv('TLPATH='.$path);
			# $_ENV['TLPATH'] = $path;
		endif;
	}
	public function deactivate(Composer $composer,IOInterface $io) : void {
	}
	public function uninstall(Composer $composer,IOInterface $io) : void {
	}
	public static function getSubscribedEvents() : array {
		return array(
			ScriptEvents::POST_AUTOLOAD_DUMP=>'onPostAutoloadDump',
			ScriptEvents::POST_INSTALL_CMD=>'onPostInstall',
			ScriptEvents::POST_UPDATE_CMD =>'onPostUpdate',
		);
	}
	public function onPostAutoloadDump(Event $event) : void {
		$this->io->write('<info>dump...</info>');
		
	}
	public function onPostInstall(Event $event) : void {
		$this->io->write('TLGenerator : post-install hook');
		$this->onPostAutoloadDump($event);
	}
	public function onPostUpdate(Event $event) : void {
		$this->io->write('TLGenerator : post-update hook');
		$this->onPostAutoloadDump($event);
	}
}

?>
