<?php

namespace Plugo\Services\Flash;

use DateTime;
use Exception;

readonly class Toast
{
    public const TYPE_ERROR = 'error';
    public const TYPE_SUCCESS = 'success';

    /**
     * @param string $type
     * @param string $message
     * @throws Exception
     */
    public function __construct(
        private string $type,
        private string $message
    )
    {
        if ($type !== self::TYPE_ERROR && $type !== self::TYPE_SUCCESS) {
            throw new Exception("Toast type $this->type isn't from the constants list.");
        }

        $_SESSION['previousPage'] = [
            'requestUri' => basename($_SERVER['REQUEST_URI']),
            'dateTime' => new DateTime()
        ];

        $_SESSION['toast'] = $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
