<?php
class AutoloaderJules{

	/**
	 * Summary of registerApp
	 * Regista o auto loader do projeto que deve ser deste a pasta RAIZ
	 */
	public function registerProject($dir){
		spl_autoload_register(function($class)use($dir){
			require str_replace("\\","/",$dir."$class.php");
		});
		return $this;
	}
}
header('Content-Type: application/json');
$julesAutoloader = new AutoloaderJules();
$julesAutoloader->registerProject("C:/xampp/htdocs/gatewayAuht/webservice/");






