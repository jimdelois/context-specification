<?php

namespace ContextSpecification\TestDummies\Traits;

trait DummyConcernTrait {

	/**
	* This override is here solely to avoid the Exception thrown when the PHPUnit base library
	*  finds that output buffers opened are not set to the amount that it, itself, has opened.
	*  This "run" method is called as the actual Method-Under-Test, and PHPUnit, via the
	*  private method `PHPUnit_Framework_TestCase::stopOutputBuffering`, will actually close ALL
	*  buffers instead of just the amount of the difference...  This quick patch opens a buffer
	*  back up so that the number closed will be equal, and no exception is thrown.
	*
	* {@inheritdoc}
	*/
	public function run( \PHPUnit_Framework_TestResult $result = null ) {

	if ( ! $this instanceof \PHPUnit_Framework_TestCase ) {
		throw new \RuntimeException( 'Invalid Trait Import!' );
	}

	$res = parent::run( $result );
	ob_start( );
	return $res;

	}

}

?>