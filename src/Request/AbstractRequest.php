<?php
namespace Quazardous\PriceministerWs\Request;

abstract class AbstractRequest {
    protected $parameters = array();
    protected $path;
    
    /**
     * Return path.
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set the parameters.
     * @param array $parameters
     * @param boolean $default : the given parameters are default values, existing values will precede.
     */
    public function setParameters(array $parameters, $default = false) {
        if ($default) {
            $this->parameters = array_merge($parameters, $this->parameters);
        }
        else {
            $this->parameters = array_merge($this->parameters, $parameters);
        }
    }
    
    /**
     * Get the parameters
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * Set a given parameter value.
     *
     * @param string $key
     * @param string $value
     * @param boolean $default : the given parameter is default value, existing value will precede.
     */
    public function setParameter($key, $value, $default = false) {
        if (isset($this->parameters[$key]) && $default) return;
        $this->parameters[$key] = $value;
    }
    
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array()) {
        $this->setParameters($parameters);
    }
    
    /**
     * Validate the parameters.
     */
    abstract public function validate();
}