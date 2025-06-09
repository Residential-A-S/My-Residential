<?php
/**
 * In template files the constant 'user' is defined as the current user
 */


namespace templates\pages;

use interfaces\Page;

class PropertiesSingle implements Page{
	public function __construct(int|string $slug) {
        LOCALIZATION->setFile( "properties" );
	}

	public function getHead(): string {
        return '';
	}

	public function getBody(): string {
        return '';
	}

}