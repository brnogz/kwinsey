<?php namespace kwinsey;
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 26.03.2016
 * Time: 22:15
 */


abstract class Controller
{
    /**
     * @var Input $input
     */
    protected $input;

    public function __construct()
    {
        $this->input = Input::getInstance();
    }
}