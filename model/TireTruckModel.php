<?php

class TireTruckModel {

    function __construct() {
        
    }

    public function getAllTireTruck($conn, $license_plate, $which_part) {
        try {
            $sql = "SELECT
            a.tire_truck_id,
            a.which_part,
            a.license_plate,
            a.wheel_position,
            a.wheel_position_code,
            a.tire_code,
            a.changed_tire_latest_date,
            a.tire_brand_id,
            a.tire_size,
            a.reason_change_tire,
            a.mile_should_change_tire,
            a.status,
            a.create_by as create_by,
            a.create_date as create_date,
            a.update_by as update_by,
            a.update_date  as update_date
        FROM
            tire_truck a 
        WHERE status =1 and
            a.which_part = :which_part
            AND a.license_plate = :license_plate";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $which_part);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireTruck($conn, $whichPart, $licensePlate, $wheelPosition) {
        try {
            $sql = 'SELECT * FROM tire_truck WHERE status=1 and which_part = ' . $whichPart . ' and license_plate = ' . $licensePlate . ' ';

            if (!empty($wheelPosition)) {
                $sql .= " and wheel_position = :wheel_position ";
            }
            $stmt = $conn->prepare($sql);
            if (!empty($wheelPosition)) {
                $stmt->bindParam(':wheel_position', $wheelPosition);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireTruckForUpdate($conn, $whichPart, $licensePlate) {
        try {
            $sql = 'SELECT * FROM tire_truck WHERE which_part = ' . $whichPart . ' and license_plate = ' . $licensePlate . ' ';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportTireWillBeExpiringMile($conn) {
        try {
            $sql = "SELECT
                a.license_plate,
                a.which_part,
                (
                SELECT
                    ( SELECT c.truck_type_name FROM truck_type c WHERE c.truck_type_code = b.truck_type_code ) 
                FROM
                    truck_registration_head b 
                WHERE
                    b.registration_code = a.license_plate 
                ) AS truck_type_name,
                a.current_date_truck_change_tire,
                a.current_truck_mile_number_change_tire,
                a.mile_should_change_tire,
            CASE
                    WHEN a.current_truck_mile_number_change_tire > a.mile_should_change_tire THEN
                    ( a.current_truck_mile_number_change_tire - a.mile_should_change_tire ) ELSE NULL 
                END AS over_mile
            FROM
                tire_for_truck a";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportTireWillBeExpiringDate($conn) {
        try {
            $sql = "SELECT
            a.license_plate,
            a.which_part,
            (
            SELECT
                ( SELECT c.truck_type_name FROM truck_type c WHERE c.truck_type_code = b.truck_type_code ) 
            FROM
                truck_registration_head b 
            WHERE
                b.registration_code = a.license_plate 
            ) AS truck_type_name,
            a.current_date_truck_change_tire,
            a.current_date_truck_change_tire,
            a.date_should_change_tire,
        CASE
                
                WHEN a.current_date_truck_change_tire > a.date_should_change_tire THEN
                ( a.current_date_truck_change_tire - a.date_should_change_tire ) ELSE NULL 
            END AS over_date
        FROM
            tire_for_truck a ORDER BY id DESC;";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getAllTireTruckByWheelPosition($conn, $whichPart, $license_plate, $position_wheel) {
        try {
            $sql = "SELECT
            a.tire_truck_id,
            a.which_part,
            a.license_plate,
            a.wheel_position,
            a.wheel_position_code,
            a.tire_code,
            a.changed_tire_latest_date,
            a.tire_brand_id,
            a.tire_size,
            a.reason_change_tire,
            a.mile_should_change_tire,
            a.status,
            a.create_by as create_by,
            a.create_date as create_date, 
            a.update_by as update_by,
            a.update_date  as update_date
        FROM
            tire_truck a 
        WHERE
            a.which_part = :which_part
            AND a.license_plate = :license_plate
            And a.wheel_position = :position_wheel ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':position_wheel', $position_wheel);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTireTruck($conn, $which_part, $license_plate, $wheelPosition, $wheelPositionCode, $tireCode, $changedTireLatestDate, $tireBrandId, $tireSize, $mileShouldChangeTire, $create_by) {
        try {
            $sql = "INSERT INTO tire_truck("
                    . "which_part,"
                    . "license_plate,"
                    . "wheel_position,"
                    . "wheel_position_code,"
                    . "tire_code,"
                    . "changed_tire_latest_date,"
                    . "tire_brand_id,"
                    . "tire_size,"
                    . "mile_should_change_tire,"
                    . "status,"
                    . "create_by,"
                    . "create_date) "
                    . " VALUES ("
                    . ":which_part,"
                    . ":license_plate,"
                    . ":wheel_position,"
                    . ":wheel_position_code,"
                    . ":tire_code,"
                    . ":changed_tire_latest_date,"
                    . ":tire_brand_id,"
                    . ":tire_size,"
                    . ":mile_should_change_tire,"
                    . "1,"
                    . ":create_by,"
                    . "sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $which_part);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':wheel_position', $wheelPosition);
            $stmt->bindParam(':wheel_position_code', $wheelPositionCode);
            $stmt->bindParam(':tire_code', $tireCode);
            $stmt->bindParam(':changed_tire_latest_date', $changedTireLatestDate);
            $stmt->bindParam(':tire_brand_id', $tireBrandId);
            $stmt->bindParam(':tire_size', $tireSize);
            $stmt->bindParam(':mile_should_change_tire', $mileShouldChangeTire);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateTireTruck($conn, $whichPart, $licensePlate, $wheelPosition, $wheelPositionCode, $tireCode, $changedTireLatestDate, $tireBrandId, $tireSize, $mileShouldChangeTire, $reasonForChangeTire, $status, $updateBy) {
        try {
            $reasonStr = "";
            if (!empty($reasonForChangeTire)) {
                $reasonStr = " reason_change_tire= '" . $reasonForChangeTire . "', ";
            }

            $sql = "UPDATE tire_truck SET wheel_position=:wheelPosition,wheel_position_code=:wheelPositionCode,tire_code=:tireCode,changed_tire_latest_date=:changedTireLatestDate,"
                    . "tire_brand_id=:tireBrandId,tire_size=:tireSize," . $reasonStr . " mile_should_change_tire=:mileShouldChangeTire,"
                    . "status=:status,update_by=:update_by,update_date= sysdate() WHERE wheel_position=:wheelPosition and which_part=:which_part and license_plate=:license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':wheelPositionCode', $wheelPositionCode);
            $stmt->bindParam(':tireCode', $tireCode);
            $stmt->bindParam(':changedTireLatestDate', $changedTireLatestDate);
            $stmt->bindParam(':tireBrandId', $tireBrandId);
            $stmt->bindParam(':tireSize', $tireSize);
            $stmt->bindParam(':mileShouldChangeTire', $mileShouldChangeTire);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':wheelPosition', $wheelPosition);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            if ($stmt->execute()) {
                return true;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateChangeTireTruck($conn, $reasonForChangeTire, $CreateBy, $wheelPosition, $whichPart, $licensePlate) {
        try {
            $sql = "UPDATE tire_truck SET 
                    reason_change_tire=:reason_change_tire,
                    status=0,
                    update_by=:update_by,
                    update_date= sysdate() 
                    WHERE wheel_position=:wheelPosition 
                    and which_part=:which_part 
                    and license_plate=:license_plate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':reason_change_tire', $reasonForChangeTire);
            $stmt->bindParam(':update_by', $CreateBy);
            $stmt->bindParam(':wheelPosition', $wheelPosition);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkPositionWheel($conn, $licensePlate, $wheelPosition, $whichPart) {
        try {
            $sql = "select tire_truck_id from tire_truck where status=1 and license_plate = :license_plate And which_part =:which_part ";
            if (!empty($wheelPosition)) {
                $sql .= " and wheel_position = :wheel_position";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':which_part', $whichPart);
            if (!empty($wheelPosition)) {
                $stmt->bindParam(':wheel_position', $wheelPosition);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function changeTireTruck($conn, $whichPart,
            $licensePlate,
            $wheelPosition,
            $newWheelPositionCode,
            $newTireCode,
            $newTireBrandId,
            $newTireSize,
            $newMileShouldChangeTire,
            $CreateBy) {
        try {
            $sql = "INSERT INTO tire_truck("
                    . "which_part,"
                    . "license_plate,"
                    . "wheel_position,"
                    . "wheel_position_code,"
                    . "tire_code,"
                    . "changed_tire_latest_date,"
                    . "tire_brand_id,"
                    . "tire_size,"
                    . "mile_should_change_tire,"
                    . "status,"
                    . "create_by,"
                    . "create_date) "
                    . " VALUES ("
                    . ":which_part,"
                    . ":license_plate,"
                    . ":wheel_position,"
                    . ":wheel_position_code,"
                    . ":tire_code,"
                    . "sysdate(),"
                    . ":tire_brand_id,"
                    . ":tire_size,"
                    . ":mile_should_change_tire,"
                    . "'1',"
                    . ":create_by,"
                    . "sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':wheel_position', $wheelPosition);
            $stmt->bindParam(':wheel_position_code', $newWheelPositionCode);
            $stmt->bindParam(':tire_code', $newTireCode);
            $stmt->bindParam(':tire_brand_id', $newTireBrandId);
            $stmt->bindParam(':tire_size', $newTireSize);
            $stmt->bindParam(':mile_should_change_tire', $newMileShouldChangeTire);
            $stmt->bindParam(':create_by', $CreateBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteTireTruck($conn, $newWheelPosition, $whichPart, $licensePlate) {
        try {
            $sql = "delete from tire_truck 
                    WHERE wheel_position=:wheelPosition 
                    and which_part=:which_part 
                    and license_plate=:license_plate and status=1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':wheelPosition', $newWheelPosition);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function insertTireHistory($conn, $whichPart,
            $licensePlate,
            $wheelPosition,
            $oldTireCode,
            $oldTireBrandId,
            $oldTireSize,
            $newTireCode,
            $newTireBrandId,
            $newTireSize,
            $reasonForChangeTire,
            $CreateBy) {
        try {
            $sql = "insert into tire_history (
                    which_part,
                    license_plate,
                    wheel_position,
                    old_tire_code,
                    old_tire_brand_id,
                    old_tire_size,
                    new_tire_code,
                    new_tire_brand_id,
                    new_tire_size,
                    change_tire_date,
                    reason_change_tire,
                    create_by
                    )
                    VALUES(
                    :which_part,
                    :license_plate,
                    :wheel_position,
                    :old_tire_code,
                    :old_tire_brand_id,
                    :old_tire_size,
                    :new_tire_code,
                    :new_tire_brand_id,
                    :new_tire_size,
                    sysdate(),
                    :reason_change_tire,
                    :create_by
                    )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':wheel_position', $wheelPosition);
            $stmt->bindParam(':old_tire_code', $oldTireCode);
            $stmt->bindParam(':old_tire_brand_id', $oldTireBrandId);
            $stmt->bindParam(':old_tire_size', $oldTireSize);
            $stmt->bindParam(':new_tire_code', $newTireCode);
            $stmt->bindParam(':new_tire_brand_id', $newTireBrandId);
            $stmt->bindParam(':new_tire_size', $newTireSize);
            $stmt->bindParam(':reason_change_tire', $reasonForChangeTire);
            $stmt->bindParam(':create_by', $CreateBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

}
