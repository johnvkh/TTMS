<?php

class DriverTruckRegularUseModel
{


    function __construct()
    {
    }

    public function getAllDriverTruckRegularUse($conn)
    {
        try {
            $sql = 'SELECT
            a.driver_code,
            ( SELECT concat( b.firstname, " ", b.lastname ) AS fullname FROM driver b WHERE b.driver_code = a.driver_code ) AS fullname,
            a.license_plate,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            driver_truck_regular_use a 
        ORDER BY
            a.id DESC';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getDriverTruckRegularUse($conn, $driverCode)
    {
        try {
            $sql = 'select a.driver_code,
                         (select concat(b.firstname, " ", b.lastname) as fullname 
                         from driver b where b.driver_code = a.driver_code) as fullname,
                         a.license_plate,
                         a.create_by,
                         a.create_date,
                         a.update_by,
                         a.update_date
                         from driver_truck_regular_use a where a.driver_code = :driver_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':driver_code', $driverCode);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDriverTruckRegularUseModel($conn, $driverCode, $licensePlate, $createBy)
    {

        try {
            $sql = "insert into driver_truck_regular_use (driver_code, license_plate, create_by, create_date) 
            values (:driver_code, :license_plate, :create_by, sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':driver_code', $driverCode);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateDriverTruckRegularUse($conn, $driverCode, $licensePlate, $updateBy)
    {
        try {
            $sql = "update driver_truck_regular_use  set 
             license_plate = :license_plate, 
             update_by = :update_by,
             update_date = sysdate()
             where  driver_code = :driver_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':driver_code', $driverCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteTruckType($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
