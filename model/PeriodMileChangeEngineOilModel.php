<?php

class PeriodMileChangeEngineOilModel
{


    function __construct()
    {
    }

    public function getAllPeriodMileChangeEngineOil($conn)
    {
        try {
            $sql = 'SELECT * FROM `period_mile_change_engine_oil` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        } 
    }



    public function getPeriodMileChangeEngineOil($conn,  $license_plate)
    {
        try {
            $sql = 'SELECT * FROM `period_mile_change_engine_oil` WHERE license_plate = :license_plate';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewPeriodMileChangeEngineOil(
        $conn,
        $date,
        $license_plate,
        $number_km_change_engine_oil_next_time,
        $number_km_change_next_timegear_oil_,
        $number_km_aut_ja_b_wheel_next_time,
        $createBy
    ) {

        try {
            $sql = "
                insert into period_mile_change_engine_oil(
                   license_plate, date, number_km_change_engine_oil_next_time, number_km_change_next_timegear_oil_, number_km_aut_ja_b_wheel_next_time, create_by, create_date
                ) values (
                    :license_plate, :date, :number_km_change_engine_oil_next_time, :number_km_change_next_timegear_oil_, :number_km_aut_ja_b_wheel_next_time,:create_by, sysdate()
                )
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':number_km_change_engine_oil_next_time', $number_km_change_engine_oil_next_time);
            $stmt->bindParam(':number_km_change_next_timegear_oil_', $number_km_change_next_timegear_oil_);
            $stmt->bindParam(':number_km_aut_ja_b_wheel_next_time', $number_km_aut_ja_b_wheel_next_time);
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

    public function updatePeriodMileChangeEngineOil(
        $conn,
        $date,
        $license_plate,
        $number_km_change_engine_oil_next_time,
        $number_km_change_next_timegear_oil_,
        $number_km_aut_ja_b_wheel_next_time,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE period_mile_change_engine_oil SET 
                date = :date, 
                number_km_change_engine_oil_next_time = :number_km_change_engine_oil_next_time, 
                number_km_change_next_timegear_oil_ = :number_km_change_next_timegear_oil_, 
                number_km_aut_ja_b_wheel_next_time = :number_km_aut_ja_b_wheel_next_time,
                update_by = :update_by, 
                update_date = sysdate()
                where  license_plate = :license_plate
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':number_km_change_engine_oil_next_time', $number_km_change_engine_oil_next_time);
            $stmt->bindParam(':number_km_change_next_timegear_oil_', $number_km_change_next_timegear_oil_);
            $stmt->bindParam(':number_km_aut_ja_b_wheel_next_time', $number_km_aut_ja_b_wheel_next_time);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deletePeriodMileChangeEngineOil($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
