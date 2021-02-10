<?php

namespace Service;

class FlashBag{

    private const FLASH_MESSAGE_KEY= "flashMessage";

    /**
     * Add message to the flash bag 
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function addFlash(string $key, string $value):void
    {
        $_SESSION[self::FLASH_MESSAGE_KEY][$key]=$value;
    }

    /**
     * Get message from flash bag 
     *
     * @param string $key
     *
     * @return string
     */
    public function getFlashBag(string $key):string
    {
        $flashValue=$_SESSION[self::FLASH_MESSAGE_KEY][$key];
        unset($_SESSION[self::FLASH_MESSAGE_KEY][$key]);

        return $flashValue; 
    }   

}