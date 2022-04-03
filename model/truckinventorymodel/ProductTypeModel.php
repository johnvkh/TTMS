<?php

class ProductTypeModel
{

    function __construct()
    {
    }

    public function getAllProductType($conn)
    {
        try {
            $sql = "select * from tis_product_type order by product_type_id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getProductType($conn, $product_type_id)
    {
        try {
            $sql = "select * from tis_product_type WHERE product_type_id = :product_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_type_id', $product_type_id);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }


    public function checkProductTypeName($conn, $product_type_name)
    {
        try {
            $sql = "select * from tis_product_type WHERE product_type_name = :product_type_name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_type_name', $product_type_name);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createProductType(
        $conn,
        $product_type_name,
        $create_by
    ) {

        try {
            $sql = "insert into tis_product_type (product_type_name, create_by, create_date) values (:product_type_name, :create_by, sysdate())";
            $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':cm_date', $cmDate);
            $stmt->bindParam(':product_type_name', $product_type_name);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function udpateProductType(
        $conn,
        $product_type_id,
        $product_type_name,
        $update_by
    ) {
        try {
            $sql = "update tis_product_type set product_type_name = :product_type_name, update_by = :update_by, update_date = sysdate() where product_type_id = :product_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_type_id', $product_type_id);
            $stmt->bindParam(':product_type_name', $product_type_name);
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

    public function deleteProductType($conn, $product_type_id)
    {
        try {
            $sql = "delete from tis_product_type where product_type_id = :product_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_type_id', $product_type_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
