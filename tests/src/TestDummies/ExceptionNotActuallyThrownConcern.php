<?php

namespace ContextSpecification\TestDummies;

class ExceptionNotActuallyThrownConcern extends \ContextSpecification\Framework\StaticConcern {

	protected function context( ) {
		$this->becauseWillThrowException( );
	}

	protected function because( ) {
		null;
	}

	public function getCaptured( ) {
		return $this->captureException( );
	}

	public function doRelease( ) {
		return $this->releaseException( );
	}
}

?>