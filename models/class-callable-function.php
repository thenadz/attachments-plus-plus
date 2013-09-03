<?php

/**
 * Represents a function that may be invoked.
 *
 * @author drossiter
 */
class APPCallableFunction {
   private $function_name, $function_class, $function_path;

   /**
    * Constructs instance of APPCallableFunction.
    *
    * @param string $funct - function name (non null)
    * @param string $class - function class
    * @param string $path - function path
    */
   public function __construct($funct, $class=null, $path=null) {
      $this->set_path($path);
      $this->set_class($class);
      $this->set_function($funct);
   }

   /**
    * Gets path to function.
    *
    * @return string
    */
   public function get_path() {
      return $this->function_path;
   }

   /**
    * Gets value which may be called, including the class if not null.
    *
    * @return callback - Can be called.
    */
   public function get_callback() {
      if(is_null($this->function_class)) {
         $ret = $this->function_name;
      } else {
         $ret = array($this->function_class, $this->function_name);
      }

      return $ret;
   }

   /**
    * Sets path to function.
    *
    * @param string $path - Path to file including function to be called.
    */
   public function set_path($path) {
      $this->function_path = $path;
   }

   /**
    * Sets class name.
    *
    * @param string $class - Class which contains function to be called.
    */
   public function set_class($class) {
      $this->function_class = $class;
   }

   /**
    * Sets function name.
    *
    * @param callable $funct - Function name to be called.
    * @throws InvalidArgumentException
    */
   public function set_function($funct) {
      if(is_null($funct)) {
         throw new InvalidArgumentException("Function name may not be null.");
      }

      $this->function_name = $funct;
   }

   /**
    * Returns whether the function represented by this object can be called.
    *
    * @return boolean
    */
   public function is_valid() {
      if(!is_null($this->function_path)) {
         include_once $this->function_path;
      }

      return is_callable($this->get_callback());
   }

   /**
    * Runs function represented by this object.
    *
    * @param array $args - Passed to executing function.
    * @throws BadFunctionCallException
    */
   public function execute($args=null) {
      $valid = $this->is_valid();

      if($valid) {
         $ret = is_null($args)
             ? call_user_func($this->get_callback())
             : call_user_func($this->get_callback(), $args);
      }

      if(!$valid || $ret === false) {
         throw new BadFunctionCallException(
             "Invalid function call requested for: " . $this);
      }

      return $ret;
   }

   /**
    * String representation of object.
    *
    * @return string
    */
   public function __toString() {
      return
         (is_null($this->function_path) ? '' : $this->function_path . ' ')
         . (is_null($this->function_class) ? '' : $this->function_class . '::')
         . $this->function_name;
   }
}
?>
