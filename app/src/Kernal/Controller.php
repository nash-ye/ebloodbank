<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
abstract class Controller
{
    /**
     * @since 1.0
     */
    public function __construct()
    {
        $this->processRequest();
        $this->outputResponse();
    }

    /**
     * @return void
     * @since 1.0
     */
    abstract public function processRequest();

    /**
     * @return void
     * @since 1.0
     */
    abstract public function outputResponse();
}
