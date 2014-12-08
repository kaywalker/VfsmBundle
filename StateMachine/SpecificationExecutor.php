<?php
/**
 * User: kay
 * Date: 28.11.14
 * Time: 19:24
 */

namespace Hn\VfsmBundle\StateMachine;

use Symfony\Component\Config\Definition\Processor;

class SpecificationExecutor {

    /**
     * @var array
     */
    private $specification;

    /**
     * @var string|null
     */
    private $state = null;

    /**
     * @param array $specification
     * @param string $startState
     * @throws \Exception if $startState does not exist in $specification keys
     */
    function __construct($specification, $startState)
    {

        $processor = new Processor();
        $processedSpecification = $processor->processConfiguration(new Specification(), $specification);

        $this->state = $startState;
        $this->specification = $processedSpecification;

        if (!array_key_exists($this->state, $this->specification)) {
            throw new \Exception('illegal start state');
        }
    }

    /**
     * @param array $input
     * @return array
     */
    public function process($input = array())
    {
        $output = array();

        if ($this->specification[$this->state]) {

            foreach ($this->specification[$this->state]['input_actions'] as $inputAction) {
                if ($this->evaluateCondition($inputAction['condition'], $input)) {
                    $output[] = $inputAction['action'];
                }
            }
        }

        $output = array_merge($output, $this->checkTransitions($input));

        return $output;
    }

    /**
     * @param array $input
     * @return array
     */
    protected function checkTransitions($input = array())
    {
        $output = array();

        if ($this->specification[$this->state]['transitions']) {

            foreach ($this->specification[$this->state]['transitions'] as $transition) {

                if ($this->evaluateCondition($transition['condition'], $input)) {

                    if (!array_key_exists($transition['to_state'], $this->specification)) {
                        throw new \Exception('illegal transition state');
                    }

                    if ($this->specification[$this->state]['exit_action']) {
                        $output[] = $this->specification[$this->state]['exit_ction'];
                    }

                    $this->state = $transition['to_state'];

                    if ($this->specification[$this->state]['enter_action']) {
                        $output[] = $this->specification[$this->state]['enter_action'];
                    }
                }
            }
        }

        return $output;
    }

    /**
     * @param $expression
     * @param $input
     * @return bool
     */
    protected function evaluateCondition($expression, $input) {

        foreach ($expression as $term) {

            $matchedInputCount = 0;

            foreach ($term as $requiredInputValue) {
                if (in_array($requiredInputValue, $input)) {
                    $matchedInputCount++;
                }
            }

            if ($matchedInputCount == count($term)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param null|string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function getSpecification()
    {
        return $this->specification;
    }
}
