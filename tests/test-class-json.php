<?php
/**
 * The JSON manager handles json output to admin and frontend.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests;

use UnitTestCase;

/**
 * TestJsonManager class.
 */
class TestJsonManager extends UnitTestCase {

	private $manager;

	public function setUp() {
		parent::setUp();
		$this->manager = new \MyThemeShop\Json_Manager;
	}

	/**
	 * Add something to JSON object.
	 */
	public function test_add() {

		// Empty.
		$this->manager->add( '', 'shakeeb', 'mythemeshop' );

		// Key don't exists.
		$this->manager->add( 'test', 'value', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'test' => 'value' ] ]
		);

		// Key exists and not array overwrite.
		$this->manager->add( 'test', 'changed', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'test' => 'changed' ] ]
		);

		// Key exists and array merge.
		$this->manager->add( 'name', [ 'first' => 'shakeeb' ], 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[
				'mythemeshop' => [
					'test' => 'changed',
					'name' => [
						'first' => 'shakeeb',
					],
				],
			]
		);

		$this->manager->add( 'name', [ 'last' => 'ahmed' ], 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[
				'mythemeshop' => [
					'test' => 'changed',
					'name' => [
						'first' => 'shakeeb',
						'last'  => 'ahmed',
					],
				],
			]
		);
	}

	/**
	 * Remove something from JSON object.
	 */
	public function test_remove() {
		$this->manager->add( 'name', 'shakeeb', 'mythemeshop' );
		$this->manager->remove( 'test', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'name' => 'shakeeb' ] ]
		);
	}

	/**
	 * Print data.
	 */
	public function test_output() {
		$this->manager->add( 'name', 'shakeeb', 'mythemeshop' );
		$script  = '';
		$script .= "<script type='text/javascript'>\n";
		$script .= "/* <![CDATA[ */\n";
		$script .= "var mythemeshop = {\"name\":\"shakeeb\"};\n\n";
		$script .= "/* ]]> */\n";
		$script .= "</script>\n";

		$this->expectOutputString( $script );
		$this->manager->output();
	}
}
