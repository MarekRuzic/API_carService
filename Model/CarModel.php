<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class CarModel extends Database
{

  public function getCars($limit)
  {
    return $this->select("SELECT * FROM car ORDER BY id ASC LIMIT ?", ["i", $limit]);
  }

  public function createCar($brand, $model, $manufacture, $mileage, $fuel, $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, $transmition, $user_id)
  {
    return $this->insert("INSERT INTO car (brand, model, manufacture, mileage, fuel, body, 
      color, drive4x4, doors, seats, aircondition, vin, spz, nickname, name_engine, code, 
      displacement, power, torque, oil_capacity, transmition, user_id) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", ["sssdsssississssssssssi", $brand, $model, $manufacture, $mileage, $fuel, $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, $transmition, $user_id]);
  }

  public function getCarsUser($id)
  {
    return $this->select("SELECT * FROM car WHERE user_id = ?", ["i", $id]);
  }

  public function deleteCar($id)
  {
    return $this->delete("DELETE FROM car WHERE id = ?", ["i", $id]);
  }

  public function updateCar($id, $brand, $model, $manufacture, $mileage, $fuel, $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, $transmition, $user_id)
  {
    return $this->update("UPDATE car SET 
    brand = ?,
    model = ?,
    manufacture = ?,
    mileage = ?,
    fuel = ?,
    body = ?,
    color = ?,
    drive4x4 = ?,
    doors = ?,
    seats = ?,
    aircondition = ?,
    vin = ?,
    spz = ?,
    nickname = ?,
    name_engine = ?,
    code = ?,
    displacement = ?,
    power = ?,
    torque = ?,
    oil_capacity = ?,
    transmition = ?
    WHERE id = ? AND user_id = ?;", ["sssdsssississssssssssii", $brand, $model, $manufacture, $mileage, $fuel, $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, $transmition, $id, $user_id]);
  }
}