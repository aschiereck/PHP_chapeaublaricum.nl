<?php
namespace App\Session;

class FlashMessageHandler implements IFlashMessageHandler
{

    // Message types and shortcuts
    const INFO    = 'i';
    const SUCCESS = 's';
    const WARNING = 'w';
    const ERROR   = 'e';

    // Default message type
    const defaultType = self::INFO;

    // Message types and order
    //
    // Note:  The order that message types are listed here is the same order
    // they will be printed on the screen (ie: when displaying all messages)
    //
    // This can be overridden with the display() method by manually defining
    // the message types in the order you want to display them. For example:
    //
    // $msg->display([$msg::SUCCESS, $msg::INFO, $msg::ERROR, $msg::WARNING])
    //
    protected $msgTypes = [
        self::ERROR   => 'error',
        self::WARNING => 'warning',
        self::SUCCESS => 'success',
        self::INFO    => 'info',
    ];

    // The unique ID for the session/messages (do not edit)
    protected $msgId;

    private $defaultType;

    /**
     * FlashMessageHandler constructor.
     */
    public function __construct()
    {

        // Generate a unique ID for this user and session
        $this->msgId = sha1(uniqid());

        // Create session array to hold our messages if it doesn't already exist
        if (!array_key_exists('flash_messages', $_SESSION)) $_SESSION['flash_messages'] = [];
    }

    /**
     * Add an info message
     *
     * @param  string  $message      The message text
     * @param  boolean $sticky       Sticky the message (hides the close button)
     * @return object
     *
     */
    public function info($message, $sticky=false)
    {
        return $this->add($message, self::INFO, $sticky);
    }

    /**
     * Add a success message
     *
     * @param  string  $message      The message text
     * @param  boolean $sticky       Sticky the message (hides the close button)
     * @return object
     *
     */
    public function success($message, $sticky=false)
    {
        return $this->add($message, self::SUCCESS,  $sticky);
    }

    /**
     * Add a warning message
     *
     * @param  string  $message      The message text
     * @param  boolean $sticky       Sticky the message (hides the close button)
     * @return object
     *
     */
    public function warning($message, $sticky=false)
    {
        return $this->add($message, self::WARNING,  $sticky);
    }

    /**
     * Add an error message
     *
     * @param  string  $message      The message text
     * @param  boolean $sticky       Sticky the message (hides the close button)
     * @return object
     *
     */
    public function error($message, $sticky=false)
    {
        return $this->add($message, self::ERROR, $sticky);
    }

    /**
     * Add a flash message to the session data
     *
     * @param  string  $message      The message text
     * @param  string  $type         The $msgType
     * @param  boolean $sticky       Whether or not the message is stickied
     * @return bool|object
     *
     */
    protected function add($message, $type=self::defaultType, $sticky=false)
    {

        // Make sure a message and valid type was passed
        if (!isset($message[0])) return false;
        if (strlen(trim($type)) > 1) $type = strtolower($type[0]);
        if (!array_key_exists($type, $this->msgTypes)) $type = $this->defaultType;

        // Add the message to the session data
        if (!array_key_exists( $type, $_SESSION['flash_messages'] )) $_SESSION['flash_messages'][$type] = array();
        $_SESSION['flash_messages'][$type][] = ['sticky' => $sticky, 'message' => $message];


        return $this;
    }

    /**
     * @param $type
     * @return array
     */
    public function getMessages($type)
    {
        $messages = [];

        if (isset($_SESSION['flash_messages'][$type]) && !empty($_SESSION['flash_messages'][$type]) )
        {
            foreach( $_SESSION['flash_messages'][$type] as $msgData )
            {
                $messages[] = $msgData;
            }
        }

        $this->clear($type);

        return $messages;
    }

    /**
     * Clear the messages from the session data
     *
     * @param  mixed  $types  (array) Clear all of the message types in array
     *                        (string)  Only clear the one given message type
     * @return object
     *
     */
    protected function clear($types=[])
    {
        if ((is_array($types) && empty($types)) || is_null($types) || !$types) {
            unset($_SESSION['flash_messages']);
        } elseif (!is_array($types)) {
            $types = [$types];
        }

        foreach ($types as $type) {
            unset($_SESSION['flash_messages'][$type]);
        }

        return $this;
    }
}