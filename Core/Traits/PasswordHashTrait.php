<?php

namespace Traits;

trait PasswordHashTrait{

    /**
     * hash password
     * 
     * @ORM\prePersist
     * @return void
     */
    public function passwordHash()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    /**
     * password verification
     *
     * @param string $password
     * @param string $hash
     *
     * @return boolean
     */
    public function passwordVerify(string $password, string $hash):bool
    {
        if (password_verify($password, $hash)) {
            return true;
        } 
        return false;
    }
}