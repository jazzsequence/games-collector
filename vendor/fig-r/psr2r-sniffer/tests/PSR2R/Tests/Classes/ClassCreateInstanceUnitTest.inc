<?php

namespace PSR2R\Tests\Classes;

class ClassCreateInstanceUnitTest1 {
}
class ClassCreateInstanceUnitTest2 {
	public function one() {
		$instance = new ClassCreateInstanceUnitTest1;
	}
	public function two() {
		$instance = new ClassCreateInstanceUnitTest1();
	}
}
