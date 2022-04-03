<?php

class TruckBrandModel {

    function __construct() {
        
    }

    public function getAllTruckBrand($conn, $name) {
        try {
            $sql = 'SELECT
            a.truck_brand_id,
            a.truck_brand_name,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            truck_brand a ';

            if ((is_string(trim($name))) && !empty(trim($name))) {
                $sql .= ' WHERE truck_brand_name = :truck_brand_name ';
            }

            $sql .= ' ORDER BY
            a.truck_brand_id DESC';

            $stmt = $conn->prepare($sql);
            if ((is_string(trim($name))) && !empty(trim($name))) {
                $stmt->bindParam('truck_brand_name', $name);
            }
            $stmt->execute();
            if ($stmt->execute()) {
                return $stmt;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkTruckBrandId($conn, $truck_brand_id) {
        try {
            $sql = 'SELECT
            a.truck_brand_id,
            a.truck_brand_name,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            truck_brand a 
        WHERE 
            a.truck_brand_id = :truck_brand_id
        ORDER BY 
            a.create_date DESC';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
            if ($stmt->execute()) {
                return $stmt;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function geTruckBrandByName($conn, $truck_brand_name) {
        try {
            $sql = "SELECT
            a.truck_brand_id,
            a.truck_brand_name,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            truck_brand a 
        WHERE 
            a.truck_brand_name like '%" . $truck_brand_name . "%'
        ORDER BY 
            a.create_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_brand_name', $truck_brand_name);
            if ($stmt->execute()) {
                return $stmt;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTruckBrand(
            $conn,
            $truck_brand_name,
            $createBy
    ) {
        $sql = "INSERT INTO `truck_brand` 
        (
            `truck_brand_name`,
            `create_by`, 
            `create_date` 
        ) 
        VALUES (
            :truck_brand_name, :create_by, sysdate()
        )";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':truck_brand_name', $truck_brand_name);
        $stmt->bindParam(':create_by', $createBy);
        if ($stmt->execute()) {
            return true;
        }
        return false;
        try {
            
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateTruckBrandById(
            $conn,
            $truck_brand_id,
            $truck_brand_name,
            $updateBy
    ) {
        try {

            $sql = "UPDATE `truck_brand` SET 
            `truck_brand_name` = :truck_brand_name,
            `update_by` = :update_by, 
            `update_date` = sysdate()  WHERE  `truck_brand_id` =  :truck_brand_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_brand_name', $truck_brand_name);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteTruckType($conn, $truck_brand_id) {
        try {
            $sql = 'DELETE FROM truck_brand WHERE truck_brand_id = :truck_brand_id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
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
