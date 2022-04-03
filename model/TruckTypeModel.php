<?php

class TruckTypeModel {

    function __construct() {
        
    }

    public function getAllTruckType($conn) {
        try {
            $sql = 'select * from truck_type order by create_date desc';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return "00";
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckTypeByTruckCode($conn, $truckCode) {
        try {
            $sql = 'select * from truck_type where truck_type_code = :truck_type_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_type_code', $truckCode);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTruckTypeCode(
            $conn,
            $actionNodeId,
            $truckTypeCode,
            $truckTypeName,
            $tireLifeKm,
            $tireLifeDay,
            $countWheels,
            $createBy
    ) {
        try {
            $sql = "INSERT INTO truck_type (`truck_type_code`, `truck_type_name`, `tire_life_km`, `tire_life_day`, `number_of_wheels`, `action_node_id`, `create_by`, `create_date`) ";
            $sql .= "VALUES (:truck_type_code, :truck_type_name, :tire_life_km, :tire_life_day, :number_of_wheels, :action_node_id, :create_by, sysdate() )";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_type_code', $truckTypeCode);
            $stmt->bindParam(':truck_type_name', $truckTypeName);
            $stmt->bindParam(':tire_life_km', $tireLifeKm);
            $stmt->bindParam(':tire_life_day', $tireLifeDay);
            $stmt->bindParam(':number_of_wheels', $countWheels);
            $stmt->bindParam(':action_node_id', $actionNodeId);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateTruckTypeByTruckTypeCode(
            $conn,
            $actionNodeId,
            $truckTypeCode,
            $truckTypeName,
            $tireLifeKm,
            $tireLifeDay,
            $countWheels,
            $udateBy
    ) {
        try {
            $sql = 'update `truck_type` set'
                    . ' `truck_type_name` = :truck_type_name,'
                    . ' `tire_life_km` = :tire_life_km,'
                    . ' `tire_life_day` = :tire_life_day,'
                    . ' `number_of_wheels` = :number_of_wheels,'
                    . ' `action_node_id` = :action_node_id,'
                    . ' `update_by` = :update_by,'
                    . ' `update_date` = sysdate() '
                    . ' where truck_type_code = :truck_type_code';
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':truck_type_name', $truckTypeName);
            $stmt->bindParam(':tire_life_km', $tireLifeKm);
            $stmt->bindParam(':tire_life_day', $tireLifeDay);
            $stmt->bindParam(':number_of_wheels', $countWheels);
            $stmt->bindParam(':action_node_id', $actionNodeId);
            $stmt->bindParam(':update_by', $udateBy);
            $stmt->bindParam(':truck_type_code', $truckTypeCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteTruckType($conn, $truckTypeCode) {
        try {
            $sql = 'delete from truck_type where truck_type_code = :truck_type_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_type_code', $truckTypeCode);
            $stmt->execute();
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
