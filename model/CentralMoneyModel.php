<?php

class CentralMoneyModel
{

    function __construct()
    {
    }

    public function getAllCentralMoney($conn)
    {
        try {
            $sql = "select * from central_money order by id desc";
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

    public function getCentralMoney($conn, $cm_code)
    {
        try {
            $sql = "select * from central_money WHERE cm_code = :cm_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cm_code', $cm_code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewCentralMoney(
        $conn,
        $cmDate,
        $cmCode,
        $name,
        $price,
        $numberOfTruck,
        $avg,
        $totalPrice,
        $totalAvg,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `central_money`(
                `cm_date`,
                `cm_code`,
                `name`,
                `price`,
                `number_truck`,
                `avg`,
                `total_price`,
                `total_avg`,
                `create_by`,
                `create_date`
            )
            VALUES(
                sysdate(),
                :cm_code,
                :name,
                :price,
                :number_truck,
                :avg,
                :total_price,
                :total_avg,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':cm_date', $cmDate);
            $stmt->bindParam(':cm_code', $cmCode);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':number_truck', $numberOfTruck);
            $stmt->bindParam(':avg', $avg);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':total_avg', $totalAvg);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function updateCentralMoney(
        $conn,
        $cmDate,
        $cmCode,
        $name,
        $price,
        $numberOfTruck,
        $avg,
        $totalPrice,
        $totalAvg,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `central_money`
        SET
            `cm_date` = sysdate(),
            `name` = :name,
            `price` = :price,
            `number_truck` = :number_truck,
            `avg` = :avg,
            `total_price` = :total_price,
            `total_avg` = :total_avg,
            `update_by` = :update_by,
            `update_date` = sysdate()
        where `cm_code` = :cm_code
            ";
            $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':cm_date', $cmDate);
            $stmt->bindParam(':cm_code', $cmCode);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':number_truck', $numberOfTruck);
            $stmt->bindParam(':avg', $avg);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':total_avg', $totalAvg);
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

    public function deleteCentralMoney($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
