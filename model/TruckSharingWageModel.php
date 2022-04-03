<?php

class TruckSharingWageModel
{

    function __construct()
    {
    }

    public function getAllTruckSharingWage($conn, $date, $ownerTruckSharingCode)
    {
        try {

            $sql = "SELECT
                    a.`truck_sharing_payment_bill`,
                    a.`paytment_date`,
                    a.`owner_truck_truck_sharing_code`,
                    a.`truck_sharing_work_bill`,
                    a.`truck_sharing_working_date`,
                    a.`license_plate`,
                    a.`truck_sharing_money`,
                    a.`cost_oil`,
                    a.`damnages`,
                    a.`product_insurance`,
                    a.`other`,
                    a.`create_by`,
                    a.`create_date`,
                    a.`update_by`,
                    a.`update_date` 
                FROM
                    `truck_sharing_wage` a ";
            if ($date != null || $date != "") {
                $sql .= " where date_format(a.paytment_date, '%Y-%m-%d') = date_format(:date, '%Y-%m-%d') ";
            }
            
            if ($ownerTruckSharingCode != null || $ownerTruckSharingCode != "") {
                $sql .=" and a.owner_truck_truck_sharing_code = :ownerTruckSharingCode ";
            }

            $sql .= " order by a.id desc;";
            $stmt = $conn->prepare($sql);
            if ($date != null || $date != "") {
                $stmt->bindParam(':date', $date);
            }
            if ($ownerTruckSharingCode != null || $ownerTruckSharingCode != "") {
                 $stmt->bindParam(':ownerTruckSharingCode', $ownerTruckSharingCode);
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

    public function getTruckSharingWage($conn, $truck_sharing_payment_bill)
    {
        try {
            $sql = "SELECT
            a.`truck_sharing_payment_bill`,
            a.`paytment_date`,
            a.`owner_truck_truck_sharing_code`,
            a.`truck_sharing_work_bill`,
            a.`truck_sharing_working_date`,
            a.`license_plate`,
            a.`truck_sharing_money`,
            a.`cost_oil`,
            a.`damnages`,
            a.`product_insurance`,
            a.`other`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `truck_sharing_wage` a
        WHERE
            a.`truck_sharing_payment_bill` = :truck_sharing_payment_bill
        ORDER BY
            a.id
        DESC
            ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_sharing_payment_bill', $truck_sharing_payment_bill);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTruckSharingWage(
        $conn,
        $truckSharingPaymentBill,
        $paymentDate,
        $ownerTruckSharingCode,
        $truckSharingWorkingBill,
        $truckSharingWorkingDate,
        $licensePlate,
        $truckSharingMoney,
        $costOil,
        $damages,
        $productInsurance,
        $other,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `truck_sharing_wage`(
                `truck_sharing_payment_bill`,
                `paytment_date`,
                `owner_truck_truck_sharing_code`,
                `truck_sharing_work_bill`,
                `truck_sharing_working_date`,
                `license_plate`,
                `truck_sharing_money`,
                `cost_oil`,
                `damnages`,
                `product_insurance`,
                `other`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :truck_sharing_payment_bill,
                :paytment_date,
                :owner_truck_truck_sharing_code,
                :truck_sharing_work_bill,
                :truck_sharing_working_date,
                :license_plate,
                :truck_sharing_money,
                :cost_oil,
                :damnages,
                :product_insurance,
                :other,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_sharing_payment_bill', $truckSharingPaymentBill);
            $stmt->bindParam(':paytment_date', $paymentDate);
            $stmt->bindParam(':owner_truck_truck_sharing_code', $ownerTruckSharingCode);
            $stmt->bindParam(':truck_sharing_work_bill', $truckSharingWorkingBill);
            $stmt->bindParam(':truck_sharing_working_date', $truckSharingWorkingDate);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':truck_sharing_money', $truckSharingMoney);
            $stmt->bindParam(':cost_oil', $costOil);
            $stmt->bindParam(':damnages', $damages);
            $stmt->bindParam(':product_insurance', $productInsurance);
            $stmt->bindParam(':other', $other);
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

    public function updateTruckSharingWage(
        $conn,
        $truckSharingPaymentBill,
        $paymentDate,
        $ownerTruckSharingCode,
        $truckSharingWorkingBill,
        $truckSharingWorkingDate,
        $licensePlate,
        $truckSharingMoney,
        $costOil,
        $damages,
        $productInsurance,
        $other,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `truck_sharing_wage`
        SET
            `paytment_date` = :paytment_date,
            `owner_truck_truck_sharing_code` = :owner_truck_truck_sharing_code,
            `truck_sharing_work_bill` = :truck_sharing_work_bill,
            `truck_sharing_working_date` = :truck_sharing_working_date,
            `license_plate` = :license_plate,
            `truck_sharing_money` = :truck_sharing_money,
            `cost_oil` = :cost_oil,
            `damnages` = :damnages,
            `product_insurance` = :product_insurance,
            `other` = :other,
            `create_by` = :update_by,
            `create_date` = SYSDATE()
        WHERE
            `truck_sharing_payment_bill` = :truck_sharing_payment_bill;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_sharing_payment_bill', $truckSharingPaymentBill);
            $stmt->bindParam(':paytment_date', $paymentDate);
            $stmt->bindParam(':owner_truck_truck_sharing_code', $ownerTruckSharingCode);
            $stmt->bindParam(':truck_sharing_work_bill', $truckSharingWorkingBill);
            $stmt->bindParam(':truck_sharing_working_date', $truckSharingWorkingDate);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':truck_sharing_money', $truckSharingMoney);
            $stmt->bindParam(':cost_oil', $costOil);
            $stmt->bindParam(':damnages', $damages);
            $stmt->bindParam(':product_insurance', $productInsurance);
            $stmt->bindParam(':other', $other);
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

    public function deleteTruckSharingWage($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
