<?php

namespace PSR2R\Tests\ControlStructures;

/**
 * Class ControlStructureSpacingUnitTest
 *
 * @author  Ed Barnard
 * @license MIT
 * @package PSR2R\Tests\ControlStructures
 */
class ControlStructureSpacingUnitTestInc {
	public function one() {
		$a = 0;
		if($a){
			$a = 1;
		}
		while($a){ $a=0;
		}
		foreach([1]as $b){$a=$b;}if($b)$a=0;
		if ($something) {
		}
		foreach ( $this as $that ) {
		}
		while ( true ) {
			for ( $i = 0; $i < 10; $i++ ) {
			}
			if ($something ) {
			}

			foreach ($this as $that ) {
				do {
				} while (true );

			}
		}

		if ($defaultPageDesign === 0
			&& $defaultCascade === true
			&& $defaultChildDesign === 0
		) {
			$settingsUpdated = false;
		}

		foreach ( $blah as $var ) {
			if ( $blah) {
			}
		}
	}
}
