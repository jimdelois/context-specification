<?php

namespace ContextSpecification\TestDummies;

class BecauseExceptionNotSetConcern extends \ContextSpecification\Framework\StaticConcern {

	use Traits\DummyConcernTrait;

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