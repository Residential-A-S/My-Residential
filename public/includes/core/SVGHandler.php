<?php

namespace core;

class SVGHandler{
	public static function getSVG( string $name ): string {
		return file_get_contents( "assets/icon/$name.svg" );
	}
}