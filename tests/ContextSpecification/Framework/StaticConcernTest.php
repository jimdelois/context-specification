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

use Tests\ContextSpecification\Framework\TestDummies\StandardStaticConcernWithSingleTest;

class StaticConcernTest extends \PHPUnit_Framework_TestCase {

	public function testCorrectOrder( ) {
		$concern = new StandardStaticConcernWithSingleTest( 'test_something' );
		$result = $concern->run( );

		$first = array_shift( $concern->call_log );
		$second = array_shift( $concern->call_log );
		$third = array_shift( $concern->call_log );
		$last = array_shift( $concern->call_log );

		$this->assertEquals( 'context' , $first );
		$this->assertEquals( 'because' , $second);
		$this->assertEquals( 'test_something' , $third );
		$this->assertEquals( 'decontext' , $last );

	}


	public function testCorrectCountForSingleTest( ) {
		$concern = new StandardStaticConcernWithSingleTest( 'test_something' );
		$result = $concern->run( );

		$this->assertEquals( 1 , $concern->count_context );
		$this->assertEquals( 1 , $concern->count_because );
		$this->assertEquals( 0 , $concern->count_create_sut );
		$this->assertEquals( 1 , $concern->count_test_something );
	}

}


?>