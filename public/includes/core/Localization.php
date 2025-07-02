<?php
namespace core;

class Localization{
	public string $language {
        get {
            return $this->language;
        }
    }
    private array $translations;

	public function __construct() {
		if ( isset( $_GET["lang"] ) ) {
			$this->language = $_GET["lang"];
		} else if ( isset( $_POST["lang"] ) ) {
			$this->language = $_POST["lang"];
		} else {
			$this->language = 'da';
		}
	}

    public function setFile( string $file ): void {
		$this->translations = require_once App::$root . "/localization/" . $this->language . "/" . $file . ".php";
	}

	public function translate( string $string ): string {
		if ( isset( $this->translations[ $string ] ) ) {
			return $this->translations[ $string ];
		}

		return $string;
	}

}