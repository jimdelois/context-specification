<?php

namespace Tests\ContextSpecification\Framework\TestDummies;

class StandardExceptionConcern extends \ContextSpecification\Framework\Concern {

	public $count_context = 0;
	public $count_because = 0;
	public $count_create_sut = 0;
	public $count_decontext = 0;
	public $count_test_something = 0;
	public $call_log = array( );

	protected function context( ) {
		$this->becauseWillThrowException( );
		++$this->count_context;
		$this->call_log[ ] = 'context';
	}

	protected function because( ) {
		++$this->count_because;
		$this->call_log[ ] = 'because';
		throw new \Exception( );
	}

	protected function createSUT() {
		++$this->count_create_sut;
		$this->call_log[ ] = 'createSUT';
	}

	protected function decontext( ) {
		$this->becauseWillThrowException( false );
		++$this->count_decontext;
		$this->call_log[ ] = 'decontext';
	}

	public function not_using_exception( ) {
		++$this->count_test_something;
		$this->call_log[ ] = 'not_using_exception';
	}

	public function using_capture_exception( ) {
		$this->call_log[ ] = 'using_capture_exception';
		$this->captureException();
	}

	public function using_trigger_exception( ) {
		$this->call_log[ ] = 'using_trigger_exception';
		$this->triggerException();
	}

	public function getExceptionObject( ) {
		return $this->exception;
	}
}

?>