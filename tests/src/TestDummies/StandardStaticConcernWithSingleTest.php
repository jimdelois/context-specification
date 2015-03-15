<?php

namespace Tests\ContextSpecification\Framework\TestDummies;

class StandardStaticConcernWithSingleTest extends \ContextSpecification\Framework\StaticConcern {

	public $count_context = 0;
	public $count_because = 0;
	public $count_create_sut = 0;
	public $count_decontext = 0;
	public $count_test_something = 0;
	public $call_log = array( );

	protected function context( ) {
		++$this->count_context;
		$this->call_log[ ] = 'context';
	}

	protected function because( ) {
		++$this->count_because;
		$this->call_log[ ] = 'because';
	}

	protected function createSUT() {
		++$this->count_create_sut;
		$this->call_log[ ] = 'createSUT';
	}

	protected function decontext( ) {
		++$this->count_decontext;
		$this->call_log[ ] = 'decontext';
	}

	public function test_something( ) {
		++$this->count_test_something;
		$this->call_log[ ] = 'test_something';
		$this->assertFalse( false );
	}
}

?>