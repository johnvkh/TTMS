<?php

class RequisitionProductModel
{

    function __construct()
    {
    }

    public function getAllRequisition($conn)
    {
        try {
            $sql = "select * from tis_unit order by id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRequisition($conn, $id)
    {
        try {
            $sql = "select * from tis_unit WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }


    public function createRequisition(
        $conn,
        $document_no,
        $document_date,
        $note,
        $product_detail,
        $total_price,
        $create_by
    ) {

        try {
            $sql = "insert into tis_requisition_product (document_no, document_date, note, total_price, create_by, create_date) 
            values (:document_no, :document_date, :note, :total_price, :create_by, sysdate())";
            $sql_sec = "insert into tis_requisition_product_detail (document_no, product_code, product_name, unit, qty_per_unit, unit_cost, price_unit, total_price)
            values (:document_no, :product_code, :product_name, :unit_id, :qty, :unit_cost, :price_unit, :total_price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->bindParam(':document_date', $document_date);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':total_price', $total_price);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                foreach ($product_detail as $item) {
                    $stmt_sec = $conn->prepare($sql_sec);
                    $stmt_sec->bindParam(':document_no', $document_no);
                    $stmt_sec->bindParam(':product_code', $item->productCode);
                    $stmt_sec->bindParam(':product_name', $item->productName);
                    $stmt_sec->bindParam(':unit_id', $item->unitId);
                    $stmt_sec->bindParam(':qty', $item->unitQty);
                    $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                    $stmt_sec->bindParam(':price_unit', $item->price);
                    $stmt_sec->bindParam(':total_price', $item->price);
                    if (!$stmt_sec->execute()) {
                        return false;
                    }
                }
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkRequisitionProduct($conn, $document_no)
    {
        try {
            $sql = "select document_no from tis_requisition_product where document_no = :document_no";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkDuplicateReceiveProductDetail($conn, $document_no, $product_code)
    {
        try {
            $sql = "select document_no, product_code from tis_requisition_product_detail where document_no = :document_no and product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":document_no", $document_no);
            $stmt->bindParam(":product_code", $product_code);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function udpateRequisition(
        $conn,
        $document_no,
        $document_date,
        $note,
        $product_detail,
        $total_price,
        $update_by
    ) {
        try {
            $sql = "update tis_requisition_product set 
            document_date = :document_date, note = :note, total_price = :total_price, update_by = :update_by, update_date = sysdate()
            where document_no = :document_no";
            $sql_sec = "update tis_requisition_product_detail set 
            product_name = :product_name, 
            unit = :unit, 
            qty_per_unit = :qty_per_unit, 
            unit_cost = :unit_cost, 
            price_unit = :price_unit, 
            total_price = :total_price
            where document_no = :document_no and product_code = :product_code";
            $sql_insert = "insert into tis_requisition_product_detail (document_no, product_code, product_name, unit, qty_per_unit, unit_cost, price_unit, total_price)
            values (:document_no, :product_code, :product_name, :unit_id, :qty, :unit_cost, :price_unit, :total_price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->bindParam(':document_date', $document_date);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':total_price', $total_price);
            $stmt->bindParam(':update_by', $update_by);
            if ($stmt->execute()) {
                foreach ($product_detail as $item) {
                    if ($this->checkDuplicateReceiveProductDetail($conn, $document_no, $item->productCode)) {
                        $stmt_sec = $conn->prepare($sql_sec);
                        $stmt_sec->bindParam(':document_no', $document_no);
                        $stmt_sec->bindParam(':product_code', $item->productCode);
                        $stmt_sec->bindParam(':product_name', $item->productName);
                        $stmt_sec->bindParam(':unit', $item->unitId);
                        $stmt_sec->bindParam(':qty_per_unit', $item->unitQty);
                        $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                        $stmt_sec->bindParam(':price_unit', $item->price);
                        $stmt_sec->bindParam(':total_price', $item->price);
                        if (!$stmt_sec->execute()) {
                            return false;
                        }
                    } else {
                        $stmt_sec = $conn->prepare($sql_insert);
                        $stmt_sec->bindParam(':document_no', $document_no);
                        $stmt_sec->bindParam(':product_code', $item->productCode);
                        $stmt_sec->bindParam(':product_name', $item->productName);
                        $stmt_sec->bindParam(':unit_id', $item->unitId);
                        $stmt_sec->bindParam(':qty', $item->unitQty);
                        $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                        $stmt_sec->bindParam(':price_unit', $item->price);
                        $stmt_sec->bindParam(':total_price', $item->price);
                        if (!$stmt_sec->execute()) {
                            return false;
                        }
                    }
                }
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function deleteRequisition($conn, $document_no)
    {
        try {
            $sql = "delete from tis_requisition_product where document_no = :document_no";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            if ($stmt->execute()) {
                $sql_sec = "delete from tis_requisition_product where document_no = :document_no";
                $stmt = $conn->prepare($sql_sec);
                $stmt->bindParam(':document_no', $document_no);
                if (!$stmt->execute()) {
                    return false;
                }
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }
}
