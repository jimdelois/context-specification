<?php

namespace Tests\ContextSpecification\Framework\TestDummies;

class BecauseExceptionNotSetConcern extends \ContextSpecification\Framework\StaticConcern {


	protected function context( ) {
		$this->becauseWillThrowException( false );
	}

	protected function because( ) {
		null;
	}

	public function testReleasing( ) {
		$this->releaseException( );
	}

	public function testCapturing( ) {
		$this->captureException( );
	}
}

?>