<?php

class GasStationModel
{

    function __construct()
    {
    }

    public function getAllGasStation($conn)
    {
        try {
            $sql = "SELECT * FROM `gas_station` ORDER BY id DESC;";
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

    public function getGasStation($conn, $gas_station_code)
    {
        try {
            $sql = "SELECT * FROM `gas_station` WHERE gas_station_code = :gas_station_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':gas_station_code', $gas_station_code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewGasStation(
        $conn,
        $gasStationCode,
        $gasStationName,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `gas_station`(
                `gas_station_code`,
                `gas_station_name`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :gas_station_code,
                :gas_station_name,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':gas_station_code', $gasStationCode);
            $stmt->bindParam(':gas_station_name', $gasStationName);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function updateGasStation(
        $conn,
        $gasStationCode,
        $gasStationName,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `gas_station`
        SET
            `gas_station_name` = :gas_station_name,
            `update_by` = :update_by,
            `update_date` = SYSDATE()
        WHERE
            `gas_station_code` = :gas_station_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':gas_station_code', $gasStationCode);
            $stmt->bindParam(':gas_station_name', $gasStationName);
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

    public function deleteGasStation($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
