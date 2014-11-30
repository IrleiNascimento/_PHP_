<?php
/**
 *
 *
 * @author Irlei
 */
final class Error {

    private $source;
    private $message;

    function __construct($source, $message) {
        $this->source = $source;
        $this->message = $message;
    }

    public function getSource() {
        return $this->source;
    }

    public function getMessage() {
        return $this->message;
    }

}

?>
