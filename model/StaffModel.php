<?php

class StaffModel {

    function __construct() {
        
    }

    public function getStaff($conn, $staffCode, $departmentId, $branchId) {
        try {
            $sql = "select * from STAFF where status=1 ";
            if (!empty($staffCode)) {
                $sql .= " and STAFF_CODE=:STAFF_CODE";
            }
            if (!empty($departmentId)) {
                $sql .= " and DEPARTMENT_ID=:DEPARTMENT_ID";
            }
            if (!empty($branchId)) {
                $sql .= " and BRANCH_ID=:DEPARTMENT_ID";
            }
            $stmt = $conn->prepare($sql);
            if (!empty($staffCode)) {
                $stmt->bindParam(":STAFF_CODE", $staffCode);
            }
            if (!empty($departmentId)) {
                $stmt->bindParam(":DEPARTMENT_ID", $departmentId);
            }
            if (!empty($branchId)) {
                $stmt->bindParam(":BRANCH_ID", $branchId);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkDuplicateStaffCodeProcess($conn, $staffCode) {
        try {
            $sql = "SELECT STAFF_CODE FROM STAFF WHERE STAFF_CODE =:STAFF_CODE";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":STAFF_CODE", $staffCode);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function insertStaffProcess($conn, $STAFF_CODE, $FULL_NAME, $EMAIL, $MSISDN, $PASSWORD, $ROLE_ID, $GENDER, $BIRTH_DAY, $ADDRESS, $DEPARTMENT_ID, $BRANCH_ID, $CREATED_BY) {
        $sql = "INSERT INTO STAFF( 
		STAFF_CODE, 
		FULL_NAME, 
		EMAIL, 
		MSISDN, 
		PASSWORD, 
		ROLE_ID, 
		STATUS, 
		GENDER, 
		BIRTH_DAY, 
		ADDRESS, 
		CREATED_DATE, 
		CREATED_BY, 
		DEPARTMENT_ID, 
		BRANCH_ID
            ) VALUES(
		:STAFF_CODE, 
		:FULL_NAME, 
		:EMAIL, 
		:MSISDN, 
		:PASSWORD, 
		:ROLE_ID, 
		1, 
		:GENDER, 
		:BIRTH_DAY, 
		:ADDRESS, 
		sysdate(), 
		:CREATED_BY, 
		:DEPARTMENT_ID, 
		:BRANCH_ID
	)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":STAFF_CODE", $STAFF_CODE);
            $stmt->bindParam(":FULL_NAME", $FULL_NAME);
            $stmt->bindParam(":EMAIL", $EMAIL);
            $stmt->bindParam(":MSISDN", $MSISDN);
            $stmt->bindParam(":PASSWORD", $PASSWORD);
            $stmt->bindParam(":ROLE_ID", $ROLE_ID);
            $stmt->bindParam(":GENDER", $GENDER);
            $stmt->bindParam(":BIRTH_DAY", $BIRTH_DAY);
            $stmt->bindParam(":ADDRESS", $ADDRESS);
            $stmt->bindParam(":CREATED_BY", $CREATED_BY);
            $stmt->bindParam(":DEPARTMENT_ID", $DEPARTMENT_ID);
            $stmt->bindParam(":BRANCH_ID", $BRANCH_ID);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
        return false;
    }

    public function updateStaff($conn, $STAFF_CODE, $FULL_NAME, $EMAIL, $MSISDN, $ROLE_ID, $GENDER, $BIRTH_DAY, $ADDRESS, $DEPARTMENT_ID, $BRANCH_ID, $MODIFIED_BY) {
        try {
            $sql = "update STAFF set 
		FULL_NAME=:FULL_NAME, 
		EMAIL=:EMAIL,
		MSISDN=:MSISDN,
		ROLE_ID=:ROLE_ID,
		GENDER=:GENDER,
		BIRTH_DAY=:BIRTH_DAY,
		ADDRESS=:ADDRESS,
		DEPARTMENT_ID=:DEPARTMENT_ID,
		BRANCH_ID=:BRANCH_ID,
		MODIFIED_DATE=sysdate(),
		MODIFIED_BY=:MODIFIED_BY
	where STAFF_CODE=:STAFF_CODE";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':FULL_NAME', $FULL_NAME);
            $stmt->bindParam(':EMAIL', $EMAIL);
            $stmt->bindParam(':MSISDN', $MSISDN);
            $stmt->bindParam(':ROLE_ID', $ROLE_ID);
            $stmt->bindParam(':GENDER', $GENDER);
            $stmt->bindParam(':BIRTH_DAY', $BIRTH_DAY);
            $stmt->bindParam(':ADDRESS', $ADDRESS);
            $stmt->bindParam(':DEPARTMENT_ID', $DEPARTMENT_ID);
            $stmt->bindParam(':BRANCH_ID', $BRANCH_ID);
            $stmt->bindParam(':MODIFIED_BY', $MODIFIED_BY);
            $stmt->bindParam(':STAFF_CODE', $STAFF_CODE);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteStaff($conn, $STAFF_CODE, $MODIFIED_BY) {
        try {
            $sql = "update STAFF set STATUS=2, MODIFIED_DATE=sysdate(), MODIFIED_BY=:MODIFIED_BY
                    where STAFF_CODE=:STAFF_CODE";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':MODIFIED_BY', $MODIFIED_BY);
            $stmt->bindParam(':STAFF_CODE', $STAFF_CODE);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function staffResetPassword($conn, $STAFF_CODE, $oldPassword, $newPassword, $MODIFIED_BY) {
        try {
            $SQL_GET_STAFF = "SELECT * FROM STAFF WHERE STAFF_CODE = '" . $STAFF_CODE . "' ";
            $stmtGetInfo = $conn->prepare($SQL_GET_STAFF);
            if ($stmtGetInfo->execute()) {
                if ($stmtGetInfo->rowCount() > 0) {
                    if ($row = $stmtGetInfo->fetch(PDO::FETCH_ASSOC)) {
                        $password = $row["PASSWORD"];
                        if ($oldPassword == $password) {
                            $sql = "update STAFF set  PASSWORD=:PASSWORD,MODIFIED_DATE=sysdate(),MODIFIED_BY=:MODIFIED_BY where STAFF_CODE=:STAFF_CODE";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':PASSWORD', $newPassword);
                            $stmt->bindParam(':MODIFIED_BY', $MODIFIED_BY);
                            $stmt->bindParam(':STAFF_CODE', $STAFF_CODE);
                            if ($stmt->execute()) {
                                return true;
                            }
                            return false;
                        }
                        return false;
                    }
                    return false;
                }
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
