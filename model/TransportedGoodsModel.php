<?php

class TransportedGoodsModel
{


    function __construct()
    {
    }

    public function getAllTransportedGoodsModel($conn)
    {
        try {
            $sql = 'SELECT * FROM `transported_goods` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getTransportedGoodsModel($conn, $product_code)
    {
        try {
            $sql = 'SELECT * FROM `transported_goods` WHERE product_code = :product_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $product_code);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTransportedGoodsModel(
        $conn,
        $product_code,
        $product_name,
        $short_name,
        $product_unit,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `transported_goods` (`id`, `product_code`, `product_name`, `short_name`, `product_unit`, `create_by`, `create_date`) 
            VALUES (NULL, :product_code, :product_name, :short_name, :product_unit, :create_by,  sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $product_code);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':short_name', $short_name);
            $stmt->bindParam(':product_unit', $product_unit);
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

    public function updateTransportedGoodsModel(
        $conn,
        $product_code,
        $product_name,
        $short_name,
        $product_unit,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE transported_goods SET 
                `product_name` = :product_name, 
                `short_name` = :short_name, 
                `product_unit` = :product_unit,
                 `update_by` = :update_by,
                  `update_date` = sysdate()
                  where  `product_code` = :product_code
            ";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':short_name', $short_name);
            $stmt->bindParam(':product_unit', $product_unit);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':product_code', $product_code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDeliveryLocation($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
