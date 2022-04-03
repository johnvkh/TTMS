<?php

class DeliveryLocationModel {

    function __construct() {
        
    }

    public function getAllDeliveryLocation($conn) {
        try {
            $sql = 'SELECT * FROM `delivery_location` ORDER BY create_date DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getDeliveryLocationByLocationCode($conn, $locationCode) {
        try {
            $sql = 'SELECT * FROM `delivery_location` WHERE location_code = :location_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':location_code', $locationCode);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDeliveryLocation($conn, $locationCode, $locationName, $createBy) {

        try {
            $sql = "INSERT INTO `delivery_location` (`id`, `location_code`, `location_name`, `create_by`, `create_date`) 
            VALUES (NULL, :location_code, :location_name, :create_by, sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':location_code', $locationCode);
            $stmt->bindParam(':location_name', $locationName);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateDeliveryLocation($conn, $locationCode, $locationName, $updateBy) {
        try {
            $sql = "UPDATE `delivery_location` SET  `location_name` = :location_name, update_by = :update_by, update_date = sysdate()  WHERE location_code = :location_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':location_name', $locationName);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':location_code', $locationCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDeliveryLocation($conn, $truckTypeCode) {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
