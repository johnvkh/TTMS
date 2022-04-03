<?php

class StoreModel {

    function __construct() {
        
    }

    public function getAllStore($conn) {
        try {
            $sql = 'SELECT * FROM `store_name` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function getStore($conn, $id) {
        try {
            $sql = 'SELECT * FROM store_name WHERE id  = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function getStoreName($conn, $fullname) {
        try {
            $sql = 'SELECT * FROM `store_name` WHERE fullname  = :fullname';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);

            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function createNewStore(
    $conn, $fullname, $short_name_report, $createBy
    ) {

        try {
            $sql = "
                insert into store_name(
                    fullname, short_name_report, create_by, create_date
                ) values (
                    :fullname, :short_name_report, :create_by, sysdate()
                )
                
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':short_name_report', $short_name_report);
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

    public function updateStore(
    $conn, $id, $fullname, $short_name_report, $updateBy
    ) {
        try {
            $sql = "
                UPDATE store_name SET 
                fullname = :fullname, 
                short_name_report = :short_name_report, 
                update_by = :update_by, 
                update_date = sysdate()
                where  id = :id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':short_name_report', $short_name_report);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function deleteStore($conn, $truckTypeCode) {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

}
