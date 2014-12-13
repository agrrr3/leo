<?php
namespace Peridot\Leo\Matcher;

use Peridot\Leo\Matcher\Template\ArrayTemplate;
use Peridot\Leo\Matcher\Template\TemplateInterface;

class KeysMatcher extends AbstractMatcher
{
    protected $verb = 'have';

    /**
     * {@inheritdoc}
     *
     * @return TemplateInterface
     */
    public function getDefaultTemplate()
    {
        $subject = 'key';

        if (count($this->expected) > 1) {
            $subject = "keys";
        }

        $template = new ArrayTemplate([
            'default' => "Expected {{actual}} to {$this->verb} $subject {{keys}}",
            'negated' => "Expected {{actual}} to not {$this->verb} $subject {{keys}}"
        ]);

        return $template->setTemplateVars(['keys' => $this->getKeyString()]);
    }

    /**
     * Assert that the actual value is an array with the expected keys.
     *
     * @param $actual
     * @return mixed
     */
    protected function doMatch($actual)
    {
        $actual = $this->getArrayValue($actual);
        if ($this->assertion->flag('contain')) {
            $this->verb = 'contain';
            return $this->matchInclusion($actual);
        }
        $keys = array_keys($actual);
        return $keys == $this->expected;
    }

    /**
     * @param object|array $actual
     */
    protected function getArrayValue($actual)
    {
        if (is_object($actual)) {
            return get_object_vars($actual);
        }

        if (is_array($actual)) {
            return $actual;
        }

        throw new \InvalidArgumentException("KeysMatcher expects object or array");
    }

    /**
     * @return string keys
     */
    protected function getKeyString()
    {
        if (! $this->expected) {
            return '';
        }

        $expected = $this->expected;
        $keys = '';
        $tail = array_pop($expected);

        if ($expected) {
            $keys = implode('","', $expected) . '", and "';
        }

        $keys .= $tail;

        return $keys;
    }

    /**
     * @param array $actual
     * @return true
     */
    protected function matchInclusion($actual)
    {
        foreach ($this->expected as $key) {
            if (!isset($actual[$key])) {
                return false;
            }
        }
        return true;
    }
}