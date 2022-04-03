<?php

class TruckRegistrationBackPartModel {

    function __construct() {
        
    }

    public function getAllTruckRegistrationBackPart($conn) {
        try {
            $sql = "SELECT 
            a.which_part,
            a.license_plate,
            a.truck_brand_id,
            ( SELECT truck_brand_name FROM truck_brand b WHERE b.truck_brand_id = a.truck_brand_id ) AS truck_brand_name,
            a.truck_color_id,
            (select color_name from color c where c.id = a.truck_color_id) as truck_color_name,
            a.truck_model_code,
            a.truck_type_id,
            (select truck_type_name from truck_type d where d.truck_type_id = a.truck_type_id) as truck_type_name,
            a.province_id,
            (select province_name from province e where e.province_id = a.province_id) as province_name,
            a.registration_date,
            a.registration_date_expired,
            a.technical_renewal_date,
            a.technical_expired_date,
            a.driver_license,
            a.annual_fee_receipt,
            a.tungsit_renewal_date,
            a.tungsit_renewal_expired_date,
            a.insurance_renewal_date,
            a.insurance_expired_date,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM truck_registration_back_part a ORDER BY a.register_back_id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckRegistrationBackPart($conn, $licensePlate) {
        try {
            $sql = "SELECT
                a.which_part,
                a.license_plate,
                a.truck_brand_id,
                ( SELECT truck_brand_name FROM truck_brand b WHERE b.truck_brand_id = a.truck_brand_id ) AS truck_brand_name,
                a.truck_color_id,
                (select color_name from color c where c.id = a.truck_color_id) as truck_color_name,
                a.truck_model_code,
                a.truck_type_id,
                (select truck_type_name from truck_type d where d.truck_type_id = a.truck_type_id) as truck_type_name,
                a.province_id,
                (select province_name from province e where e.province_id = a.province_id) as province_name,
                a.registration_date,
                a.registration_date_expired,
                a.technical_renewal_date,
                a.technical_expired_date,
                a.driver_license,
                a.annual_fee_receipt,
                a.tungsit_renewal_date,
                a.tungsit_renewal_expired_date,
                a.insurance_renewal_date,
                a.insurance_expired_date,
                a.create_by,
                a.create_date,
                a.update_by,
                a.update_date 
            FROM
                truck_registration_back_part a
            WHERE a.which_part = 1 and 	license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireTruck($conn, $licensePlate) {
        try {
            $sql = "SELECT which_part, wheel_position, tire_code, changed_tire_latest_date, tire_brand_id, tire_size FROM tire_truck where which_part = 1 AND license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkLicensePlateOnBackPart($conn, $licensePlate) {
        try {
            $sql = "select license_plate from truck_registration_back_part where license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkLicensePlateOnTireTruck($conn, $licensePlate) {
        try {
            $sql = "select license_plate from tire_truck where license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function createNewRegistrationBackPart(
            $conn,
            $license_plate,
            $truck_brand_id,
            $truck_color_id,
            $truck_model_code,
            $truck_type_id,
            $province_id,
            $registration_date,
            $registration_date_expired,
            $technical_renewal_date,
            $technical_expired_date,
            $driver_license,
            $annual_fee_receipt,
            $tungsit_renewal_date,
            $tungsit_renewal_expired_date,
            $insurance_renewal_date,
            $insurance_expired_date,
            $create_by
    ) {

        try {
            $sql = "INSERT INTO truck_registration_back_part(
                        which_part,
                        license_plate,
                        truck_brand_id,
                        truck_color_id,
                        truck_model_code,
                        truck_type_id, 
                        province_id, 
                        registration_date, 
                        registration_date_expired, 
                        technical_renewal_date, 
                        technical_expired_date, 
                        driver_license, 
                        annual_fee_receipt, 
                        tungsit_renewal_date, 
                        tungsit_renewal_expired_date, 
                        insurance_renewal_date, 
                        insurance_expired_date, 
                        create_by, 
                        create_date) 
                VALUES (
                        1,
                        :license_plate,
                        :truck_brand_id,
                        :truck_color_id,
                        :truck_model_code,
                        :truck_type_id, 
                        :province_id, 
                        :registration_date, 
                        :registration_date_expired, 
                        :technical_renewal_date, 
                        :technical_expired_date, 
                        :driver_license, 
                        :annual_fee_receipt, 
                        :tungsit_renewal_date, 
                        :tungsit_renewal_expired_date, 
                        :insurance_renewal_date, 
                        :insurance_expired_date, 
                        :create_by, 
                        sysdate()
                )";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
            $stmt->bindParam(':truck_color_id', $truck_color_id);
            $stmt->bindParam(':truck_model_code', $truck_model_code);
            $stmt->bindParam(':truck_type_id', $truck_type_id);
            $stmt->bindParam(':province_id', $province_id);
            $stmt->bindParam(':registration_date', $registration_date);
            $stmt->bindParam(':registration_date_expired', $registration_date_expired);
            $stmt->bindParam(':technical_renewal_date', $technical_renewal_date);
            $stmt->bindParam(':technical_expired_date', $technical_expired_date);
            $stmt->bindParam(':driver_license', $driver_license);
            $stmt->bindParam(':annual_fee_receipt', $annual_fee_receipt);
            $stmt->bindParam(':tungsit_renewal_date', $tungsit_renewal_date);
            $stmt->bindParam(':tungsit_renewal_expired_date', $tungsit_renewal_expired_date);
            $stmt->bindParam(':insurance_renewal_date', $insurance_renewal_date);
            $stmt->bindParam(':insurance_expired_date', $insurance_expired_date);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateRegistrationBackPart(
            $conn,
            $which_part,
            $license_plate,
            $truck_brand_id,
            $truck_color_id,
            $truck_model_code,
            $truck_type_id,
            $province_id,
            $registration_date,
            $registration_date_expired,
            $technical_renewal_date,
            $technical_expired_date,
            $driver_license,
            $annual_fee_receipt,
            $tungsit_renewal_date,
            $tungsit_renewal_expired_date,
            $insurance_renewal_date,
            $insurance_expired_date,
            $updateBy
    ) {
        try {

            $sql = "UPDATE truck_registration_back_part SET 
                        truck_brand_id=:truck_brand_id,
                        truck_color_id=:truck_color_id,
                        truck_model_code=:truck_model_code,
                        truck_type_id=:truck_type_id,
                        province_id=:province_id,
                        registration_date=:registration_date,
                        registration_date_expired=:registration_date_expired,
                        technical_renewal_date=:technical_expired_date,
                        technical_expired_date=:technical_expired_date,
                        driver_license=:driver_license,
                        annual_fee_receipt=:annual_fee_receipt,
                        tungsit_renewal_date=:tungsit_renewal_date,
                        tungsit_renewal_expired_date=:tungsit_renewal_expired_date,
                        insurance_renewal_date=:insurance_renewal_date,
                        insurance_expired_date=:insurance_expired_date,
                        update_by=:update_by,
                        update_date=sysdate() 
                WHERE which_part=1 and license_plate=:license_plate";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
            $stmt->bindParam(':truck_color_id', $truck_color_id);
            $stmt->bindParam(':truck_model_code', $truck_model_code);
            $stmt->bindParam(':truck_type_id', $truck_type_id);
            $stmt->bindParam(':province_id', $province_id);
            $stmt->bindParam(':registration_date', $registration_date);
            $stmt->bindParam(':registration_date_expired', $registration_date_expired);
            $stmt->bindParam(':technical_renewal_date', $technical_renewal_date);
            $stmt->bindParam(':technical_expired_date', $technical_expired_date);
            $stmt->bindParam(':driver_license', $driver_license);
            $stmt->bindParam(':annual_fee_receipt', $annual_fee_receipt);
            $stmt->bindParam(':tungsit_renewal_date', $tungsit_renewal_date);
            $stmt->bindParam(':tungsit_renewal_expired_date', $tungsit_renewal_expired_date);
            $stmt->bindParam(':insurance_renewal_date', $insurance_renewal_date);
            $stmt->bindParam(':insurance_expired_date', $insurance_expired_date);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function deleteTruckType($conn, $truckTypeCode) {
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
