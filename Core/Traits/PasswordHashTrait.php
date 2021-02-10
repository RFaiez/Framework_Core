<?php

namespace rfaiez\framework_core\Traits;

trait PasswordHashTrait
{
    /**
     * Set hashed password.
     *
     * @ORM\prePersist
     *
     * @return void
     */
    public function passwordHash(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    /**
     * Password verification.
     *
     * @param string $password
     * @param string $hash
     *
     * @return boolean
     */
    public function passwordVerify(string $password, string $hash): bool
    {
        if (password_verify($password, $hash)) {
            return true;
        }

        return false;
    }
}
