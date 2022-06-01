<?php

class TruckRegistrationHeadPartModel {

    function __construct() {
        
    }

    public function getAllTruckRegistrationHeadPart($conn) {
        try {
            $sql = "SELECT
            a.which_part,
            a.license_plate,
            a.truck_brand_id,
            (SELECT truck_brand_name FROM truck_brand b WHERE b.truck_brand_id = a.truck_brand_id) AS truck_brand_name,
            a.truck_color_id,
            (select color_name from color c where c.id = a.truck_color_id) as truck_color_name,
            a.truck_model_code,
            a.truck_type_id,
            (select truck_type_name from truck_type d where d.truck_type_id = a.truck_type_id) as truck_type_name,
            a.province_id,
            (select province_name from province e where e.province_id = a.province_id) as province_name,
            a.truck_engine_number,
            a.truck_body_number,
            a.power,
            a.battery_id,
            (select battery_name from battery f where f.id = a.battery_id) as battery_name,
            a.change_battery_latest_date,
            a.fire_extinguisher,
            a.front_camera,
            a.tools,
            a.card_cloth,
            a.gps,
            a.vertical_traffic,
            a.straps,
            a.chains,
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
            truck_registration_head_part a 
        ORDER BY
            a.register_head_id DESC";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute()) {
                echo "error";
            }
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckRegistrationHeadPart($conn, $licensePlate) {
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
            a.truck_engine_number,
            a.truck_body_number,
            a.power,
            a.battery_id,
            (select battery_name from battery f where f.id = a.battery_id) as battery_name,
            a.change_battery_latest_date,
            a.fire_extinguisher,
            a.front_camera,
            a.tools,
            a.card_cloth,
            a.gps,
            a.vertical_traffic,
            a.straps,
            a.chains,
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
            FROM truck_registration_head_part a WHERE a.license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckRegistrationByLicensePlate($conn, $license_plate) {
        try {

            $sql = "select license_plate,
            truck_brand_id,
            truck_color_id,
            truck_model_code, 
            truck_type_id, 
            province_id, 
            truck_engine_number, 
            truck_body_number, 
            power, 
            batter_id, 
            changed_battery_latest_date, 
            fire_extinguisher, 
            front_camera, 
            tools, 
            card_cloth, 
            gps, 
            vertical_traffic,
            straps,
            chains,
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
            create_date,
            update_by,
            update_date
            from truck_registration_head_part 
            where license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getChangeEnginOil($conn, $licensePlate) {
        try {
            $sql = "SELECT license_plate, km_number_change_gear_oil_next_time, km_number_change_engine_oil_next_time FROM change_oil WHERE license_plate = :license_plate;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireTruck($conn, $licensePlate) {
        try {
            $sql = "SELECT which_part, wheel_position, tire_code, changed_tire_latest_date, tire_brand_id, tire_size FROM tire_truck where which_part = 0 AND license_plate = :license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createTruckRegistrationHeadPart(
            $conn,
            $which_part,
            $license_plate,
            $truck_brand_id,
            $truck_color_id,
            $truck_model_code,
            $truck_type_id,
            $province_id,
            $truck_engine_number,
            $truck_body_number,
            $power,
            $battery_id,
            $change_battery_latest_date,
            $fire_extinguisher,
            $front_camera,
            $tools,
            $card_cloth,
            $gps,
            $vertical_traffic,
            $straps,
            $chains,
            $registration_date,
            $registration_date_expire,
            $technical_renewal_date,
            $technical_expired_date,
            $driver_license,
            $annual_fee_receipt,
            $tungsit_renewal_date,
            $tungsit_renewal_expired_date,
            $insurance_renewal_date,
            $insurance_expired_date,
            $kmChangeEngineOilNextTime,
            $kmChangeGearOilNextTime,
            $create_by
    ) {

        try {
            $sql = "INSERT INTO truck_registration_head_part(
				which_part,
				license_plate,
				truck_brand_id,
				truck_color_id,
				truck_model_code,
				truck_type_id,
				province_id,
				truck_engine_number,
				truck_body_number,
				power,
				battery_id,
				change_battery_latest_date,
				fire_extinguisher,
				front_camera,
				tools,
				card_cloth,
				gps,
				vertical_traffic,
				straps,
				chains,
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
				:which_part,
				:license_plate,
				:truck_brand_id,
				:truck_color_id,
				:truck_model_code,
				:truck_type_id,
				:province_id,
				:truck_engine_number,
				:truck_body_number,
				:power,
				:battery_id,
				:change_battery_latest_date,
				:fire_extinguisher,
				:front_camera,
				:tools,
				:card_cloth,
				:gps,
				:vertical_traffic,
				:straps,
				:chains,
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
				sysdate())";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $which_part);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':truck_brand_id', $truck_brand_id);
            $stmt->bindParam(':truck_color_id', $truck_color_id);
            $stmt->bindParam(':truck_model_code', $truck_model_code);
            $stmt->bindParam(':truck_type_id', $truck_type_id);
            $stmt->bindParam(':province_id', $province_id);
            $stmt->bindParam(':truck_engine_number', $truck_engine_number);
            $stmt->bindParam(':truck_body_number', $truck_body_number);
            $stmt->bindParam(':power', $power);
            $stmt->bindParam(':battery_id', $battery_id);
            $stmt->bindParam(':change_battery_latest_date', $change_battery_latest_date);
            $stmt->bindParam(':fire_extinguisher', $fire_extinguisher);
            $stmt->bindParam(':front_camera', $front_camera);
            $stmt->bindParam(':tools', $tools);
            $stmt->bindParam(':card_cloth', $card_cloth);
            $stmt->bindParam(':gps', $gps);
            $stmt->bindParam(':vertical_traffic', $vertical_traffic);
            $stmt->bindParam(':straps', $straps);
            $stmt->bindParam(':chains', $chains);
            $stmt->bindParam(':registration_date', $registration_date);
            $stmt->bindParam(':registration_date_expired', $registration_date_expire);
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
                if (!$this->insertChangeOil($conn, $license_plate, $kmChangeEngineOilNextTime, $kmChangeGearOilNextTime, $create_by)) {
                    return false;
                }
                return true;
            }
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function insertChangeOil($conn, $licensePlate, $kmChangeEngineOilNextTime, $kmChangeGearOilNextTime, $createBy) {
        try {

            $sql_insert = "INSERT INTO change_oil(license_plate,km_number_change_engine_oil_next_time,
		km_number_change_gear_oil_next_time,
		create_by,
		create_date
		) 
            VALUES ('" . $licensePlate . "'," . $kmChangeEngineOilNextTime . "," . $kmChangeGearOilNextTime . ",'" . $createBy . "',sysdate())";
            $stmt = $conn->prepare($sql_insert);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateTruckRegistrationHeadPartByLicensePlate(
            $conn,
            $which_part,
            $license_plate,
            $truck_brand_id,
            $truck_color_id,
            $truck_model_code,
            $truck_type_id,
            $province_id,
            $truck_engine_number,
            $truck_body_number,
            $power,
            $battery_id,
            $change_battery_latest_date,
            $fire_extinguisher,
            $front_camera,
            $tools,
            $card_cloth,
            $gps,
            $vertical_traffic,
            $straps,
            $chains,
            $registration_date,
            $registration_date_expire,
            $technical_renewal_date,
            $technical_expired_date,
            $driver_license,
            $annual_fee_receipt,
            $tungsit_renewal_date,
            $tungsit_renewal_expired_date,
            $insurance_renewal_date,
            $insurance_expired_date,
            $kmChangeEngineOilNextTime,
            $kmChangeGearOilNextTime,
            $updateBy
    ) {
        try {

            $sql_update_truck_registration_head_part = "UPDATE truck_registration_head_part SET 
		truck_brand_id= ".$truck_brand_id.",
		truck_color_id= ".$truck_color_id.",
		truck_model_code='".$truck_model_code."',
		truck_type_id='".$truck_type_id."',
		province_id='".$province_id."',
		truck_engine_number='".$truck_engine_number."',
		truck_body_number='".$truck_body_number."',
		power='".$power."',
		battery_id='".$battery_id."',
		change_battery_latest_date='".$change_battery_latest_date."',
		fire_extinguisher=".$fire_extinguisher.",
		front_camera=".$front_camera.",
		tools=".$tools.",
		card_cloth=".$card_cloth.",
		gps=".$gps.",
		vertical_traffic=".$vertical_traffic.",
		straps=".$straps.",
		chains=".$chains.",
		registration_date='".$registration_date."',
		registration_date_expired='".$registration_date_expire."',
		technical_renewal_date='".$technical_renewal_date."',
		technical_expired_date='".$technical_expired_date."',
		driver_license='".$driver_license."',
		annual_fee_receipt='".$annual_fee_receipt."',
		tungsit_renewal_date='".$tungsit_renewal_date."',
		tungsit_renewal_expired_date='".$tungsit_renewal_expired_date."',
		insurance_renewal_date='".$insurance_renewal_date."',
		insurance_expired_date='".$insurance_expired_date."',
		update_by='".$updateBy."',
		update_date=sysdate()
		WHERE which_part='".$which_part."' and license_plate='".$license_plate."'";
            $stmt = $conn->prepare($sql_update_truck_registration_head_part);
            if ($stmt->execute()) {
                if (!$this->updateChangeOil($conn, $license_plate, $kmChangeEngineOilNextTime, $kmChangeGearOilNextTime, $updateBy)) {
                    return false;
                }

                return true;
            }
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateChangeOil($conn, $licensePlate, $kmChangeEngineOilNextTime, $kmChangeGearOilNextTime, $updateBy) {
        try {
            $sql_update_change_oil = "update change_oil
            set license_plate = :license_plate,
            km_number_change_engine_oil_next_time = :km_number_change_engine_oil_next_time,
            km_number_change_gear_oil_next_time = :km_number_change_gear_oil_next_time,
            update_by = :update_by,
            update_date = SYSDATE() 
            where license_plate = :license_plate";

            $stmt = $conn->prepare($sql_update_change_oil);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':km_number_change_engine_oil_next_time', $kmChangeEngineOilNextTime);
            $stmt->bindParam(':km_number_change_gear_oil_next_time', $kmChangeGearOilNextTime);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkLicensePlate($conn, $licensePlate) {
        try {
            $sql = "select license_plate from truck_registration_head_part where license_plate = :license_plate";
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

    public function checkLicensePlateOnChangeOil($conn, $licensePlate) {
        try {
            $sql = "select license_plate from change_oil where license_plate = :license_plate";
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

    public function checkDuplicateWheelPosition($conn, $whichPart, $licensePlate, $wheelPosition) {
        try {
            $sql = "select which_part, license_plate, wheel_position from tire_truck where which_part = :which_part and license_plate = :license_plate and wheel_position = :wheel_position";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':wheel_position', $wheelPosition);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            echo ('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

}
