<?php

class MonthlyAdditionalCostModel
{

    function __construct()
    {
    }

    public function getAllMonthlyAdditionalCost($conn)
    {
        try {
            $sql = "SELECT
                    `id`,
                    `date`,
                    `licensePlate`,
                    `driver`,
                    `cost_repairing_in`,
                    `const_repairing_out`,
                    `create_by`,
                    `create_date`,
                    `update_by`,
                    `update_date`
                FROM
                    `monthly_additional_cost`
                ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getMonthlyAdditionalCost($conn, $licensePlate)
    {
        try {
            $sql = "SELECT
                    `id`,
                    `date`,
                    `licensePlate`,
                    `driver`,
                    `cost_repairing_in`,
                    `const_repairing_out`,
                    `create_by`,
                    `create_date`,
                    `update_by`,
                    `update_date`
                FROM
                    `monthly_additional_cost`
                WHERE `licensePlate` = :licensePlate
                ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':licensePlate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportCostOfTheMonth($conn, $from_date, $to_date)
    {
        try {
            $sql = "SELECT
                    `id`,
                    `date`,
                    `licensePlate`,
                    `driver`,
                    `cost_repairing_in`,
                    `const_repairing_out`,
                    `create_by`,
                    `create_date`,
                    `update_by`,
                    `update_date`
                FROM
                    `monthly_additional_cost`
                WHERE `licensePlate` = :licensePlate
                ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':licensePlate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewMonthlyAdditionalCost(
        $conn,
        $date,
        $licensePlate,
        $driver,
        $costRepairingIn,
        $costRepairingOut,
        $createBy
    ) {


        try {
            $sql = "INSERT INTO `monthly_additional_cost`(
                `date`,
                `licensePlate`,
                `driver`,
                `cost_repairing_in`,
                `const_repairing_out`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :date,
                :licensePlate,
                :driver,
                :cost_repairing_in,
                :const_repairing_out,
                :create_by,
                SYSDATE())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':licensePlate', $licensePlate);
            $stmt->bindParam(':driver', $driver);
            $stmt->bindParam(':cost_repairing_in', $costRepairingIn);
            $stmt->bindParam(':const_repairing_out', $costRepairingOut);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateMonthlyAdditionalCost(
        $conn,
        $date,
        $licensePlate,
        $driver,
        $costRepairingIn,
        $costRepairingOut,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `monthly_additional_cost`
        SET
            `date` = :date,
            `driver` = :driver,
            `cost_repairing_in` = :cost_repairing_in,
            `const_repairing_out` = :const_repairing_out,
            `update_by` = :update_by,
            `update_date` = SYSDATE()
        WHERE
            `licensePlate` = :licensePlate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':driver', $driver);
            $stmt->bindParam(':licensePlate', $licensePlate);
            $stmt->bindParam(':cost_repairing_in', $costRepairingIn);
            $stmt->bindParam(':const_repairing_out', $costRepairingOut);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteMonthlyAdditionalCost($conn)
    {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
