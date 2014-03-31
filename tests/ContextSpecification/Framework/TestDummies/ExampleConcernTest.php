<?php


class When_calling_truthy_method extends ContextSpecification\Framework\Concern {

	protected $result;

	protected function context( ) {
	}

	protected function because( ) {
		$this->result = $this->sut->returnsTrue( );
	}

	protected function createSUT( ) {
		return new SystemUnderTest();
	}

	/**
	 * @test
	 */
	public function should_return_true( ) {
		$this->assertTrue( $this->result );
	}

	/**
	 * @test
	 */
	public function should_do_something_else_truthy( ) {
		$this->assertTrue( true );
	}
}


class When_throwing_an_exception extends \ContextSpecification\Framework\Concern {


	protected function context( ) {
		$this->becauseWillThrowException( );
	}

	protected function because( ) {
		$this->sut->beExceptional( );
	}

	protected function createSUT( ) {
		return new SystemUnderTest();
	}

	/**
	 * @test
	 */
	public function should_be_seen_as_exception( ) {
		$this->captureException();
		$this->assertEquals( 'No way, Hoss!' , $this->exception->getMessage( ) );

	}

	/**
	 * @test
	 */
	public function should_be_thrown_as_expected_exception( ) {
		$this->setExpectedException( '\InvalidArgumentException' );
		$this->triggerException( );
	}
}










class SystemUnderTest {
	public function returnsTrue( ) {
		return true;
	}

	public function beExceptional( ) {
		throw new \InvalidArgumentException( 'No way, Hoss!' );
	}
}

?>