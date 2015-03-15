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
 * A Base Concern Class For Testing Normal Systems/Objects Requiring State
 *
 * This class is identical to its parent class with the exception of the fact that it
 * assumes the action being tested requires some type of "State." This State may exist
 * within the object directly, within the dependencies on which the object relies, or
 * perhaps as some State internal to the object that must be verified through
 * assertions after the "state transition" (<code>because</code> method) has been
 * invoked.
 *
 * @author     Jim DeLois <%%PHPDOC_AUTHOR_EMAIL%%>
 */
abstract class Concern extends BaseTestCase {
	/**
	 * Member Variable to Hold the System-Under-Test (SUT)
	 *
	 * Will automatically be populated with the SUT returned from <code>createSUT()</code>
	 * For that reason, consider this a "read-only" property.
	 *
	 * @var \stdClass Any object
	 */
	protected $sut;

	/**
	 * Responsible for Creating System-Under-Test (SUT).
	 *
	 * Must <code>return</code> an instantiated object, with state fully prepared
	 * for the specific functionality that is about to be tested as part of the
	 * state transition.
	 *
	 * Returning an object from this method will automatically populate
	 * <code>$this->sut</code> for the future invocation of a state change,
	 * as well as use within any observations thereafter.
	 *
	 * @return \stdClass Any object
	 */
	abstract protected function createSUT( );

	/**
	 * Allows the (final) Parent Base Class a Hook to Instantiate a SUT
	 *
	 * This method calls through to <code>createSUT</code>, but does so intelligently.
	 * It allows an extending class to call <code>createSUT</code> from within the
	 * <code>context</code> method in the event that state needs to be injected
	 * directly into the SUT without (or after) constructor interaction. In this case,
	 * the SUT will not be created again. Instead, the original will remain untouched.
	 *
	 * Note, however, that this approach is discouraged in favor of overriding
	 * <code>createSUT()</code> to set up the correct SUT with the correct state therein,
	 * as opposed ot creating the SUT within the <code>context</code> method and then
	 * operating on it. However, the option remains.
	 *
	 * @internal
	 * @return void
	 */
	final protected function initSUT( ) {
		if ( $this->sut === null ) {
			$this->sut = $this->createSUT( );
		}
	}
}

?>