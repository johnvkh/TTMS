<?php

class DeductMoneyTruckSharingModel
{


    function __construct()
    {
    }

    public function getAllDeductMoneyTruckSharing($conn)
    {
        try {
            $sql = 'SELECT * FROM `deduct_money_truck_sharing` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getDeductMoneyTruckSharing($conn, $code)
    {
        try {
            $sql = 'SELECT * FROM `deduct_money_truck_sharing` WHERE code = :code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDeductMoneyTruckSharing(
        $conn,
        $code,
        $name,
        $short_name_report,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `deduct_money_truck_sharing` (`code`, `name`, `short_name_report`, `create_by`, `create_date`) 
            VALUES (:code, :name, :short_name_report, :create_by,  sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);
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

    public function updateDeductMoneyTruckSharing(
        $conn,
        $code,
        $name,
        $short_name_report,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE deduct_money_truck_sharing SET 
                `name` = :name, 
                `short_name_report` = :short_name_report, 
                 `update_by` = :update_by,
                  `update_date` = sysdate()
                  where  `code` = :code
            ";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':short_name_report', $short_name_report);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDeductMoneyTruckSharing($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
