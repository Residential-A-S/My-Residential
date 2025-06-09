<?php
/**
 * In template files the constant 'user' is defined as the current user
 */


namespace templates\pages;

use function\SVGHandler;
use model\WidgetController;
use Page;
use Popup;

class Properties implements Page{
	public function __construct() {
        LOCALIZATION->setFile( "properties" );
	}

	public function getHead(): string {
		ob_start();
		?>
        <title><?= _e( "Title" ) ?></title>
        <link href="/public/assets/assets/style/default.css" rel="stylesheet">
        <link href="../../assets/style/bookableSingle.css" rel="stylesheet">
		<?php
		return ob_get_clean();
	}

	public function getBody(): string {
		ob_start();
		echo WidgetController::sideNavbar();
		?>
        <div class="content-topNavbar">
			<?= WidgetController::topNavbar() ?>
            <div id="content">
                <div class="contentHeader">
                    <h1><?= _e( "Your properties" ) ?></h1>
                    <button id="createProperty"><?= SVGHandler::getSVG( "add" ) ?><?= _e( "Add new property" ) ?></button>
                </div>


            </div>
        </div>
		<?= new Popup( "createProperty" ) ?>
        <script src="/public/assets/assets/script/app.mjs" type="module"></script>
        <script src="../../assets/script/bookableSingle.mjs" type="module"></script>
		<?php
		return ob_get_clean();
	}

}