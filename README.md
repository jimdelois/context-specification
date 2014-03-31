Context Specification Testing Framework
==========================================

The Context Specification (CS) library is a PHPUnit wrapper intended to promote the use of a
CS-style testing pattern.  This approach offers the Test-Driven Developer
a working syntax which more-closely aligns with how behavior is described in business terms, giving
rise to the increasingly popular "Behavior-Driven Design (BDD)" paradigm.

When compared to "traditional" unit testing, Context Specification -style BDD seems negligibly
different, if at all.  While at first the only deviation appears to be in syntactic semantics, the
subtle arrangement of the "different" methods plays a huge part in how functionality is logically
organized... and tested.

Namely, this distinction is realized when describing and considering functionality as broken into
three parts... (1) The starting point; an initial state; a "Context" in which a particular
action is to be taken. (2) The action itself; the "state transition" from the initial state
into a new one. (3) The new, final, or resultant state after the state transition is complete.

By testing only one single transitional event or method at a time, and then
verifying that everything about the new state is sufficiently expected,
in conjunction with having correctly provided an initial state, it can then be assumed
that the state transition, itself, is operating correctly.  Since a computer program operates by
endlessly transitioning from one state to another, we can ensure that a library is operating
correctly by testing each transition individually, and from all known initial states. Such a
consideration of behavior/functionality will likely lead to greater code coverage, fewer bugs
and, when requirements can be expressed in a similar fashion, a more accurately-modeled system.

It's true that the same type of BDD can be practiced with the similar granularity and behavioral
coverage without any such wrapper. The hope, however, is that providing a lightweight library
and suggesting that a few rules be followed, a developer's tests will read more clearly while also
providing a more natural report of test failures (and successes!).

