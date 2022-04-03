<?php

class RepairInModel
{

    function __construct()
    {
    }

    public function getAllRepairIn($conn)
    {
        try {
            $sql = "SELECT
            a.`id`,
            a.`date_expense`,
            a.`repair_in_bill_code`,
            a.`license_plate`,
            a.`date_repair`,
            a.`total_price`,
            a.`cerate_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `save_repair_in` a order by id desc";
            // exit;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairIn($conn, $repair_in_bill_code)
    {
        try {
            $sql = "SELECT
            a.`id`,
            a.`date_expense`,
            a.`repair_in_bill_code`,
            a.`license_plate`,
            a.`date_repair`,
            a.`total_price`,
            a.`cerate_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `save_repair_in` a where a.repair_in_bill_code = :repair_in_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_in_bill_code', $repair_in_bill_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairInByRepairBillCode($conn, $repair_bill_code)
    {
        try {
            $sql = "SELECT
            a.which_part,
        CASE
                
                WHEN a.which_part = 0 THEN
                'ທ່ອນຫົວ' 
                WHEN a.which_part = 1 THEN
                'ທ່ອນຫາງ' ELSE NULL 
            END AS which_part_name,
            a.repair_bill_code,
            a.license_plate,
        CASE
                
                WHEN a.which_part = 0 THEN
                (
                SELECT
                    ( SELECT e.truck_type_name FROM truck_type e WHERE e.truck_type_code = d.truck_type_code ) 
                FROM
                    truck_registration_head d 
                WHERE
                    d.registration_code = a.license_plate 
                ) ELSE NULL 
            END AS truck_type_name,
            (
            SELECT
                ( SELECT CONCAT( c.firstname, ' ', c.lastname ) FROM driver c WHERE c.driver_code = b.driver_first_code ) 
            FROM
                truck_in_working_bill b 
            WHERE
                b.license_plate = a.license_plate 
            ) AS driver_name,
            a.repair_date,
            a.truck_mile_number,
            a.repair_type_id,
            ( SELECT f.NAME FROM repair_type f WHERE f.id = a.repair_type_id ) AS repair_type_name,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            repair_in a 
        WHERE
            a.repair_bill_code = :repair_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_bill_code', $repair_bill_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkRepairBillCodeInRepairIn($conn, $repair_bill_code)
    {
        try {
            $sql = "SELECT
            a.`repair_in_bill_code`
                FROM
            `save_repair_in` a where a.repair_in_bill_code = :repair_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_bill_code', $repair_bill_code);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportRepairInDailyRepair($conn, $whichPart, $fromDate, $toDate, $fromBill, $toBill)
    {
        try {
            return;
        } catch (PDOException $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkInforForUpdate($conn, $repairInBillCode)
    {
        try {
            $sql = "SELECT
            a.id,
            a.date_expense,
            a.repair_in_bill_code,
            a.which_part,
            a.license_plate,
            a.date_repair,
            a.date_repair_success,
            a.mile_number,
            a.repair_type_id,
            a.note,
            b.`code`,
            b.list,
            b.qty_per_unit,
            b.price_per_unit,
            a.total_price,
            a.repair_facility_id 
        FROM
            save_repair_in a,
            repair_in_truck_parts b 
        WHERE
            a.id = b.save_repair_in_id 
            AND a.repair_in_bill_code = :repair_bill_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repair_bill_code', $repairInBillCode);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairIn(
        $conn,
        $dateExpense,
        $repairInBillCode,
        $whichPart,
        $licensePlate,
        $dateRepair,
        $dateRepairSuccess,
        $mileNumber,
        $repairType,
        $truckPartsCode,
        $list,
        $quantityPerUnit,
        $pricePerUnit,
        $totalPrice,
        $repairFacilityId,
        $note,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `save_repair_in`(
                `date_expense`,
                `repair_in_bill_code`,
                `which_part`,
                `license_plate`,
                `date_repair`,
                `date_repair_success`,
                `mile_number`,
                `repair_type_id`,
                `truck_parts_code`,
                `list`,
                `qty_per_unit`,
                `price_per_unit`,
                `total_price`,
                `repair_facility_id`,
                `note`,
                `cerate_by`,
                `create_date`
            )
            VALUES(
                :date_expense,
                :repair_in_bill_code,
                :which_part,
                :license_plate,
                :date_repair,
                :date_repair_success,
                :mile_number,
                :repair_type_id,
                :truck_parts_code,
                :list,
                :qty_per_unit,
                :price_per_unit,
                :total_price,
                :repair_facility_id,
                :note,
                :cerate_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date_expense', $dateExpense);
            $stmt->bindParam(':repair_in_bill_code', $repairInBillCode);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date_repair', $dateRepair);
            $stmt->bindParam(':date_repair_success', $dateRepairSuccess);
            $stmt->bindParam(':mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairType);
            $stmt->bindParam(':truck_parts_code', $truckPartsCode);
            $stmt->bindParam(':list', $list);
            $stmt->bindParam(':qty_per_unit', $quantityPerUnit);
            $stmt->bindParam(':price_per_unit', $pricePerUnit);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':repair_facility_id', $repairFacilityId);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':cerate_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairInTwo(
        $conn,
        $dateExpense,
        $repairInBillCode,
        $whichPart,
        $licensePlate,
        $repairOrderDate,
        $repairedDate,
        $mileNumber,
        $repairTypeId,
        $repairedDesc,
        $truckPartsDetails,
        $totalPrice,
        $repairFacilityId,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `save_repair_in`(
            `date_expense`,
            `repair_in_bill_code`,
            `which_part`,
            `license_plate`,
            `date_repair`,
            `date_repair_success`,
            `mile_number`,
            `repair_type_id`,
            `total_price`,
            `repair_facility_id`,
            `note`,
            `cerate_by`,
            `create_date`
        )
        VALUES(
            :date_expense,
            :repair_in_bill_code,
            :which_part,
            :license_plate,
            :date_repair,
            :date_repair_success,
            :mile_number,
            :repair_type_id,
            :total_price,
            :repair_facility_id,
            :note,
            :cerate_by,
            sysdate()
        )";
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date_expense', $dateExpense);
            $stmt->bindParam(':repair_in_bill_code', $repairInBillCode);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date_repair', $repairOrderDate);
            $stmt->bindParam(':date_repair_success', $repairedDate);
            $stmt->bindParam(':mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':repair_facility_id', $repairFacilityId);
            $stmt->bindParam(':note', $repairedDesc);
            $stmt->bindParam(':cerate_by', $createBy);
            if ($stmt->execute()) {
                $save_repair_last_id = $conn->lastInsertId();
                $resultInsertTruckpartsDetail = $this->insertTruckpartsDetail($conn, $truckPartsDetails, $save_repair_last_id);
                if ($resultInsertTruckpartsDetail) {
                    $conn->commit();
                    return true;
                }
                $conn->rollBack();
                return false;
            }
            return false;
        } catch (PDOException $ex) {
            $conn->rollBack();
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    function insertTruckpartsDetail($conn, $truck_parts_details, $save_repair_last_id)
    {

        $sql = "INSERT INTO `repair_in_truck_parts`(
                        `save_repair_in_id`,
                        `code`,
                        `list`,
                        `qty_per_unit`,
                        `price_per_unit`,
                        `create_date`
                    )
                    VALUES(
                        :save_repair_in_id,
                        :code,
                        :list,
                        :qty_per_unit,
                        :price_per_unit,
                        sysdate()
                    )";

        try {
            if (is_array($truck_parts_details) || is_object($truck_parts_details)) {
                // $conn->beginTransaction();
                foreach ($truck_parts_details as $truck_parts_detail) {
                    $check_data = $this->getRepairInTruckPartByRepairInIdAndTruckPartsCode($conn, $truck_parts_detail->truckPartsCode, $save_repair_last_id);
                    if ($check_data->rowCount() > 0) {
                        continue;
                    }
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':save_repair_in_id', $save_repair_last_id);
                    $stmt->bindParam(':code', $truck_parts_detail->truckPartsCode);
                    $stmt->bindParam(':list', $truck_parts_detail->list);
                    $stmt->bindParam(':qty_per_unit', $truck_parts_detail->quantityPerUnit);
                    $stmt->bindParam(':price_per_unit', $truck_parts_detail->pricePerUnit);
                    $stmt->execute();
                }
                return true;
            }
            return false;
        } catch (PDOException $ex) {
            $conn->rollBack();
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateRepairIn(
        $conn,
        $saveRepairInId,
        $dateExpense,
        $repairInBillCode,
        $whichPart,
        $licensePlate,
        $repairOrderDate,
        $repairedDate,
        $mileNumber,
        $repairTypeId,
        $repairedDesc,
        $truckPartsDetails,
        $totalPrice,
        $repairFacilityId,
        $updateBy
    ) {

        try {
            $sql = "
            UPDATE
            `save_repair_in`
        SET
            `date_expense` = :date_expense,
            `which_part` = :which_part,
            `license_plate` = :license_plate,
            `date_repair` = :date_repair,
            `date_repair_success` = :date_repair_success,
            `mile_number` = :mile_number,
            `repair_type_id` = :repair_type_id,
            `total_price` = :total_price,
            `repair_facility_id` = :repair_facility_id,
            `note` = :note,
            `update_by` = :update_by,
            `update_date` = SYSDATE()
        WHERE
            `repair_in_bill_code` = :repair_in_bill_code;
            ";

            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date_expense', $dateExpense);
            $stmt->bindParam(':repair_in_bill_code', $repairInBillCode);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date_repair', $repairOrderDate);
            $stmt->bindParam(':date_repair_success', $repairedDate);
            $stmt->bindParam(':mile_number', $mileNumber);
            $stmt->bindParam(':repair_type_id', $repairTypeId);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->bindParam(':repair_facility_id', $repairFacilityId);
            $stmt->bindParam(':note', $repairedDesc);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                $resultInsertTruckpartsDetail = $this->insertTruckpartsDetail($conn, $truckPartsDetails, $saveRepairInId);
                if ($resultInsertTruckpartsDetail) {
                    $conn->commit();
                    return true;
                }
                $conn->rollBack();
                return false;
            }
            return false;
        } catch (Exception $ex) {
            $conn->rollBack();
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkBeforeDelete($conn, $id)
    {
        try {
            $sql = "select id from save_repair_in where id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteRepairIn($conn, $id)
    {
        try {
            $sql = "delete from save_repair_in where id = :id";
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                $stmt_sec = $conn->prepare("delete from repair_in_truck_parts where save_repair_in_id = :save_repair_in_id");
                $stmt_sec->bindParam(':save_repair_in_id', $id);
                if ($stmt_sec->execute()) {
                    $conn->commit();
                    return true;
                }
                $conn->rollBack();
                return false;
            }
            return false;
        } catch (Exception $ex) {
            $conn->rollBack();
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    /* repair in truck parts */

    public function getRepairInTruckPartByRepairInIdAndTruckPartsCode($conn, $code, $saveRepairInId)
    {
        try {
            $sql = "select save_repair_in_id, code from repair_in_truck_parts where save_repair_in_id = :save_repair_in_id and code = :code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':save_repair_in_id', $saveRepairInId);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteRepairInTruckParts($conn, $code, $saveRepairInId)
    {
        try {
            $sql = "delete from repair_in_truck_parts where code = :code and save_repair_in_id = :save_repair_in_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':save_repair_in_id', $saveRepairInId);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    /* report */
    public function reportDailyRepairIn($conn, $fromDate, $toDate, $whichPart)
    {

        try {
            $sql = "SELECT
            a.date_expense,
            a.repair_in_bill_code,
            a.license_plate,
            a.date_repair,
            a.date_repair_success,
            a.repair_type_id,
            a.note,
            a.repair_facility_id,
            b.code,
            b.list,
            b.qty_per_unit,
            b.price_per_unit,
            a.cerate_by,
            a.create_date,
            a.update_by,
            a.update_date
        FROM
            save_repair_in a, repair_in_truck_parts b 
            WHERE
	        a.which_part = 0 AND DATE_FORMAT( a.date_expense, '%Y-%m-%d' ) BETWEEN DATE_FORMAT( '2021-12-01', '%Y-%m-%d' ) AND DATE_FORMAT('2021-12-12','%Y-%m-%d')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
