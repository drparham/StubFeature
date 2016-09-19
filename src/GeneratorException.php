<?php
namespace Drparham\StubFeature;

/**
 * Class GeneratorException
 * This code is lifted from Jeffrey Ways Laravel 5 Generators
 * https://github.com/laracasts/Laravel-5-Generators-Extended
 * @package Drparham\StubFeature
 */
class GeneratorException extends \Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'Could not determine what you are trying to do. Sorry! Check your feature name.';
}