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
 * A Base Concern Class For Testing Static Methods or Standalone Functionality
 *
 * This class is identical to its parent class with the exception of the fact
 * that it assumes the action being tested requires no specific "state" to be
 * setup beforehand or internally verified afterwards.  As such, no
 * System-Under-Test (SUT) object is necessary, so this "StaticConcern" class
 * allows the user a Context Specification -style syntax without having to set
 * up such a SUT.
 *
 * Users are free to execute extending Concerns' <code>because</code> methods
 * as static method or global function calls, etc, as needed, without having
 * to fake some sort of instantiated SUT.
 *
 * @author     Jim DeLois <%%PHPDOC_AUTHOR_EMAIL%%>
 */
abstract class StaticConcern extends BaseTestCase {

	/**
	 * Protects the (final) Parent Base Class From Possibly Receiving a SUT
	 *
	 * Intentionally void in order to ensure that this Concern has no SUT
	 * on which to operate. Frees the user from having to worry about these
	 * details.
	 *
	 * @internal
	 * @return void
	 */
	final protected function initSUT( ) { }

}

?>