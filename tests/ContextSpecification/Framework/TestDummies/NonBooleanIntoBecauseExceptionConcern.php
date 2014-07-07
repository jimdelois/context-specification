<?php

namespace Tests\ContextSpecification\Framework\TestDummies;

class NonBooleanIntoBecauseExceptionConcern extends \ContextSpecification\Framework\StaticConcern {


	protected function context( ) {
		$this->becauseWillThrowException( 'This is not a boolean value' );
	}

	protected function because( ) {
		null;
	}


}

?>