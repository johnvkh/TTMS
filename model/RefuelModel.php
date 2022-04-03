<?php

class RefuelModel
{

    function __construct()
    {
    }

    public function getAllRefuel($conn, $from_date, $to_date)
    {
        try {
            $sql = "SELECT id, `perform_bill`, `license_plate`, `date_refuel`, `seller_code`, `invoice_code`, `way`, `product`, `qty`, `price`, `total_price`, `create_by`, `create_date`, `update_by`, `update_date` FROM `refuel_cost` ";
            if (($from_date != null || $from_date != "") && ($to_date != null || $to_date != "")) {
                $sql .= " WHERE create_date BETWEEN date_format(:from_date, '%Y-%m-%d') AND date_format(:to_date, '%Y-%m-%d') ";
            }
            $sql .= " ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            if (($from_date != null || $from_date != "") && ($to_date != null || $to_date != "")) {
                $stmt->bindParam(':from_date', $from_date);
                $stmt->bindParam(':to_date', $to_date);
            }
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRefuel($conn, $performBill, $licensePlate)
    {
        try {
            $sql = "SELECT id, `perform_bill`, `license_plate`, `date_refuel`, `seller_code`, `invoice_code`, `way`, `product`, `qty`, `price`, `total_price`, `create_by`, `create_date`, `update_by`, `update_date` FROM `refuel_cost` WHERE `perform_bill` = :perform_bill and `license_plate` = :license_plate ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill', $performBill);
            $stmt->bindParam(':license_plate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getPerformBill($conn, $performBill)
    {

        try {
            $sql = "SELECT perform_bill FROM refuel_cost WHERE perform_bill = :perform_bill";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill', $performBill);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRefuel(
        $conn,
        $performBill,
        $licensePlate,
        $refuelDate,
        $sellerCode,
        $invoice,
        $way,
        $productCode,
        $dieselPerL,
        $price,
        $totalPrice,
        $createBy
    ) {


        try {
            $sql = "INSERT INTO `refuel_cost`(
                `perform_bill`,
                `license_plate`,
                `date_refuel`,
                `seller_code`,
                `invoice_code`,
                `way`,
                `product`,
                `qty`,
                `price`,
                `total_price`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :perform_bill,
                :license_plate,
                :date_refuel,
                :seller_code,
                :invoice_code,
                :way,
                :product,
                :qty,
                :price,
                :total_price,
                :create_by,
                SYSDATE())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill', $performBill);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date_refuel', $refuelDate);
            $stmt->bindParam(':seller_code', $sellerCode);
            $stmt->bindParam(':invoice_code', $invoice);
            $stmt->bindParam(':way', $way);
            $stmt->bindParam(':product', $productCode);
            $stmt->bindParam(':qty', $dieselPerL);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateRefuel(
        $conn,
        $performBill,
        $licensePlate,
        $refuelDate,
        $sellerCode,
        $invoice,
        $way,
        $productCode,
        $qty,
        $price,
        $totalPrice,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
                `refuel_cost`
            SET
                `date_refuel` = :date_refuel,
                `seller_code` = :seller_code,
                `invoice_code` = :invoice_code,
                `way` = :way,
                `product` = :product,
                `qty` = :qty,
                `price` = :price,
                `total_price` = :total_price,
                `update_by` = :update_by,
                `update_date` = sysdate()
            WHERE
                `perform_bill` = :perform_bill AND `license_plate` = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill', $performBill);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date_refuel', $refuelDate);
            $stmt->bindParam(':seller_code', $sellerCode);
            $stmt->bindParam(':invoice_code', $invoice);
            $stmt->bindParam(':way', $way);
            $stmt->bindParam(':product', $productCode);
            $stmt->bindParam(':qty', $qty);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':total_price', $totalPrice);
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

    public function deleteRefuel($conn, $performBill)
    {
        try {
            $sql = "delete from refuel_cost where perform_bill = :perform_bill";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill', $performBill);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
