<?php

class IndirectExpenseModel
{


    function __construct()
    {
    }

    public function getAllIndirectExpense($conn)
    {
        try {
            $sql = 'SELECT * FROM `indirect_expense` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getIndirectExpense($conn, $indirect_expense_code)
    {
        try {
            $sql = 'SELECT * FROM `indirect_expense` WHERE indirect_expense_code = :indirect_expense_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':indirect_expense_code', $indirect_expense_code);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewIndirectExpense(
        $conn,
        $indirect_expense_code,
        $indirect_expense_name,
        $short_name_report,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `indirect_expense` (`indirect_expense_code`, `indirect_expense_name`, `short_name_report`, `create_by`, `create_date`) 
            VALUES (:indirect_expense_code, :indirect_expense_name, :short_name_report, :create_by,  sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':indirect_expense_code', $indirect_expense_code);
            $stmt->bindParam(':indirect_expense_name', $indirect_expense_name);
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

    public function updateIndirectExpense(
        $conn,
        $indirect_expense_code,
        $indirect_expense_name,
        $short_name_report,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE indirect_expense SET 
                `indirect_expense_name` = :indirect_expense_name, 
                `short_name_report` = :short_name_report,
                 `update_by` = :update_by,
                  `update_date` = sysdate()
                  where  `indirect_expense_code` = :indirect_expense_code
            ";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':indirect_expense_name', $indirect_expense_name);
            $stmt->bindParam(':short_name_report', $short_name_report);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':indirect_expense_code', $indirect_expense_code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteIndirectExpense($conn)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