Lastly, there are several approaches that could tighten up the BDD paradigm substantially,
similar to some other frameworks in other languages. However, this library is developed specifically
for PHPUnit, with tooling in mind. That is to say, this library should work correctly in all
extensions of PHPUnit (so long as they aren't explicitly removing default behavior), regardless
of any customized TestRunners, Commands, etc, put in place by either a developer or an IDE.

Installation
------------

It is recommended that the Context Specification library be installed via the Composer dependency
management tool.  Otherwise, the latest compressed file may be downloaded from Github at any time,
and any PSR-4 autoloaders will work to include the files therein.

### Composer Installation

To your `composer.json` file, add the following:

    "require" : {
    	"jimdelois/context-specification": "0.1.*@dev" ,
    }

Once you've run `composer install` or `composer update`, you should have the right libraries
available to you within the
`{vendordir}/jimdelois/context-specification/src/ContextSpecification/Framework/` directory.

Basic Usage
-----------

Depending on the need, simply extend the `Concern` or `StaticConcern` classes and begin testing as normal!

If global or isolated functionality is being tested where no state can be injected, inserted, or tracked,
then the `StaticConcern` will be just enough of a harness for this. In either case,
it is up to the developer to provide an implementation for the following two abstract methods:

    abstract protected function context( );
    abstract protected function because( );

The `context` method is where the starting point, or initial state is established.  The `because`
method will be called next, where the SUT or isolated functionality will have its one and only
tested state change executed.

If there is a System Under Test (SUT) which requires some initial state, has dependencies which
require state prior to injection, or has a resultant internal state that must be checked, then
the developer should extend the `Concern` class and instantiate this system from within:

    protected function createSUT( );

By returning the object or system under test from this method, it will automatically be available within
the Concern class as `$this->sut`. It ought be on this object that a Context is set specific to the test,
state changes are invoked (in the `because` method), and assertions are made in observations.

Such observations, whether or not there is a SUT, can be made in standard PHPUnit "test" methods.
Any method prefixed with "test" will work, although it is a general recommendation to use the
`@test` annotation built into PHPUnit and then name the observation methods in accordance with
traditional CS language, such as `should`, etc.  This will have benefits of a better report, e.g.,
a `--testdox` report will read like a regular CS report.

E.g., consider `MyTest.php` as:

    <?php
        use ContextSpecification\Framework\Concern;
        class When_attempting_to_do_something_awesome_given_a_specific_context extends Concern {
            protected function context( ) { ... } // Fill this in!
            protected function because( ) { ... } // Fill this in!

            /**
             * @test
             */
            public function should_really_be_awesome( ) {
                $awesome = true;
                $this->assertTrue( $awesome );
            }

            /**
             * @test
             */
            public function then_it_should_not_be_boring( ) {
                $boring = false;
                $this->assertFalse( $boring );
            }
        }
    ?>

Then, most reports will read like a traditional CS one (note the verbosity!):

    $ ../vendor/bin/phpunit --testdox MyTest.php
    PHPUnit 3.7.33 by Sebastian Bergmann.

    When_attempting_to_do_something_awesome_given_a_specific_context
    [x] should_really_be_awesome
    [x] then_it_should_not_be_boring

Additionally, the method `decontext` is available for optionally "undoing" any of the context establishment. This method is called
after each observation, balancing the calls to `context`. Note that reliance on this method to pass tests for true "units"
of functionality is likely the result of a poor test approach or, worse, a bad design (globals?!).  Depending on the
code we're testing, we may not be able to get away from this, so this function provides an opportunity to "reset" anything
between observations.  In "integration" testing (not "unit"), the need to do this is more commonplace and arguably
much more "acceptable".

Finally, the Context Specification library offers a small amount of extra functionality that is useful in dealing with Exceptions
raised from state changes.  Just as one should test for all possible positive-scenario contexts, a developer should also
test for contexts in which state might be invalid... Typically the target functionality (in the SUT) will be coded to raise
an exception in this case.  If we provided an invalid input directly into our `because` method's state change:

	protected function because( ) {
		$this->sut->setInteger( 'This is a string but the method expects an INT!' );
	}

... The actual invocation of `because` would cause the Exception to be raised before we're prepared, and PHPUnit would fail
immediately.  The correct solution is NOT to leave the `because` method blank and move the state change to the observation!
Doing so brings us further back to "standard" TDD practice, wherein the state change is invoked within the same method that
asserts - this is precisely what we're trying to avoid. Rather, consider capturing the Exception from within the `because`
method and then evaluating it later. Alternatively, try wrapping the state change in a lambda function and then invoke
it later.  While the latter approach is only a semantic difference from the "discouraged" one, it's an important distinction
which helps to keep our tests consistent and well-organized.

As testing invalid states and ensuring correct Exceptions are raised is a very common occurrence (or should be), the
Context Specification framework does the favor of minimizing the amount of work the testing developer has to do to deal
with these nuances. By simply calling `$this->becauseWillThrowException( );`, the Concern will be configured to
automatically wrap the `because` method contents in a lambda, meaning one can define the state change as per usual. Then,
two additional methods become available which can be used from within observations to validate and assert the
resultant Exceptions: `triggerException` and `captureException`.

Examples
--------

Below are a couple of (silly) examples of how we might test a single method on a service using the Context Specification library.

    <?php
    	use ContextSpecification\Framework\Concern;
    	use My\Library\AppService\MyAwesomeAppService;
    	use My\Library\Domain\Awesomeness;
    	use Phake;

		class When_loading_first_awesomeness_from_service_for_date extends Concern {

			protected $result_actual;
			protected $result_expected;
			protected $dao_awesomeness;
			protected $date_time;

			// Establish a context in which we'll be testing our functionality
			protected function context( ) {

				$this->date_time = new \DateTime( );

				$this->result_expected = new Awesomeness( 'Today will be AWESOME. Maybe.' );

				$dao_return_array = array(
					$this->result_expected ,
					new Awesomeness( 'Should not be seeing this message' ) ,
					new Awesomeness( 'Three is a charm' )
				);

				$this->dao_awesomeness = Phake::mock( 'My\Library\DAO\AwesomenessInterface' );
				Phake::when( $this->dao_awesomeness )->loadAllByDate( $this->date_time )->thenReturn( $dao_return_array );
			}

			// Setup a System-Under-Test
			protected function createSUT( ) {
				return new MyAwesomeAppService( $this->dao_awesomeness );
			}

			// Execute the functionality; the "state change."
			protected function because( ) {
				$this->result_actual = $this->sut->getFirstAvailableAwesomenessForDate( $this->date_time );
			}

			/**
			 * @test
			 */
			public function should_call_appropriate_method_on_awesomeness_dao( ) {
				Phake::verify( $this->dao_awesomeness )->loadAllByDate( $this->date_time );
				Phake::verifyNoFurtherInteraction( $this->dao_awesomeness );
			}

			/**
             * @test
             */
            public function should_return_correct_awesomeness_object( ) {
            	$this->assertEquals( $this->result_expected , $this->result_actual );
            }
		}
    ?>

Now, assuming that we've correctly implemented the functionality for the method `getFirstAvailableAwesomenessForDate( )`
on the `MyAwesomeAppService` object (only AFTER writing the test, of course!), then we should see something like
the following:

	$ ../vendor/bin/phpunit --testdox
    PHPUnit 3.7.33 by Sebastian Bergmann.

	When_loading_first_awesomeness_from_service_for_date
    [x] should_call_appropriate_method_on_awesomeness_dao
    [x] should_return_correct_awesomeness_object

That's pretty nice! Let's iterate on the functionality of our `getFirstAvailableAwesomenessForDate` method and ensure
that an exception is raised is the input isn't a `\DateTime` object.

    <?php
    	use ContextSpecification\Framework\Concern;
    	use My\Library\AppService\MyAwesomeAppService;
    	use My\Library\Domain\Awesomeness;
    	use Phake;

		class When_loading_first_awesomeness_from_service_for_non_date_input extends Concern {

			protected $dao_awesomeness;
			protected $date_time_invalid;

			// Establish a context in which we'll be testing our functionality
			protected function context( ) {

				// This causes the library to trap the contents of "because" into a lambda for later execution.
				$this->becauseWillThrowException( );

				$this->date_time_invalid = 'THIS_IS_A_STRING';
				$this->dao_awesomeness = Phake::mock( 'My\Library\DAO\AwesomenessInterface' );

			}

			// Setup a System-Under-Test
			protected function createSUT( ) {
				return new MyAwesomeAppService( $this->dao_awesomeness );
			}

			// Execute the functionality; the "state change."
			protected function because( ) {
				$this->sut->getFirstAvailableAwesomenessForDate( $this->date_time_invalid );
			}

			/**
			 * @test
			 */
			public function should_raise_invalid_argument_exception( ) {
				$this->setExpectedException( '\InvalidArgumentException' );
				$this->triggerException( );
			}
		}
    ?>

Note that the framework allowed us to keep a nice, clean `because` method regardless of the fact that invocation would
cause a test-failing error.

Alternatively, one could call `captureException` which would avoid throwing it and then make it available from within
`$this->exception` so that it may be inspected and used in assertions.  It's also possible that one might want to make
assertions on the state of `$this->sut` after having thrown the exception from within.

Ideally, correct implementation will then yield the following when the entire suite is run:

	$ ../vendor/bin/phpunit --testdox
    PHPUnit 3.7.33 by Sebastian Bergmann.

	When_loading_first_awesomeness_from_service_for_date
    [x] should_call_appropriate_method_on_awesomeness_dao
    [x] should_return_correct_awesomeness_object

	When_loading_first_awesomeness_from_service_for_non_date_input
    [x] should_raise_invalid_argument_exception

There are some obvious flaws to these two trivial examples, and they are already screaming for a shared parent base
Concern.  However, the point of intended usage here is arguably more important than proper test design or architecture.

Issues
------

- It would be ideal to extend the base PHPUnit Framework with configurable "Test Method Prefixes", just as they currently
allow for the adjustment of "Test Suffixes" at the file level.  By opening up configurability for method prefixes,
we could get away from having to annotate any "specially named CS test methods" using the `@test`.  Extending the
PHPUnit_Framework_TestSuite to flag such methods is trivial, and a full swap of this suite for the base is possible with
a set of custom Runners and/or Commands - but for the same reason this is a approach that works for introducing additional
functionality into PHPUnit, it's a common approach taken by other developers and IDEs... Meaning that if this particular
library insisted on *also* using custom Runners and Commands, it will almost certainly not work in other IDEs or with
other extensions of PHPUnit.

  Until such a configuration is accepted into the core PHPUnit framework and eventually propagates to other tooling, we are
likely stuck with having to use `test*` for the test methods or, as recommended, annotate them using `@test`.

- PHPUnit's `setUp()` method is an instance method run before each and every test method or, in this case, "observation."
However, `setUpBeforeClass()` is a *static* method that runs once per class.  In theory, a Context within a Context Specification
test case should only be set up once per class (this is arguably one of the largest differences between traditional testing
and CS testing - state changes are so clearly separated from assertions and context establishment that there's really no need
to ever tearDown and setUp for each observation).  This approach is common in other CS frameworks, but the difference is that
their base frameworks' "once-per-class" equivalents aren't static methods.

  This is not to suggest that it cannot be done within this library, but it's non necessarily trivial
to properly set our Concern up from within the statics.  The same goes for `tearDown` vs. `tearDownAfterClass`.  With that,
it's important to realized that **a context will be established and a context will be de-contexted before and after every
single observation method.**  What this actually means from a practical standpoint is that there may be performance
implications for any deep integration tests that contain multiple observations and complex or latent set ups.  For this
reason (among several others), it's often wise to logically separate all "unit" suites from "integration" suites.

Next Steps
----------

- It would be ideal to add additional, class-level annotations such as `@concern {Concern name here}`, which would allow
the tester to group multiple concerns, for the sake of reporting, *across several distinct files*.
Of course, a custom reporter would have to be used to accommodate this, although it's distinctly possible
that a listener could sit in the middle and perform some trickery...

  Similarly, grouping at the next-level down could be achieved with some type of `@when` annotation, as well.

- Look into addressing the above "issue" concerning once-per-observation establishment of context versus
once-per-concern.


Questions/Feedback
------------------

Jim DeLois - [%%PHPDOC_AUTHOR_EMAIL%%](mailto:%%PHPDOC_AUTHOR_EMAIL%%)