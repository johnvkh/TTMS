<?php

class DirectExpenseModel
{


    function __construct()
    {
    }

    public function getAllDirectExpense($conn)
    {
        try {
            $sql = 'SELECT * FROM `direct_expense` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getDirectExpense($conn, $code)
    {
        try {
            $sql = 'SELECT * FROM `direct_expense` WHERE code = :code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDirectExpense(
        $conn,
        $code,
        $name_per_time,
        $short_name_report,
        $is_pay_to_driver,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `direct_expense` (`code`, `name_per_time`, `short_name_report`, `is_pay_to_driver`,`create_by`, `create_date`) 
            VALUES (:code, :name_per_time, :short_name_report, :is_pay_to_driver, :create_by,  sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name_per_time', $name_per_time);
            $stmt->bindParam(':short_name_report', $short_name_report);
            $stmt->bindParam(':is_pay_to_driver', $is_pay_to_driver);
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

    public function updateDirectExpense(
        $conn,
        $code,
        $name_per_time,
        $short_name_report,
        $is_pay_to_driver,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE direct_expense SET 
                `name_per_time` = :name_per_time, 
                `short_name_report` = :short_name_report, 
                `is_pay_to_driver` = :is_pay_to_driver,
                `update_by` = :update_by, 
                `update_date` = sysdate()
                where code = :code
            ";
            $stmt = $conn->prepare($sql);


            $stmt->bindParam(':name_per_time', $name_per_time);
            $stmt->bindParam(':short_name_report', $short_name_report);
            $stmt->bindParam(':is_pay_to_driver', $is_pay_to_driver);
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

    public function deleteDirectExpense($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
