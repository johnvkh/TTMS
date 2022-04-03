<?php

class ReceiveProductModel
{

    function __construct()
    {
    }

    public function getAllReceiveProduct($conn, $fromDocumentNo, $toDocumentNo, $fromDocumentDate, $toDocumentDate)
    {
        try {
            $sql = "SELECT * FROM tis_receive_product a, tis_receive_product_detail b WHERE (a.document_no = b.document_no)  ";
            if (!empty($fromDocumentNo) && !empty($toDocumentNo)) {
                $sql .= " AND (a.document_no BETWEEN :from_document_no AND :to_document_no) ";
            }

            if (!empty($fromDocumentDate) && !empty($toDocumentDate)) {
                $sql .= " AND (a.document_date BETWEEN :from_document_date AND :to_document_date) ";
            }

            $sql .= " order by a.id desc";
            $stmt = $conn->prepare($sql);
            if (!empty($fromDocumentNo) && !empty($toDocumentNo)) {
                $stmt->bindParam(":from_document_no", $fromDocumentNo);
                $stmt->bindParam(":to_document_no", $toDocumentNo);
            }

            if (!empty($fromDocumentDate) && !empty($toDocumentDate)) {
                $stmt->bindParam(":from_document_date", $fromDocumentDate);
                $stmt->bindParam(":to_document_date", $toDocumentDate);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getReceiveProduct($conn, $document_no)
    {
        try {
            $sql = "select * from tis_receive_product WHERE document_no = :document_no";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createReceiveProduct(
        $conn,
        $no,
        $documentNo,
        $documentDate,
        $note,
        $productDetail,
        $totalPrice,
        $create_by
    ) {

        try {
            $sql = "insert into tis_receive_product (no, document_no, document_date, note, total_price, create_by, create_date) 
            values (:no, :document_no, :document_date, :note, :total_price, :create_by, sysdate())";

            $sql_sec = "insert into tis_receive_product_detail (document_no, product_code, product_name, unit_id, qty, unit_cost, price) 
            values (:document_no, :product_code, :product_name, :unit_id, :qty, :unit_cost, :price)";


            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':no', $no);
            $stmt->bindParam(':document_no', $documentNo);
            $stmt->bindParam(':document_date', $documentDate);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                foreach ($productDetail as $item) {
                    $stmt_sec = $conn->prepare($sql_sec);
                    $stmt_sec->bindParam(':document_no', $documentNo);
                    $stmt_sec->bindParam(':product_code', $item->productCode);
                    $stmt_sec->bindParam(':product_name', $item->productName);
                    $stmt_sec->bindParam(':unit_id', $item->unitId);
                    $stmt_sec->bindParam(':qty', $item->unitQty);
                    $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                    $stmt_sec->bindParam(':price', $item->price);
                    if (!$stmt_sec->execute()) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkDuplicateReceiveProductDetail($conn, $document_no, $product_code)
    {
        try {
            $sql = "select document_no, product_code from tis_receive_product_detail where document_no = :document_no and product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->bindParam(':product_code', $product_code);
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

    public function udpateReceiveProduct(
        $conn,
        $no,
        $documentNo,
        $documentDate,
        $note,
        $productDetail,
        $totalPrice,
        $updateBy
    ) {
        try {
            $sql  = "update tis_receive_product 
            set document_date = :document_date, 
            note = :note, 
            total_price = :total_price,
            update_by = :update_by, 
            update_date = sysdate() 
            where document_no = :document_no";
            $sql_sec = "update tis_receive_product_detail 
            set product_name = :product_name, 
            unit_id = :unit_id, 
            qty = :qty, 
            unit_cost = :unit_cost, 
            price = :price 
            where document_no = :document_no and product_code = :product_code";
            $sql_insert = "insert into tis_receive_product_detail (document_no, product_code, product_name, unit_id, qty, unit_cost, price) 
            values (:document_no, :product_code, :product_name, :unit_id, :qty, :unit_cost, :price)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $documentNo);
            $stmt->bindParam(':document_date', $documentDate);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                foreach ($productDetail as $item) {
                    if ($this->checkDuplicateReceiveProductDetail($conn, $documentNo, $item->productCode)) {
                        $stmt_sec = $conn->prepare($sql_sec);
                        $stmt_sec->bindParam(':document_no', $documentNo);
                        $stmt_sec->bindParam(':product_code', $item->productCode);
                        $stmt_sec->bindParam(':product_name', $item->productName);
                        $stmt_sec->bindParam(':unit_id', $item->unitId);
                        $stmt_sec->bindParam(':qty', $item->unitQty);
                        $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                        $stmt_sec->bindParam(':price', $item->price);
                        if (!$stmt_sec->execute()) {
                            return false;
                        }
                    } else {
                        $stmt_sec = $conn->prepare($sql_insert);
                        $stmt_sec->bindParam(':document_no', $documentNo);
                        $stmt_sec->bindParam(':product_code', $item->productCode);
                        $stmt_sec->bindParam(':product_name', $item->productName);
                        $stmt_sec->bindParam(':unit_id', $item->unitId);
                        $stmt_sec->bindParam(':qty', $item->unitQty);
                        $stmt_sec->bindParam(':unit_cost', $item->unitCost);
                        $stmt_sec->bindParam(':price', $item->price);
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

    public function deleteReceiveProduct($conn, $document_no)
    {
        try {
            $sql = "delete from tis_receive_product where document_no = :document_no";
            $sql_sec = "delete from tis_receive_product_detail where document_no = :document_no";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            if ($stmt->execute()) {
                $stmt_sec = $conn->prepare($sql_sec);
                $stmt_sec->bindParam(':document_no', $document_no);
                if (!$stmt_sec->execute()) {
                    return false;
                }
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkProductDetailByDocumentNo($conn, $document_no)
    {
        try {
            $sql = "select document_no, product_code from tis_receive_product_detail where document_no = :document_no";
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


    public function checkProductDetail($conn, $document_no, $product_code)
    {
        try {
            $sql = "select document_no, product_code from tis_receive_product_detail where document_no = :document_no and product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->bindParam(':product_code', $product_code);
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


    public function deleteReceiveProductDetail($conn, $document_no, $product_code)
    {
        try {
            $sql = "delete from tis_receive_product_detail where document_no = :document_no and product_code = :product_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_no', $document_no);
            $stmt->bindParam(':product_code', $product_code);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }
}
