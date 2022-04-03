<?php

class RepairInBillModel
{

    function __construct()
    {
    }

    public function getAllRepairInBill($conn, $fromRepairBillCode, $toRepairBillCode, $fromRepairDate, $toRepairDate)
    {
        try {
            $sql = "SELECT
            a.id,
            a.which_part,
            a.repair_bill_code,
            a.license_plate,
            a.truck_mile_number,
            a.repair_type_id,
            (SELECT b.name FROM repair_type b WHERE b.id = a.repair_type_id) as repair_type_name,
            a.note,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM 
            repair_in a ";

            if ((!empty($fromRepairDate) && !empty($toRepairDate)) || (!empty($fromRepairBillCode) && !empty($toRepairBillCode))) {
                $sql .= " where ";
            }

            if (!empty($fromRepairBillCode) && !empty($toRepairBillCode)) {
                $sql .= " ( a.repair_bill_code BETWEEN :fromRepairBillCode AND :toRepairBillCode )   ";
            }

            if ((!empty($fromRepairDate) && !empty($toRepairDate)) && (!empty($fromRepairBillCode) && !empty($toRepairBillCode))) {
                $sql .= " and ";
            }

            if (!empty($fromRepairDate) && !empty($toRepairDate)) {
                $sql .= "( DATE_FORMAT(a.repair_date,'%Y-%m-%d') BETWEEN DATE_FORMAT(:fromRepairDate,'%Y-%m-%d') AND DATE_FORMAT(:toRepairDate,'%Y-%m-%d') ) ";
            }
            $sql .= " order by id desc";
            // exit;
            $stmt = $conn->prepare($sql);
            if (!empty($fromRepairBillCode) && !empty($toRepairBillCode)) {
                $stmt->bindParam(':fromRepairBillCode', $fromRepairBillCode);
                $stmt->bindParam(':toRepairBillCode', $toRepairBillCode);
            }
            if (!empty($fromRepairDate) && !empty($toRepairDate)) {
                $stmt->bindParam(':fromRepairDate', $fromRepairDate);
                $stmt->bindParam(':toRepairDate', $toRepairDate);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            // http_response_code(200);
            // printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairInBill($conn, $repair_bill_code)
    {
        try {
            $sql = "SELECT
            a.id,
            a.which_part,
            a.repair_bill_code,
            a.license_plate,
            a.truck_mile_number,
            a.repair_type_id,
            (SELECT b.name FROM repair_type b WHERE b.id = a.repair_type_id) as repair_type_name,
            a.note,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM 
            repair_in a 
            WHERE a.repair_bill_code = :repair_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_bill_code', $repair_bill_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairInBillByLicensePlate($conn, $license_plate)
    {
        try {
            $sql = "SELECT
            a.id,
            a.which_part,
            a.repair_bill_code,
            a.license_plate,
            CASE
                WHEN a.which_part = 0 THEN
                (SELECT (SELECT e.truck_type_name FROM truck_type e WHERE e.truck_type_code = d.truck_type_code) FROM truck_registration_head d WHERE d.registration_code = a.license_plate)
                ELSE NULL
            END as truck_type_name,
            (SELECT (SELECT concat(c.firstname, ' ', c.lastname) FROM driver c WHERE c.driver_code = b.driver_first_code) FROM truck_in_working_bill b WHERE b.license_plate = a.license_plate) as driver_name,
            a.truck_mile_number,
            -- a.repair_type_id,
            -- (SELECT f.name FROM repair_type f WHERE f.id = a.repair_type_id) as repair_type_name,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM 
            repair_in a
            WHERE a.license_plate = :license_plate;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairInBill(
        $conn,
        $repairBillCode,
        $whichPart,
        $licensePlate,
        $repairDate,
        $mileNumber,
        $repairTypeId,
        $note,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `repair_in`(
                `which_part`,
                `repair_bill_code`,
                `license_plate`,
                `repair_date`,
                `truck_mile_number`,
                `repair_type_id`,
                `note`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :which_part,
                :repair_bill_code,
                :license_plate,
                :repair_date,
                :truck_mile_number,
                :repair_type_id,
                :note,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':repair_bill_code', $repairBillCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':repair_date', $repairDate);
            $stmt->bindParam(':truck_mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':note', $note);
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

    public function updateRepairInBill(
        $conn,
        $repairBillCode,
        $whichPart,
        $licensePlate,
        $repairDate,
        $mileNumber,
        $repairTypeId,
        $note,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `repair_in`
        SET
            `which_part` = :which_part,
            `license_plate` = :license_plate,
            `repair_date` = :repair_date,
            `truck_mile_number` = :truck_mile_number,
            `repair_type_id` = :repair_type_id,
            `note` = :note,
            `update_by` = :update_by,
            `update_date` = sysdate()
        WHERE
            `repair_bill_code` = :repair_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':repair_bill_code', $repairBillCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':repair_date', $repairDate);
            $stmt->bindParam(':truck_mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':note', $note);
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

    public function deleteRepairInBill($conn, $id)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
