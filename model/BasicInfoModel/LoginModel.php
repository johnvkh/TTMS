<?php

class LoginModel {

    function __construct() {
        
    }

    public function login($conn, $staffCode) {
        $SQL_SELECT_USERS_INFO = "SELECT * FROM STAFF WHERE STAFF_CODE = '" . $staffCode . "' ";
        try {
            $stmt = $conn->prepare($SQL_SELECT_USERS_INFO);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function count_auth_log($conn, $staffCode) {
        try {
            $query = "select * from LOGIN_LOG where STAFF_CODE=:STAFF_CODE";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->execute();
            $count = $stmt->rowCount();
            return $count;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function lockAccount($conn, $staffCode) {
        try {
            $query = "UPDATE STAFF SET STATUS=2, MODIFIED_DATE=sysdate(),MODIFIED_BY=:STAFF_CODE WHERE STAFF_CODE=:STAFF_CODE";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->execute();
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function insert_auth_log($conn, $staffCode, $actionCode, $actionNodeId, $pError_code, $pError_desc, $count) {
        try {
            $query = "insert into LOGIN_LOG (STAFF_CODE, DATE_CREATE, ACTION_NODE, ERROR_CODE, ERROR_DESC, ACTION_CODE, LOGIN_TIME)
                      values (:STAFF_CODE, sysdate(), :ACTION_NODE, :ERROR_CODE, :ERROR_DESC, :ACTION_CODE, :LOGIN_TIME)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->bindParam(":ACTION_NODE", $actionNodeId);
            $stmt->bindParam(":ERROR_CODE", $pError_code);
            $stmt->bindParam(":ERROR_DESC", $pError_desc);
            $stmt->bindParam(":ACTION_CODE", $actionCode);
            $stmt->bindParam(":LOGIN_TIME", $count);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
    
    public function deleteLoginlog($conn, $staffCode) {
        try {
            $query = "delete from LOGIN_LOG WHERE STAFF_CODE=:STAFF_CODE";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->execute();
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
