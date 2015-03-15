<?php

namespace ContextSpecification\TestDummies;

class NonBooleanIntoBecauseExceptionConcern extends \ContextSpecification\Framework\StaticConcern {

	use Traits\DummyConcernTrait;

	protected function context( ) {
		$this->becauseWillThrowException( 'This is not a boolean value' );
	}

	protected function because( ) {
		null;
	}


}

?>