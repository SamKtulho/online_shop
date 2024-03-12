<?php

declare(strict_types=1);

namespace Support\Flash;

use Illuminate\Contracts\Session\Session;

class Flash
{

    const MESSAGE_KEY = "shop_flash_message";
    const MESSAGE_CLASS_KEY = "shop_flash_class";

    /**
     * @param Session $session
     */
    public function __construct(protected Session $session)
    {

    }

    /**
     * @return FlashMessage|null
     */
    public function get(): ?FlashMessage
    {
        $message = $this->session->get(self::MESSAGE_KEY);

        if (!$message) {
            return null;
        }

        return new FlashMessage(
            $message,
            $this->session->get(self::MESSAGE_CLASS_KEY)
        );
    }

    /**
     * @param string $message
     * @return void
     */
    public function info(string $message): void
    {
        $this->flash($message, config('flash.info', ''));
    }

    /**
     * @param string $message
     * @return void
     */
    public function alert(string $message): void
    {
        $this->flash($message, config('flash.alert', ''));
    }

    /**
     * @param string $message
     * @param string $class
     * @return void
     */
    private function flash(string $message, string $class): void
    {
        $this->session->flash(self::MESSAGE_KEY, $message);
        $this->session->flash(self::MESSAGE_CLASS_KEY, $class);
    }
}
