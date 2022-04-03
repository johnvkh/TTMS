<?php

class ProductTurnoverModel
{

    function __construct()
    {
    }

    public function getAllProductTurnover($conn)
    {
        try {
            $sql = "select * from tis_product_turnover order by id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getProductTurnover($conn, $accounting_period)
    {
        try {
            $sql = "select * from tis_product_turnover where accounting_period = :accounting_period";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':accounting_period', $accounting_period);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createProductTurnover(
        $conn,
        $accountingPeriod,
        $productCode,
        $productName,
        $lot,
        $unit,
        $unitCost,
        $total,
        $createBy
    ) {

        try {
            $sql = "insert into tis_product_turnover (accounting_period, product_code, product_name, lot, unit, unit_cost, total, create_by, create_date) 
            values (:accounting_period, :product_code, :product_name, :lot, :unit, :unit_cost, :total, :create_by, sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':accounting_period', $accountingPeriod);
            $stmt->bindParam(':product_code', $productCode);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':lot', $lot);
            $stmt->bindParam(':unit', $unit);
            $stmt->bindParam(':unit_cost', $unitCost);
            $stmt->bindParam(':total', $total);
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

    public function udpateProductTurnover(
        $conn,
        $accountingPeriod,
        $productCode,
        $productName,
        $lot,
        $unit,
        $unitCost,
        $total,
        $update_by
    ) {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteProductTurnover($conn, $product_type_id)
    {
        try {

            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
