<?php
/**
 * InterWiki Links extension for BlueSpice MediaWiki
 *
 * Administration interface for adding, editing and deleting interwiki links
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit https://bluespice.com
 *
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @author     Leonid Verhovskij <verhovskij@hallowelt.com>
 * @author     Sebastian Ulbricht <sebastian.ulbricht@dragon-design.hk>
 * @package    BlueSpice_Extensions
 * @subpackage InterWikiLinks
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */
namespace BlueSpice\InterWikiLinks;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

class Extension extends \BlueSpice\Extension {

	/**
	 *
	 * @param string $iwPrefix
	 */
	public static function purgeTitles( $iwPrefix ) {
		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancer()
			->getConnection( DB_REPLICA );
		$res = $dbr->select(
			'iwlinks',
			[ 'iwl_from', 'iwl_prefix' ],
			[ 'iwl_prefix' => $iwPrefix ],
			__METHOD__
		);

		foreach ( $res as $row ) {
			$title = Title::newFromID( $row->iwl_from );
			if ( $title instanceof Title == false ) {
				continue;
			}
			$title->invalidateCache();
		}
	}
}
