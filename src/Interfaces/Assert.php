<?php
namespace Peridot\Leo\Interfaces;

use Peridot\Leo\Behavior\Assert\InclusionBehavior;
use Peridot\Leo\Behavior\Assert\TypeBehavior;
use Peridot\Leo\Matcher\InclusionMatcher;
use Peridot\Leo\Matcher\TypeMatcher;

/**
 * Assert is a traditional assert style interface.
 *
 * @method void typeOf() typeOf(mixed $value, string $type, string $message = "") validates the type of the passed in value
 * @method void notTypeOf() notTypeOf(mixed $value, string $type, string $message = "") validates that the type of a subject is not the given type
 * @method void include() include(mixed $haystack, mixed $needle, string $message = "") validates that a haystack contains the needle
 * @method void contain() contain(mixed $haystack, mixed $needle, string $message = "") validates that a haystack contains the needle
 * @method void notInclude() notInclude(mixed $haystack, mixed $needle, string $message = "") validates that a haystack does not contain a needle
 *
 * @package Peridot\Leo\Interfaces
 */
class Assert extends AbstractBaseInterface
{
    public function __construct($subject = null)
    {
        parent::__construct($subject);

        $this->setBehavior(new TypeBehavior(new TypeMatcher()));
        $this->setBehavior(new InclusionBehavior(new InclusionMatcher()));
    }

    /**
     * Include is an alias for the contain behavior. A method named "include" cannot
     * be defined by traditional means, so it is setup here to delegate to the contain behavior.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($name == "include") {
            return call_user_func_array([$this, 'contain'], $arguments);
        }
        return parent::__call($name, $arguments);
    }
}
