<?php

class undefinedGc_game_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'undefinedGc_game') );
	}

	function test_class_access() {
		$this->assertTrue( ()->gc-game instanceof undefinedGc_game );
	}

  function test_cpt_exists() {
    $this->assertTrue( post_type_exists( 'games-collector-gc-game' ) );
  }
}
