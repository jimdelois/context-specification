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
 * @author     Jim DeLois <%%PHPDOC_AUTHOR_EMAIL%%>
 * @copyright  2014 Jim DeLois
 * @license    http://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @version    %%PHPDOC_VERSION%%
 * @link       https://github.com/jimdelois/context-specification
 * @filesource
 *
 */

namespace ContextSpecification\Framework;

/**
 * The (Abstract) Base Concern Class from which all other Concern classes are extended
 *
 * This class provides the base functionality to other extending Concerns. It is a
 * direct extension of PHPUnit_Framework_TestCase and, as such, behaves as a Unit Test
 * and makes available all assertions and expectations accordingly.
 *
 * In the spirit of Context Specification -style testing, the <code>setUp</code> and <code>tearDown</code>
 * methods are locked as final, and are ultimately responsible for providing the proper
 * execution of CS-style methods in the correct order. Users are encouraged to follow
 * CS-style paradigm of "setting up" by providing a <code>context</code> method implementation.
 *
 * Additionally, this Base Class provides helper methods and properties for extending
 * Concerns to handle the expectations and testing of Exceptions thrown from state changes
 * specified as part of the <code>because</code> method without have to explicitly wrap the
 * <code>because</code> method contents in a lambda function or pointer.  <code>Because</code> method state
 * transitions that throw exceptions may be written as normal <code>because</code> methods, yet
 * Exceptions can be tested, asserted and expected within standard observations (test methods). For
 * this to work, the author must simply flag the Concern in its <code>context</code> method as
 * an Exception-throwing Concern by calling <code>$this->becauseWillThrowException();</code>
 *
 * @author     Jim DeLois <%%PHPDOC_AUTHOR_EMAIL%%>
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * Flag to read internally
	 *
	 * Signals whether or not this Concern is marked to handle Exceptions thrown
	 * from state changes. If so, the <code>because</code> method will automatically
	 * be wrapped in a lambda function so as not to bubble an Exceptions prior to
	 * expectations/observations being set up.
	 *
	 * @internal
	 * @var bool
	 */
	private $because_will_throw_exception = false;

	/**
	 * Holds the contents of a state transition in order to fire later
	 *
	 * If the Concern is configured to throw Exceptions from a state change, this
	 * variable will contain the contents of the <code>because</code> method in
	 * order to invoke the change at a later, more-appropriate time (within observations)
	 *
	 * @internal
	 * @var \Closure
	 */
	private $closure;

	/**
	 * Holds Exceptions thrown from state transitions
	 *
	 * If configured, will hold any \Exception object thrown from within the
	 * <code>because</code> method
	 *
	 * @var \Exception
	 */
	protected $exception;

	/**
	 * Once-Per-Test fixture set up
	 *
	 * This method is responsible for ordering the CS-style chain of execution,
	 * as well as checking for whether or not the Concern is flagged to test for Exceptions.
	 *
	 * @internal
	 * @return void
	 */
	final protected function setUp( ) {
		$this->context( );
		$this->initSUT( );
		if ( $this->because_will_throw_exception === true ) {
			$class = new \ReflectionClass( $this );
			$this->closure = $class->getMethod( 'because' )->getClosure( $this );
		} else {
			$this->because( );
		}
	}

	/**
	 * Once-Per-Test fixture tear down
	 *
	 * This method is responsible for ordering the CS-style chain of execution
	 * after each observation is executed.
	 *
	 * @internal
	 * @return void
	 */
	final protected function tearDown( ) {
		$this->decontext( );
	}

	/**
	 * Flag to indicate that the Concern will be testing Exceptions
	 *
	 * When calling this method from within <code>context</code>, it is flagging
	 * the Concern as one whose state transition (<code>because</code>) will yield
	 * an Exception of some sort. Raising the Exception as part of normal execution
	 * would cause a test failure, so the calling of this method signals the
	 * Concern to trap the contents of the <code>because</code> method in a lambda
	 * so that the actual throwing occurs as part of the observation, when expected.
	 *
	 * The raising of the Exception may then be done at a controlled point from within
	 * an observation (using <code>captureException</code> or <code>releaseException</code>),
	 * yet allows the <code>because</code> method to be written in a normal fashion,
	 * agnostic of this behavior.
	 *
	 * The method should be called during Context setup, e.g.,:
	 * <pre>
	 * protected function context( ) {
	 *     $this->becauseWillThrowException( );
	 *     // ... Additional context establishment code.
	 * }
	 * </pre>
	 *
	 * @param bool $bool Defaults to <code>true</code> if not specified
	 * @return void
	 * @throws \InvalidArgumentException If the (optionally) supplied parameter is not a boolean
	 */
	protected function becauseWillThrowException( $bool = true ) {
		if ( ! is_bool( $bool ) ) {
			throw new \InvalidArgumentException( sprintf( 'Expected boolean; "%s" given.' , $bool ) );
		}
		$this->because_will_throw_exception = $bool;
	}

	/**
	 * Once-Per-Observation Tear Down
	 *
	 * Used to clean up or undo any context initializations prior to the next
	 * observation being run (and, thus, the context being re-established)
	 *
	 * @return void
	 */
	protected function decontext( ) { }

	/**
	 * Once-Per-Observation Context Set Up
	 *
	 * The hook whose implementation ought fully establish a Context for the given
	 * concern.  This method is called once for each observation.
	 *
	 * @return void
	 */
	abstract protected function context( );

	/**
	 * Once-Per-Observation State Transition
	 *
	 * This method ought contain the one single action or state transition of the
	 * system-under-test.  No Concern should ever exist without a <code>because</code>
	 * implementation!
	 *
	 * @return void
	 */
	abstract protected function because( );

	/**
	 * Hook For Subclasses to Properly Handle a SUT
	 *
	 * Subclasses may or may not need to initialize a System Under Test (SUT).
	 * This stub is called from the <code>setUp</code> method and allows
	 * a subclass that is testing a static system to do nothing, while a subclass
	 * that actually has a system with state can dispatch the call to initialize
	 * their SUT accordingly.
	 *
	 * @internal
	 * @return void
	 */
	abstract protected function initSUT( );

	/**
	 * Capture Any Exceptions Expected from State Transition
	 *
	 * Working in concert with <code>becauseWillThrowException</code>, calling this method
	 * from within an observation will trigger the actual state transition and capture
	 * any resultant exceptions into <code>$this->exception</code>. From there, that
	 * property/object can be tested and assertions can be made against it, without
	 * it never having been thrown to the observing method or beyond.
	 *
	 * @return \Exception|null
	 * @throws \RuntimeException If Concern is not flagged for state transition to throw Exception
	 */
	final protected function captureException( ) {
		if ( ! $this->because_will_throw_exception ) {
			throw new \RuntimeException( 'Must configure test for Exception-throwing Because method prior to calling "captureException".' );
		}
		$closure = $this->closure;
		try {
			$closure( );
		} catch( \Exception $e ) {
			$this->exception = $e;
			return $this->exception;
		}
		return null;
	}

	/**
	 * Throw Exceptions from State Transitions to Meet Expectations
	 *
	 * If an observation is configured to expect a certain exception (e.g., via
	 * <code>$this->setExpectedException( ... )</code>, then calling this method
	 * will ensure that the expectations are met by throwing any exceptions previously
	 * trapped from the State Transition.
	 *
	 * @throws \RuntimeException If Concern is not flagged for state transition to throw Exception
	 * @throws \Exception So any expectations for one can be met
	 */
	final protected function releaseException( ) {
		if ( ! $this->because_will_throw_exception ) {
			throw new \RuntimeException( 'Must configure test for Exception-throwing Because method prior to calling "releaseException".' );
		}
		throw $this->captureException( );
	}
}

?>