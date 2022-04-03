<?php

class TruckSharingModel
{

    function __construct()
    {
    }

    public function getAllTruckSharing($conn)
    {
        try {
            $sql = 'SELECT
            a.license_plate,
            a.province_id,
            ( SELECT b.province_name FROM province b WHERE b.province_id = a.province_id ) AS province_name,
            a.truck_brand_id,
            ( SELECT c.truck_brand_name FROM truck_brand c WHERE c.truck_brand_id = a.truck_brand_id ) AS truck_brand_name,
            a.truck_type_code,
            ( SELECT d.truck_type_name FROM truck_type d WHERE d.truck_type_code = a.truck_type_code ) AS truck_type_name,
            a.owner_truck_code,
            ( SELECT e.`name` FROM owner_truck_sharing e WHERE e.`code` = a.owner_truck_code) AS owner_truck_name,
            a.truck_registration_back_part,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date 
        FROM
            trukc_sharing a 
        ORDER BY
            create_date DESC;';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckSharing($conn, $license_plate)
    {
        try {
            $sql = 'SELECT
                a.license_plate,
                a.province_id,
                ( SELECT b.province_name FROM province b WHERE b.province_id = a.province_id ) AS province_name,
                a.truck_brand_id,
                ( SELECT c.truck_brand_name FROM truck_brand c WHERE c.truck_brand_id = a.truck_brand_id ) AS truck_brand_name,
                a.truck_type_code,
                ( SELECT d.truck_type_name FROM truck_type d WHERE d.truck_type_code = a.truck_type_code ) AS truck_type_name,
                a.owner_truck_code,
                ( SELECT e.`name` FROM owner_truck_sharing e WHERE e.`code` = a.owner_truck_code) AS owner_truck_name,
                a.truck_registration_back_part,
                a.create_by,
                a.create_date,
                a.update_by,
                a.update_date 
            FROM
                trukc_sharing a 
            WHERE 
                license_plate = :license_plate';

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTruckSharing(
        $conn,
        $licensePlate,
        $provinceId,
        $trucBrandId,
        $truckTypeCode,
        $ownerTruckCode,
        $truckRegistrationBackPart,
        $createBy
    ) {
        $sql = "INSERT INTO `trukc_sharing` (
            `license_plate`,
            `province_id`,
            `truck_brand_id`,
            `truck_type_code`,
            `owner_truck_code`,
            `truck_registration_back_part`,
            `create_by`, `create_date`) 
        VALUES (
            :license_plate, :province_id, :truck_brand_id, :truck_type_code, 
            :owner_truck_id, :truck_registration_back_part,
            :create_by, sysdate()
        )";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':license_plate', $licensePlate);
        $stmt->bindParam(':province_id', $provinceId);
        $stmt->bindParam(':truck_brand_id', $trucBrandId);
        $stmt->bindParam(':truck_type_code', $truckTypeCode);
        $stmt->bindParam(':owner_truck_id', $ownerTruckCode);
        $stmt->bindParam(':truck_registration_back_part', $truckRegistrationBackPart);
        $stmt->bindParam(':create_by', $createBy);
        if ($stmt->execute()) {
            return true;
        }
        return false;
        try {
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateTruckSharing(
        $conn,
        $licensePlate,
        $provinceId,
        $trucBrandId,
        $truckTypeCode,
        $ownerTruckCode,
        $truckRegistrationBackPart,
        $updateBy
    ) {
        try {

            $sql = "UPDATE `trukc_sharing` SET 
            `province_id` = :province_id,
            `truck_brand_id` = :truck_brand_id,
            `truck_type_code` = :truck_type_code,
            `owner_truck_code` = :owner_truck_code,
            `truck_registration_back_part` = :truck_registration_back_part,
            `update_by` = :update_by, 
            `update_date` = sysdate() 
            where license_plate = :license_plate";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':province_id', $provinceId);
            $stmt->bindParam(':truck_brand_id', $trucBrandId);
            $stmt->bindParam(':truck_type_code', $truckTypeCode);
            $stmt->bindParam(':owner_truck_code', $ownerTruckCode);
            $stmt->bindParam(':truck_registration_back_part', $truckRegistrationBackPart);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':license_plate', $licensePlate);

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
            $sql = 'delete from truck_type where truck_type_code = :truck_type_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':truck_type_code', $truckTypeCode);
            $stmt->execute();
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
