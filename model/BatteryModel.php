<?php

class BatteryModel {

    function __construct() {
        
    }

    public function getAllBattery($conn) {
        $sql = "select * from battery order by id desc";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getBattery($conn, $batteryId) {
        $sql = "select * from color where id = :battery_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":battery_id", $batteryId);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getBatteryByName($conn, $batteryName) {
        $sql = "select * from battery where battery_name like '%" . $batteryName . "%' ";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewBattery($conn, $batteryName, $createBy) {
        $sql = "insert into battery (battery_name, create_by, create_date) values (:battery_name, :create_by, sysdate())";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":battery_name", $batteryName);
            $stmt->bindParam(":create_by", $createBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateBattery($conn, $batteryId, $batteryName, $updateBy) {
        $sql = "update battery set battery_name = :battery_name, update_by = :update_by, update_date = sysdate() where id = :battery_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":battery_id", $batteryId);
            $stmt->bindParam(":battery_name", $batteryName);
            $stmt->bindParam(":update_by", $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function deleteBattery($conn, $batteryId) {
        $sql = "DELETE FROM `battery` WHERE id='.$batteryId.' ";
        try {
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkBatteryName($conn, $batteryName) {
        $sql = "select * from battery where battery_name = :battery_name";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":battery_name", $batteryName);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkBatteryId($conn, $batteryId) {
        $sql = "select * from battery where id = :battery_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":battery_id", $batteryId);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

}
