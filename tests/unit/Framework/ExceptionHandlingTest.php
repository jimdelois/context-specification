<?php
/**
 * Copyright (c) 2014, Jim DeLois
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its contributors
 * may be used to endorse or promote products derived from this software without
 * specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Tests
 * @subpackage ContextSpecification
 * @author     Jim DeLois <%%PHPDOC_AUTHOR_EMAIL%%>
 * @copyright  2014 Jim DeLois
 * @license    http://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @version    %%PHPDOC_VERSION%%
 * @link       https://github.com/jimdelois/context-specification
 *
 */

namespace Tests\ContextSpecification\Framework;

use Tests\ContextSpecification\Framework\TestDummies\StandardExceptionConcern;
use Tests\ContextSpecification\Framework\TestDummies\NonBooleanIntoBecauseExceptionConcern;
use Tests\ContextSpecification\Framework\TestDummies\BecauseExceptionNotSetConcern;
use Tests\ContextSpecification\Framework\TestDummies\ExceptionNotActuallyThrownConcern;

class ExceptionConcernTest extends \PHPUnit_Framework_TestCase {

	// Because is Never Called
	public function testCorrectOrderWithoutBecause( ) {
		$concern = new StandardExceptionConcern( 'not_using_exception' );
		$result = $concern->run( );

		$first = array_shift( $concern->call_log );
		$second = array_shift( $concern->call_log );
		$third = array_shift( $concern->call_log );
		$last = array_shift( $concern->call_log );

		$this->assertEquals( 'context' , $first );
		$this->assertEquals( 'createSUT' , $second );
		$this->assertEquals( 'not_using_exception' , $third );
		$this->assertEquals( 'decontext' , $last );

	}

	public function testCaptureWillTrapException( ) {
		$concern = new StandardExceptionConcern( 'using_capture_exception' );
		$result = $concern->run( );

		$this->assertInstanceOf( '\Exception' , $concern->getExceptionObject( ) );
	}

	public function testCorrectOrderWithBecauseViaCapture( ) {
		$concern = new StandardExceptionConcern( 'using_capture_exception' );
		$result = $concern->run( );

		$this->assertInstanceOf( '\Exception' , $concern->getExceptionObject( ) );

		$first = array_shift( $concern->call_log );
		$second = array_shift( $concern->call_log );
		$third = array_shift( $concern->call_log );
		$fourth = array_shift( $concern->call_log );
		$last = array_shift( $concern->call_log );

		$this->assertEquals( 'context' , $first );
		$this->assertEquals( 'createSUT' , $second );
		$this->assertEquals( 'using_capture_exception' , $third );
		$this->assertEquals( 'because' , $fourth );
		$this->assertEquals( 'decontext' , $last );

	}

	public function testTriggerThrowExceptionWhichWillFailIfNotExpected( ) {
		$concern = new StandardExceptionConcern( 'using_trigger_exception' );

		$result = $concern->run( );


		$this->assertInstanceOf( '\Exception' , $concern->getExceptionObject( ) );

		$this->assertFalse( $result->wasSuccessful( ) );
		$this->assertEquals( 1 , $result->errorCount( ) );

	}

	public function testTriggerThrowExceptionWhichWillPassIfExpected( ) {
		$concern = new StandardExceptionConcern( 'using_trigger_exception' );
		$concern->setExpectedException( '\Exception' );
		$result = $concern->run( );

		$this->assertInstanceOf( '\Exception' , $concern->getExceptionObject( ) );

		$this->assertTrue( $result->wasSuccessful( ) );
		$this->assertEquals( 0 , $result->errorCount( ) );
		$this->assertEquals( 0 , $result->failureCount( ) );
	}


	public function testNonBooleanInputToBecauseWillRaiseExceptionMethod( ) {
		$concern = new NonBooleanIntoBecauseExceptionConcern( );
		$result = $concern->run( );

		$this->assertFalse( $result->wasSuccessful( ) );
		$this->assertEquals( 1 , $result->errorCount( ) );
	}


	public function testBecauseWillRaiseExceptionNotCalledPriorToReleasing( ) {
		$concern = new BecauseExceptionNotSetConcern( );
		$result = $concern->run( );
		try {
			$concern->testReleasing();
		} catch( \RuntimeException $e ) {
			$this->assertEquals(
				'Must configure test for Exception-throwing Because method prior to calling "releaseException".'
				, $e->getMessage( )
			);
		}


		$this->assertFalse( $result->wasSuccessful( ) );
		$this->assertEquals( 1 , $result->errorCount( ) );
	}



	public function testBecauseWillRaiseExceptionNotCalledPriorToCapturing( ) {
		$concern = new BecauseExceptionNotSetConcern( );
		$result = $concern->run( );
		try {
			$concern->testCapturing();
		} catch( \RuntimeException $e ) {
			$this->assertEquals(
				'Must configure test for Exception-throwing Because method prior to calling "captureException".'
				, $e->getMessage( )
			);
		}

		$this->assertFalse( $result->wasSuccessful( ) );
		$this->assertEquals( 1 , $result->errorCount( ) );
	}


	public function testExceptionNotActuallyThrownWhileTryingToCapture( ) {
		$concern = new ExceptionNotActuallyThrownConcern( );
		$result = $concern->run( );

		$this->assertNull( $concern->getCaptured( ) );
	}



	public function testExceptionNotReleasedIfNotActuallyThrownWhileTryingToCapture( ) {
		$concern = new ExceptionNotActuallyThrownConcern( );
		$result = $concern->run( );

		$this->assertNull( $concern->doRelease( ) );
	}
}






?>