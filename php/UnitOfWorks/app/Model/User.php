<?php
namespace App\Model;

class User extends AbstractEntity
{
    const ADMINISTRATOR_ROLE = "Administrator";
    const GUEST_ROLE         = "Guest";

    protected $allowedFields = array("id", "name", "email", "role");

    public function setId($id) {
        if (isset($this->fields["id"])) {
            throw new \BadMethodCallException(
                "The ID for this user has been set already.");
        }
        if (!is_int($id) || $id < 1) {
            throw new \InvalidArgumentException(
                "The user ID is invalid.");
        }
        $this->fields["id"] = $id;
        return $this;
    }

    public function setName($name) {
        if (strlen($name) < 2 || strlen($name) > 30) {
            throw new \InvalidArgumentException(
                "The user name is invalid.");
        }
        $this->fields["name"] = htmlspecialchars(trim($name),
            ENT_QUOTES);
        return $this;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                "The user email is invalid.");
        }
        $this->fields["email"] = $email;
        return $this;
    }

    public function setRole($role) {
        if ($role !== self::ADMINISTRATOR_ROLE &&
            $role !== self::GUEST_ROLE) {
            throw new \InvalidArgumentException(
                "The user role is invalid.");
        }
        $this->fields["role"] = $role;
        return $this;
    }
}