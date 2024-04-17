<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{

  public function getUsers($limit)
  {
    return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", ["i", $limit]);
  }

  public function createUser($jmeno, $prijmeni, $email, $heslo)
  {
    return $this->insert("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)", ["ssss", $jmeno, $prijmeni, $email, $heslo]);
  }

  public function deleteUser($id)
  {      
    return $this->delete("DELETE FROM users WHERE id = ?", ["i", $id]);
  }

  public function getUser($id)
  {
    return $this->select("SELECT * FROM users WHERE id = ?", ["i", $id]);
  }

  public function getUserByEmail($email)
  {
    return $this->select("SELECT * FROM users WHERE email = ?", ["s", $email]);
  }

  public function updateUser($id, $jmeno, $prijmeni, $email)
  {
    return $this->update("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?", ["sssi", $jmeno, $prijmeni, $email, $id]);
  }
  
  public function updateUserPassword($id, $password)
  {
    return $this->update("UPDATE users SET password = ? WHERE id = ?", ["si", $password, $id]);
  }

}
