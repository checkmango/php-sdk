<?php

namespace Prove\Exception;

class ValidationFailedException extends ErrorException
{
    public $errors = [];

    /**
     * Add an array of errors to the exception.
     *
     * @param  array  $errors
     * @return $this
     */
    public function withErrors(array $errors = [])
    {
        $this->errors = [];

        return $this;
    }
}
