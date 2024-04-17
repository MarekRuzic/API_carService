<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class RepairModel extends Database
{
  public function getRepairs($car_id)
  {
    return $this->select("SELECT * FROM repair WHERE car_id = ?", ["i", $car_id]);
  }

  public function createRepair($name, $date, $mileage, $description, $price, $part_name, $url, $car_id)
  {
    return $this->insert("INSERT INTO repair (name, date, mileage, description, price, part_name, url, car_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", ["ssdssssi", $name, $date, $mileage, $description, $price, $part_name, $url, $car_id]);
  }

  public function updateRepair($id, $name, $date, $mileage, $description, $price, $part_name, $url)
  {
    return $this->update("UPDATE repair SET name = ?, date = ?, mileage = ?, description = ?, price = ?, part_name = ?, url = ? WHERE id = ?", ["ssdssssi", $name, $date, $mileage, $description, $price, $part_name, $url, $id]);
  }

  public function deleteRepair($id)
  {
    return $this->delete("DELETE FROM repair WHERE id = ?", ["i", $id]);
  }
}
