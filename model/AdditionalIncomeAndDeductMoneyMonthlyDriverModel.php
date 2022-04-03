<?php

class AdditionalIncomeAndDeductMoneyMonthlyDriverModel
{


    function __construct()
    {
    }

    public function getAllAdditionalIncomeAndDeductMoneyMonthlyDriver($conn)
    {
        try {
            $sql = "SELECT
            a.`additional_income_and_deduct_money_monthly_driver_date`,
            a.`driver_code`,
            a.`license_plate`,
            a.`working_day`,
            a.`income_per_trip`,
            a.`property_insurance_deduction`,
            a.`deduct_money_from_borrowing`,
            a.`deduction_accident_insurance`,
            a.`deduct_other_money`,
            a.`total_deduction`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `additional_income_and_deduct_money_monthly_driver` a
        ORDER BY a.id DESC;";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getAdditionalIncomeAndDeductMoneyMonthlyDriver($conn,  $driverCode, $licensePlate)
    {
        try {
            $sql = "SELECT
            a.`additional_income_and_deduct_money_monthly_driver_date`,
            a.`driver_code`,
            a.`license_plate`,
            a.`working_day`,
            a.`income_per_trip`,
            a.`property_insurance_deduction`,
            a.`deduct_money_from_borrowing`,
            a.`deduction_accident_insurance`,
            a.`deduct_other_money`,
            a.`total_deduction`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `additional_income_and_deduct_money_monthly_driver` a
        WHERE a.`driver_code` = :driver_code AND a.`license_plate` = :license_plate 
        ORDER BY a.id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':driver_code', $driverCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewAdditionalIncomeAndDeductMoneyMonthlyDriver(
        $conn,
        $additionalIncomeAndDeduct,
        $driverCode,
        $licensePlate,
        $workingDay,
        $incomePerTrip,
        $propertyInsuranceDeduction,
        $deductMoneyFromBorrowing,
        $deductionAccidentInsurance,
        $deductOtherMoney,
        $totalDeduction,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `additional_income_and_deduct_money_monthly_driver`(
                `additional_income_and_deduct_money_monthly_driver_date`,
                `driver_code`,
                `license_plate`,
                `working_day`,
                `income_per_trip`,
                `property_insurance_deduction`,
                `deduct_money_from_borrowing`,
                `deduction_accident_insurance`,
                `deduct_other_money`,
                `total_deduction`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :additional_income_and_deduct_money_monthly_driver_date,
                :driver_code,
                :license_plate,
                :working_day,
                :income_per_trip,
                :property_insurance_deduction,
                :deduct_money_from_borrowing,
                :deduction_accident_insurance,
                :deduct_other_money,
                :total_deduction,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':additional_income_and_deduct_money_monthly_driver_date', $additionalIncomeAndDeduct);
            $stmt->bindParam(':driver_code', $driverCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':working_day', $workingDay);
            $stmt->bindParam(':income_per_trip', $incomePerTrip);
            $stmt->bindParam(':property_insurance_deduction', $propertyInsuranceDeduction);
            $stmt->bindParam(':deduct_money_from_borrowing', $deductMoneyFromBorrowing);
            $stmt->bindParam(':deduction_accident_insurance', $deductionAccidentInsurance);
            $stmt->bindParam(':deduct_other_money', $deductOtherMoney);
            $stmt->bindParam(':total_deduction', $totalDeduction);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function updateAdditionalIncomeAndDeductMoneyMonthlyDriver(
        $conn,
        $additionalIncomeAndDeduct,
        $driverCode,
        $licensePlate,
        $workingDay,
        $incomePerTrip,
        $propertyInsuranceDeduction,
        $deductMoneyFromBorrowing,
        $deductionAccidentInsurance,
        $deductOtherMoney,
        $totalDeduction,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `additional_income_and_deduct_money_monthly_driver`
        SET
            `additional_income_and_deduct_money_monthly_driver_date` = :additional_income_and_deduct_money_monthly_driver_date,
            `working_day` = :working_day,
            `income_per_trip` = :income_per_trip,
            `property_insurance_deduction` = :property_insurance_deduction,
            `deduct_money_from_borrowing` = :deduct_money_from_borrowing,
            `deduction_accident_insurance` = :deduction_accident_insurance,
            `deduct_other_money` = :deduct_other_money,
            `total_deduction` = :total_deduction,
            `update_by` = :update_by,
            `update_date` = SYSDATE()
        WHERE
            `driver_code` = :driver_code AND `license_plate` = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':additional_income_and_deduct_money_monthly_driver_date', $additionalIncomeAndDeduct);
            $stmt->bindParam(':driver_code', $driverCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':working_day', $workingDay);
            $stmt->bindParam(':income_per_trip', $incomePerTrip);
            $stmt->bindParam(':property_insurance_deduction', $propertyInsuranceDeduction);
            $stmt->bindParam(':deduct_money_from_borrowing', $deductMoneyFromBorrowing);
            $stmt->bindParam(':deduction_accident_insurance', $deductionAccidentInsurance);
            $stmt->bindParam(':deduct_other_money', $deductOtherMoney);
            $stmt->bindParam(':total_deduction', $totalDeduction);
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

    public function deleteAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
