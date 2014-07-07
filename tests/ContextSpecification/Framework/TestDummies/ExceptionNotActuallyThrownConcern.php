<?php

namespace Tests\ContextSpecification\Framework\TestDummies;

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
}

?>