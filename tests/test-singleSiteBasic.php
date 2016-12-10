<?php
/**
 * Class SingleSiteBasic
 *
 * @package Clean_Expired_Transients
 */

/**
 * Test case for single site.
 */
class SingleSiteBasic extends WP_UnitTestCase {
	/**
	 * Test default behaviour.
	 */
	public function test_without_cleaning() {
		// Test setting of transient
		$set_key1 = set_transient( 'key1', 'value1', 5 );
		$this->assertTrue( $set_key1 );

		// Test retrieving of transient
		$get_key1 = get_transient( 'key1' );
		$this->assertEquals( $get_key1, 'value1' );

		// Sleep for half a minute
		sleep( 30 );

		// Test direct retrieval of transient value
		$raw_key1 = get_option( '_transient_key1' );
		$this->assertEquals( $raw_key1, 'value1' );

		// Test direct retrieval of transient timeout is less than current time`
		$raw_key1_timeout = get_option( '_transient_timeout_key1' );
		$this->assertTrue( is_int( $raw_key1_timeout ) );
		$this->assertLessThan( time(), $raw_key1_timeout );

		// Test retrieving of expired transient
		$fresh_get_key1 = get_transient( 'key1' );
		$this->assertFalse( $fresh_get_key1 );
	}
}
