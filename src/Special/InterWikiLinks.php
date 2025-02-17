<?php

namespace BlueSpice\InterWikiLinks\Special;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;

class InterWikiLinks extends SpecialPage {

	public function __construct() {
		parent::__construct( 'InterWikiLinks', 'interwikilinks-viewspecialpage' );
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $subPage ) {
		parent::execute( $subPage );

		$out = $this->getOutput();
		$out->addModules( [ 'ext.bluespice.interwikilinks.manager' ] );
		$out->addHTML( Html::element( 'div', [
			'id' => 'bs-interwikilinks-manager',
			'data-can-edit' => $this->getUser()->isAllowed( 'wikiadmin' ) ? 1 : 0
		] ) );
	}
}
