<?php
/**
 * Class SingleSiteSite
 *
 * @package Clean_Expired_site_transients
 */

/**
 * Test case for single site.
 */
class SingleSiteSite extends WP_UnitTestCase {
	/**
	 * Test default behaviour.
	 */
	public function test_without_cleaning() {
		// Test setting of transient
		$set_key1 = set_site_transient( 'key1', 'value1', 5 );
		$this->assertTrue( $set_key1 );

		// Test retrieving of transient
		$get_key1 = get_site_transient( 'key1' );
		$this->assertEquals( $get_key1, 'value1' );

		// Sleep for half a minute
		sleep( 30 );

		// Test direct retrieval of transient value
		$raw_key1 = get_option( '_site_transient_key1' );
		$this->assertEquals( $raw_key1, 'value1' );

		// Test direct retrieval of transient timeout is less than current time`
		$raw_key1_timeout = get_option( '_site_transient_timeout_key1' );
		$this->assertTrue( is_int( $raw_key1_timeout ) );
		$this->assertLessThan( time(), $raw_key1_timeout );

		// Test retrieving of expired transient
		$fresh_get_key1 = get_site_transient( 'key1' );
		$this->assertFalse( $fresh_get_key1 );
	}

	/**
	 * Test when there is cleaning.
	 */
	public function test_with_cleaning() {
		// Test setting of transient
		$set_key2 = set_site_transient( 'key2', 'value2', 5 );
		$this->assertTrue( $set_key2 );

		// Test retrieving of transient
		$get_key2 = get_site_transient( 'key2' );
		$this->assertEquals( $get_key2, 'value2' );

		// Sleep for half a minute
		sleep( 30 );

		// Do cleaning
		Clean_Expired_Transients::clean();

		sleep( 2 );

		// Test direct retrieval of transient value
		$raw_key2 = get_option( '_site_transient_key2' );
		$this->assertFalse( $raw_key2 );

		// Test direct retrieval of transient timeout is less than current time`
		$raw_key2_timeout = get_option( '_site_transient_timeout_key2' );
		$this->assertFalse( $raw_key2_timeout );

		// Test retrieving of expired transient
		$fresh_get_key2 = get_site_transient( 'key2' );
		$this->assertFalse( $fresh_get_key2 );
	}
}
