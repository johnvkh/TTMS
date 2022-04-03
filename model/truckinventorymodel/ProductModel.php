<?php

class ProductModel
{

    function __construct()
    {
    }

    public function getAllProduct($conn)
    {
        try {
            $sql = "select product_code, product_name, product_unit_id, date, lowest_stocked, highest_stocked, product_type_id, accounting_id, price_per_unit, big_unit, size, note, product_img, create_by, create_date, update_by, update_date from tis_product order by product_id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getProduct($conn, $product_code)
    {
        try {
            $sql = "select product_code, product_name, product_unit_id, date, lowest_stocked, highest_stocked, product_type_id, accounting_id, price_per_unit, big_unit, size, note, product_img, create_by, create_date, update_by, update_date from tis_product where product_code = :product_code ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $product_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkProductName($conn, $product_name)
    {
        try {
            $sql = "select product_name from tis_product where product_name = :product_name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkProductCode($conn, $product_code)
    {
        try {
            $sql = "select product_code from tis_product where product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $product_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createProduct(
        $conn,
        $productCode,
        $productName,
        $productUnitId,
        $date,
        $lowestStocked,
        $highestStocked,
        $productTypeId,
        $acoountingId,
        $pricePerUnit,
        $bigUnit,
        $size,
        $note,
        $productImg,
        $createBy
    ) {
        try {
            $sql = "insert into tis_product (product_code, product_name, product_unit_id, date, lowest_stocked, highest_stocked, product_type_id, accounting_id, price_per_unit, big_unit, size, note, product_img, create_by, create_date) 
            values (:product_code, :product_name, :product_unit_id, :date, :lowest_stocked, :highest_stocked, :product_type_id, :accounting_id, :price_per_unit, :big_unit, :size, :note, :product_img, :create_by, sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $productCode);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':product_unit_id', $productUnitId);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':lowest_stocked', $lowestStocked);
            $stmt->bindParam(':highest_stocked', $highestStocked);
            $stmt->bindParam(':product_type_id', $productTypeId);
            $stmt->bindParam(':accounting_id', $acoountingId);
            $stmt->bindParam(':price_per_unit', $pricePerUnit);
            $stmt->bindParam(':big_unit', $bigUnit);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':product_img', $productImg);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateProduct(
        $conn,
        $productCode,
        $productName,
        $productUnitId,
        $date,
        $lowestStocked,
        $highestStocked,
        $productTypeId,
        $acoountingId,
        $pricePerUnit,
        $bigUnit,
        $size,
        $note,
        $productImg,
        $update_by
    ) {
        try {
            $sql = "update tis_product set 
            product_code = :product_code,
            product_name = :product_name,
            product_unit_id = :product_unit_id,
            date = :date,
            lowest_stocked = :lowest_stocked, 
            highest_stocked = :highest_stocked, 
            product_type_id = :product_type_id, 
            accounting_id = :accounting_id, 
            price_per_unit = :price_per_unit, 
            big_unit = :big_unit, 
            size = :size, 
            note = :note, 
            product_img = :product_img, 
            update_by = :update_by, 
            update_date = sysdate() 
            where product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $productCode);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':product_unit_id', $productUnitId);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':lowest_stocked', $lowestStocked);
            $stmt->bindParam(':highest_stocked', $highestStocked);
            $stmt->bindParam(':product_type_id', $productTypeId);
            $stmt->bindParam(':accounting_id', $acoountingId);
            $stmt->bindParam(':price_per_unit', $pricePerUnit);
            $stmt->bindParam(':big_unit', $bigUnit);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':product_img', $productImg);
            $stmt->bindParam(':update_by', $update_by);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteProduct($conn, $product_code)
    {
        try {
            $sql = "delete from tis_product where product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_code', $product_code);
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
