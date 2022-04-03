<?php

class ChangeEngineOilModel
{
    function __construct()
    {
    }

    public function getAllChangeEngineOil($conn)
    {
        try {
            $sql = "SELECT
            a.license_plate,
            (
            SELECT
                ( SELECT e.truck_type_code FROM truck_type e WHERE e.truck_type_code = b.truck_type_code ) 
            FROM
                truck_registration_head b 
            WHERE
                b.registration_code = a.license_plate 
            ) AS truck_type_code,
            (
            SELECT
                ( SELECT e.truck_type_name FROM truck_type e WHERE e.truck_type_code = b.truck_type_code ) 
            FROM
                truck_registration_head b 
            WHERE
                b.registration_code = a.license_plate 
            ) AS truck_type_name,
            a.km_number_change_engine_oil_next_time as km_number_change_engine_oil_next_time,
            a.km_number_change_gear_oil_next_time as km_number_change_gear_oil_next_time,
            a.km_number_change_autjabee_wheel_next_time as km_number_change_autjabee_wheel_next_time,
            a.create_by as create_by,
            a.create_date as create_date,
            a.update_by as update_by,
            a.update_date  as update_date
        FROM
            change_oil a  ORDER BY update_date,id DESC";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getChangeEngineOil($conn, $licensePlate)
    {
        try {
            $sql = "SELECT
                a.license_plate,
                (
                SELECT
                    ( SELECT e.truck_type_code FROM truck_type e WHERE e.truck_type_code = b.truck_type_code ) 
                FROM
                    truck_registration_head b 
                WHERE
                    b.registration_code = a.license_plate 
                ) AS truck_type_code,
                (
                SELECT
                    ( SELECT e.truck_type_name FROM truck_type e WHERE e.truck_type_code = b.truck_type_code ) 
                FROM
                    truck_registration_head b 
                WHERE
                    b.registration_code = a.license_plate 
                ) AS truck_type_name,
                a.km_number_change_engine_oil_next_time as km_number_change_engine_oil_next_time,
                a.km_number_change_gear_oil_next_time as km_number_change_gear_oil_next_time,
                a.km_number_change_autjabee_wheel_next_time as km_number_change_autjabee_wheel_next_time,
                a.create_by as create_by,
                a.create_date as create_date,
                a.update_by as update_by,
                a.update_date  as update_date
            FROM
                change_oil a 
            WHERE
                a.license_plate = :licensePlate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':licensePlate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function createNewChangeEngineOil(
        $conn,
        $license_plate,
        $km_number_change_engine_oil_next_time,
        $km_number_change_gear_oil_next_time,
        $km_number_change_autjabee_wheel_next_time,
        $createBy
    ) {

        try {
            $sql  = "INSERT INTO change_oil (license_plate, date, km_number_change_engine_oil_next_time, km_number_change_gear_oil_next_time, km_number_change_autjabee_wheel_next_time, create_by, create_date) 
            VALUES (:license_plate, SYSDATE(), :km_number_change_engine_oil_next_time, :km_number_change_gear_oil_next_time, :km_number_change_autjabee_wheel_next_time, :create_by, SYSDATE())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':km_number_change_engine_oil_next_time', $km_number_change_engine_oil_next_time);
            $stmt->bindParam(':km_number_change_gear_oil_next_time', $km_number_change_gear_oil_next_time);
            $stmt->bindParam(':km_number_change_autjabee_wheel_next_time', $km_number_change_autjabee_wheel_next_time);
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

    public function updateChangeEngineOil(
        $conn,
        $license_plate,
        $km_number_change_engine_oil_next_time,
        $km_number_change_gear_oil_next_time,
        $km_number_change_autjabee_wheel_next_time,
        $update_by
    ) {
        try {
            $sql = "UPDATE change_oil 
            SET date = SYSDATE(),
            km_number_change_engine_oil_next_time = :km_number_change_engine_oil_next_time,
            km_number_change_gear_oil_next_time = :km_number_change_gear_oil_next_time,
            km_number_change_autjabee_wheel_next_time = :km_number_change_autjabee_wheel_next_time,
            update_by = :update_by,
            update_date = SYSDATE()
            WHERE
                license_plate = :license_plate";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':km_number_change_engine_oil_next_time', $km_number_change_engine_oil_next_time);
            $stmt->bindParam(':km_number_change_gear_oil_next_time', $km_number_change_gear_oil_next_time);
            $stmt->bindParam(':km_number_change_autjabee_wheel_next_time', $km_number_change_autjabee_wheel_next_time);
            $stmt->bindParam(':update_by', $update_by);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return true;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function deleteTireHistory($conn, $truckTypeCode)
    {
        try {
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
