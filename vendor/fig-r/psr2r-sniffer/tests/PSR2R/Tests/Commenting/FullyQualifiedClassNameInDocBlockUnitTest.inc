<?php

namespace PSR2R\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PSR2R\Tools\AbstractSniff;

/**
 * Make sure all class names in doc blocks are FQCN (Fully Qualified Class Name).
 *
 * @author Mark Scherer
 * @license MIT
 */
class FullyQualifiedClassNameInDocBlockInc {

	/**
	 * @param PHP_CodeSniffer\Files\File $phpCsFile
	 * @param int $stackPointer
	 *
	 * @return void
	 */
	protected function processInlineComments(File $phpCsFile, $stackPointer) {
	}

	/**
	 * @param File $phpCsFile
	 * @param int $stackPointer
	 *
	 * @return void
	 */
	protected function processDocBlockComments(File $phpCsFile, $stackPointer) {
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpCsFile
	 * @param int $classNameIndex
	 * @param array $classNames
	 *
	 * @return array
	 */
	protected function generateClassNameMap(File $phpCsFile, $classNameIndex, array &$classNames) {
	}
}
