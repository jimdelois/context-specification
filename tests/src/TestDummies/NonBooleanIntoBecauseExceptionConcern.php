<?php

namespace ContextSpecification\TestDummies;

class NonBooleanIntoBecauseExceptionConcern extends \ContextSpecification\Framework\StaticConcern {


	protected function context( ) {
		$this->becauseWillThrowException( 'This is not a boolean value' );
	}

	protected function because( ) {
		null;
	}


}

?>