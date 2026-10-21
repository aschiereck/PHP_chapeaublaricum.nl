<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 18-10-2016
 * Time: 23:28
 */
namespace App\Session;

interface IFlashMessageHandler
{
    /**
     * Add an info message
     *
     * @param  string $message The message text
     * @param  boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function info($message, $sticky = false);

    /**
     * Add a success message
     *
     * @param  string $message The message text
     * @param  boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function success($message, $sticky = false);

    /**
     * Add a warning message
     *
     * @param  string $message The message text
     * @param  boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function warning($message, $sticky = false);

    /**
     * Add an error message
     *
     * @param  string $message The message text
     * @param  boolean $sticky Sticky the message (hides the close button)
     * @return object
     *
     */
    public function error($message, $sticky = false);

    /**
     * @param $type
     * @return array
     */
    public function getMessages($type);
}