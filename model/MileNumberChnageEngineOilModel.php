<?php

class MileNumberChnageEngineOilModel
{


    function __construct()
    {
    }

    public function getAllMileNumberChnageEngineOil($conn)
    {
        try {
            $sql = 'SELECT
            a.`id`,
            a.`license_plate`,
            (
            SELECT
                (
                SELECT
                    c.truck_type_code
                FROM
                    truck_type c
                WHERE
                    c.truck_type_code = b.truck_type_code
            )
        FROM
            truck_registration_head b
        WHERE
            b.registration_code = a.license_plate
        ) AS truck_type_code,
        (
            SELECT
                (
                SELECT
                    c.truck_type_name
                FROM
                    truck_type c
                WHERE
                    c.truck_type_code = b.truck_type_code
            )
        FROM
            truck_registration_head b
        WHERE
            b.registration_code = a.license_plate
        ) AS truck_type_name,
        a.`date`,
        a.`km_number_change_engine_oil_next_time`,
        a.`km_number_change_gear_oil_next_time`,
        a.`km_number_change_autjabee_wheel_next_time`,
        a.`create_by`,
        a.`create_date`,
        a.`update_by`,
        a.`update_date`
        FROM
            `change_oil` a
        ORDER BY id DESC';
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



    public function getMileNumberChnageEngineOil($conn,  $license_plate)
    {
        try {
            $sql = 'SELECT
            a.`id`,
            a.`license_plate`,
            (
            SELECT
                (
                SELECT
                    c.truck_type_code
                FROM
                    truck_type c
                WHERE
                    c.truck_type_code = b.truck_type_code
            )
        FROM
            truck_registration_head b
        WHERE
            b.registration_code = a.license_plate
        ) AS truck_type_code,
        (
            SELECT
                (
                SELECT
                    c.truck_type_name
                FROM
                    truck_type c
                WHERE
                    c.truck_type_code = b.truck_type_code
            )
        FROM
            truck_registration_head b
        WHERE
            b.registration_code = a.license_plate
        ) AS truck_type_name,
        a.`date`,
        a.`km_number_change_engine_oil_next_time`,
        a.`km_number_change_gear_oil_next_time`,
        a.`km_number_change_autjabee_wheel_next_time`,
        a.`create_by`,
        a.`create_date`,
        a.`update_by`,
        a.`update_date`
        FROM
            `change_oil` a
        WHERE a.license_plate = :license_plate
        ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewMileNumberChnageEngineOil(
        $conn,
        $licensePlate,
        $date,
        $changeEngineOilNextTime,
        $changeGearOilNextTime,
        $changeAutjabeeNextTime,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `change_oil`(
                `license_plate`,
                `date`,
                `km_number_change_engine_oil_next_time`,
                `km_number_change_gear_oil_next_time`,
                `km_number_change_autjabee_wheel_next_time`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :license_plate,                :date,
                :km_number_change_engine_oil_next_time,
                :km_number_change_gear_oil_next_time,
                :km_number_change_autjabee_wheel_next_time,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':km_number_change_engine_oil_next_time', $changeEngineOilNextTime);
            $stmt->bindParam(':km_number_change_gear_oil_next_time', $changeGearOilNextTime);
            $stmt->bindParam(':km_number_change_autjabee_wheel_next_time', $changeAutjabeeNextTime);
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

    public function updateMileNumberChnageEngineOil(
        $conn,
        $licensePlate,
        $date,
        $changeEngineOilNextTime,
        $changeGearOilNextTime,
        $changeAutjabeeNextTime,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `change_oil`
        SET
            `date` = sysdate(),
            `km_number_change_engine_oil_next_time` = :km_number_change_engine_oil_next_time,
            `km_number_change_gear_oil_next_time` = :km_number_change_gear_oil_next_time,
            `km_number_change_autjabee_wheel_next_time` = :km_number_change_autjabee_wheel_next_time,
            `update_by` = :update_by,
            `update_date` = sysdate()
        WHERE
            `license_plate` = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $licensePlate);
            // $stmt->bindParam(':date', $date);
            $stmt->bindParam(':km_number_change_engine_oil_next_time', $changeEngineOilNextTime);
            $stmt->bindParam(':km_number_change_gear_oil_next_time', $changeGearOilNextTime);
            $stmt->bindParam(':km_number_change_autjabee_wheel_next_time', $changeAutjabeeNextTime);
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

    public function deleteMileNumberChnageEngineOil($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
