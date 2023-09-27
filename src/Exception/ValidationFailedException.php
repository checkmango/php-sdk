<?php

namespace Checkmango\Exception;

class ValidationFailedException extends ErrorException
{
    public $errors = [];

    /**
     * Add an array of errors to the exception.
     *
     * @return $this
     */
    public function withErrors(array $errors = [])
    {
        $this->errors = [];

        return $this;
    }
}
