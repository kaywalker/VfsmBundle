VfsmBundle
==========

## Installation

### Composer
 
Add the following dependencies to your projects composer.json file:

```json
     "repositories": [
         {
             "type": "git",
             "url": "https://github.com/kaywalker/VfsmBundle.git"
         }
     ],
     "require": {
         "hn/vfsm-bundle": "dev-master"
     }
```

## Usage

### Useless Machine

The "Useless Machine" is a machine that does no other task, but switching itself of.
It has two states: "on" and "off". the initial state is the "off" state. when the switch is set to the on position the machine change from "off" to "on".
On entering the "on" state, a timer of 1s is being started. When the timers timeout is reached, the switch is put back to the off position and the machine is set from "on" to "off" state

```php
 class UselessMachine {
 
     const $STATE_OFF = 'off';
     const $STATE_ON = 'on';
  
     const $INPUT_SWITCH_IS_OFF = 'switch_is_off';
     const $INPUT_SWITCH_IS_ON = 'switch_is_on';
     const $INPUT_TIMEOUT = 'timeout';
   
     const $ACTION_START_TIMER = 'start_timer';
     const $ACTION_SWITCH_OFF = 'switch_off'
     
     public function __construct() 
     {
         $specification = array(
            'specification' => array(
                 self::$STATE_OFF => array(
                     'transitions' => array(
                         array(
                             'to_state' => self::$STATE_ON,
                             'condition' => array(array(self::$INPUT_SWITCH_IS_ON)),
                         )
                     )
                 ),
                 self::$STATE_ON => array(
                     'enter_action' => self::$ACTION_START_TIMER
                     'input_actions' => array(
                         'action' => self::$ACTION_SWITCH_OFF,
                         'condition' => array(array(self::$INPUT_TIMEOUT))
                     ),
                     'transitions' => array(
                         array(
                             'to_state' => self::$STATE_OFF,
                             'condition' => array(
                                 array(self::$INPUT_SWITCH_IS_OFF)
                             ),
                         )
                     )
                 )
             )
         );
         
         $this->executer = new SpecificationExecuter($specification, self::$STATE_OFF);
     }
 }
