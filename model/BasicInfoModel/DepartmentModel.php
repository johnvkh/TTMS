<?php

class DepartmentModel {

    function __construct() {
        
    }

    public function getAllDepartment($conn) {
        $SQL = "SELECT * FROM Department where STATUS=1";
        try {
            $stmt = $conn->prepare($SQL);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getAllDepartmentById($conn, $departmentId) {
        $SQL = "SELECT * FROM Department where STATUS=1 and Department_id='" . $departmentId . "'";
        try {
            $stmt = $conn->prepare($SQL);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkDepartmentByName($conn, $departmentName) {
        $SQL = "SELECT * FROM Department where STATUS=1 and Department_name= '" . $departmentName . "' ";
        try {
            $stmt = $conn->prepare($SQL);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return FALSE;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function insertDepartment($conn, $departmentName, $createBy) {
        $SQL = "insert into Department(Department_name,STATUS,CREATED_DATE,CREATED_BY) values ('" . $departmentName . "',1,sysdate(),'" . $createBy . "') ";
        try {
            $stmt = $conn->prepare($SQL);
            if ($stmt->execute()) {
                return true;
            }
            return FALSE;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateDepartment($conn, $departmentName, $updateBy) {
        $SQL = "update Department set Department_name='" . $departmentName . "', MODIFIED_DATE=sysdate(), MODIFIED_BY='" . $updateBy . "'";
        try {
            $stmt = $conn->prepare($SQL);
            if ($stmt->execute()) {
                return true;
            }
            return FALSE;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
