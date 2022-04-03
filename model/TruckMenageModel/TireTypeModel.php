<?php

class TireTypeModel {

    function __construct() {
        
    }

    public function getAllTireType($conn) {
        try {
            $sql = "SELECT * FROM tire_type where status=1";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function InsertTireType($conn, $tireTypeName) {
        try {
            $sql = "INSERT INTO tire_type(tire_type_name, status) VALUES (:tire_type_name,1)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_type_name', $tireTypeName);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateTireType($conn, $tireTypeId, $tireTypeName, $status) {
        try {
            $sql = "UPDATE tire_type SET tire_type_name=:tire_type_name,status=:status WHERE tire_type_id=:tire_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_type_name', $tireTypeName);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':tire_type_id', $tireTypeId);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
    
     public function deleteTireType($conn, $tireTypeId) {
        try {
            $sql = "UPDATE `tire_type` SET status=0 WHERE tire_type_id=:tire_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_type_id', $tireTypeId);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
