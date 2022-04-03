<?php

class RepairOutModel
{

    function __construct()
    {
    }

    public function getAllRepairOut($conn)
    {
        try {
            $sql = "SELECT repair_out_bill_code, create_date, license_plate, total_cost, create_by, create_date, update_by, update_date FROM repair_out ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairOut($conn, $repairOutBillCode)
    {
        try {
            $sql = "SELECT a.repair_out_bill_code, a.expense_date, a.which_part, a.truck_type_id, a.license_plate, a.repair_order_date, a.repair_date, a.mile_number, a.repair_type_id, a.note, a.repair_store_code, a.invoiceCode, a.date, a.total_cost, a.create_by, a.create_date, a.update_by, a.update_date FROM repair_out a WHERE a.repair_out_bill_code = :repair_out_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_out_bill_code', $repairOutBillCode);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairOut(
        $conn,
        $repairOutBillCode,
        $expenseDate,
        $whichPart,
        $licensePlate,
        $repairOrderDate,
        $repairedDate,
        $mileNumber,
        $repairTypeId,
        $note,
        $repairStoreId,
        $invoiceCode,
        $date,
        $totalCost,
        $createBy
    ) {
        try {
            $sql = "INSERT INTO `repair_out`(
                `repair_out_bill_code`,
                `expense_date`,
                `which_part`,
                `license_plate`,
                `repair_order_date`,
                `repair_date`,
                `mile_number`,
                `repair_type_id`,
                `note`,
                `repair_store_code`,
                `invoiceCode`,
                `date`,
                `total_cost`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :repair_out_bill_code,
                :expense_date,
                :which_part,
                :license_plate,
                :repair_order_date,
                :repair_date,
                :mile_number,
                :repair_type_id,
                :note,
                :repair_store_code,
                :invoiceCode,
                :date,
                :total_cost,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_out_bill_code', $repairOutBillCode);
            $stmt->bindParam(':expense_date', $expenseDate);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':repair_order_date', $repairOrderDate);
            $stmt->bindParam(':repair_date', $repairedDate);
            $stmt->bindParam(':mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':repair_store_code', $repairStoreId);
            $stmt->bindParam(':invoiceCode', $invoiceCode);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':total_cost', $totalCost);
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

    public function updateRepairOut(
        $conn,
        $repairOutBillCode,
        $expenseDate,
        $whichPart,
        $licensePlate,
        $repairOrderDate,
        $repairedDate,
        $mileNumber,
        $repairTypeId,
        $note,
        $repairStoreId,
        $invoiceCode,
        $date,
        $totalCost,
        $updateBy
    ) {

        try {
            $sql = "UPDATE repair_out SET expense_date = :expense_date, which_part = :which_part, license_plate = :license_plate, repair_order_date = :repair_order_date, repair_date = :repair_date, mile_number = :mile_number, repair_type_id = :repair_type_id, note = :note, repair_store_code = :repair_store_code, invoiceCode = :invoiceCode, date = :date, total_cost = :total_cost, update_by = :update_by, update_date = sysdate() WHERE repair_out_bill_code = :repair_out_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_out_bill_code', $repairOutBillCode);
            $stmt->bindParam(':expense_date', $expenseDate);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':repair_order_date', $repairOrderDate);
            $stmt->bindParam(':repair_date', $repairedDate);
            $stmt->bindParam(':mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':repair_store_code', $repairStoreId);
            $stmt->bindParam(':invoiceCode', $invoiceCode);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':total_cost', $totalCost);
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

    public function deleteRepairOut($conn, $repairOutBillCode)
    {
        try {
            $sql = "delete from repair_out where repair_out_bill_code = :repair_out_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":repair_out_bill_code", $repairOutBillCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportRepairOut($conn, $fromRepairOutBillCode, $toRepairOutBillCode, $fromDate, $toDate)
    {
        try {
            $sql = "SELECT a.repair_out_bill_code, a.expense_date, a.which_part, a.license_plate, a.repair_order_date, a.repair_date, a.mile_number, a.repair_type_id, a.note, a.repair_store_code, a.invoiceCode, a.date, a.total_cost, a.create_by, a.create_date, a.update_by, a.update_date FROM repair_out a WHERE a.repair_out_bill_code BETWEEN :from_repair_out_bill_code AND :to_repair_out_bill_code  ";
            if (!empty($fromDate) && !empty($toDate)) {
                $sql .= " AND DATE_FORMAT( a.expense_date, '%Y-%m-%d' ) BETWEEN DATE_FORMAT( :from_date, '%Y-%m-%d' ) AND DATE_FORMAT( :to_date, '%Y-%m-%d' ) ";
            }
            $sql .= " ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":from_repair_out_bill_code", $fromRepairOutBillCode);
            $stmt->bindParam(":to_repair_out_bill_code", $toRepairOutBillCode);
            if (!empty($fromDate) && !empty($toDate)) {
                $stmt->bindParam(":from_date", $fromDate);
                $stmt->bindParam(":to_date", $toDate);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportDailyRepairOut($conn, $fromDate, $toDate, $whichPart, $fromLicensePlate, $toLicensePlate)
    {
        try {
            $sql = "SELECT a.expense_date, a.repair_out_bill_code, a.which_part,case when a.which_part = 0 then 'ທ່ອນຫົວ' when a.which_part = 1 then 'ທ່ອນຫາງ' else null end as which_part_name, a.license_plate, a.repair_order_date, a.repair_date, a.repair_type_id, a.note, a.repair_store_code, a.invoiceCode, a.date, a.total_cost, a.create_by, a.create_date, a.update_by, a.update_date FROM repair_out a WHERE DATE_FORMAT(a.expense_date,'%Y-%d-%m') BETWEEN DATE_FORMAT(:from_date,'%Y-%d-%m') AND DATE_FORMAT(:to_date,'%Y-%d-%m') AND a.which_part = :which_part";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":from_date", $fromDate);
            $stmt->bindParam(":to_date", $toDate);
            $stmt->bindParam(":which_part", $whichPart);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
