<?php

class DriversWageModel
{


    function __construct()
    {
    }

    public function getAllDriversWage($conn)
    {
        try {
            //     $sql = "SELECT
            //     `payment_bill`,
            //     `payment_date`,
            //     `description`,
            //     `truck_in_working_bill`,
            //     `date`,
            //     `license_plate`,
            //     `driver_code`,
            //     `allowance`,
            //     `other_allownance`,
            //     `highway_tolls`,
            //     `tire_repair`,
            //     `truck_repair`,
            //     `create_by`,
            //     `create_date`,
            //     `update_by`,
            //     `update_date`
            // FROM
            //     `drivers_wage`
            // ORDER BY id DESC;";
            $sql = "SELECT a.id, a.payment_bill, a.payment_date, a.description, a.truck_in_working_bill, a.date, a.license_plate, a.driver_code, (SELECT concat(b.firstname, ' ', b.lastname) FROM driver b WHERE b.driver_code = a.driver_code) as driver_name, a.allowance, a.highway_tolls, a.tire_repair, a.truck_repair, a.create_by, a.create_date, a.update_by, a.update_date FROM drivers_wage a ORDER BY a.id DESC";
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

    public function checkLicensePlate($conn, $licensePlate)
    {
        try {
            $sql = "select license_plate from drivers_wage where license_plate = :licensePlate AND DATE_FORMAT( payment_date, '%Y-%m' ) = DATE_FORMAT( SYSDATE(), '%Y-%m' );";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':licensePlate', $licensePlate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getDriversWage($conn,  $payment_bill)
    {
        try {
            //     $sql = "SELECT
            //     `payment_bill`,
            //     `payment_date`,
            //     `description`,
            //     `truck_in_working_bill`,
            //     `date`,
            //     `license_plate`,
            //     `driver_code`,
            //     `allowance`,
            //     `other_allownance`,
            //     `highway_tolls`,
            //     `tire_repair`,
            //     `truck_repair`,
            //     `create_by`,
            //     `create_date`,
            //     `update_by`,
            //     `update_date`
            // FROM
            //     `drivers_wage`
            //     WHERE payment_bill = :payment_bill
            // ORDER BY id DESC;";
            $sql = "SELECT
            a.`payment_bill`,
            a.`payment_date`,
            a.`description`,
            a.`truck_in_working_bill`,
            a.`date`,
            a.`license_plate`,
            a.`driver_code`,
            concat( b.firstname, ' ', b.lastname ) driver_name,
            a.`allowance`,
            a.`other_allownance`,
            a.`highway_tolls`,
            a.`tire_repair`,
            a.`truck_repair`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date` 
        FROM
            `drivers_wage` a,
            driver b 
                WHERE a.`payment_bill` = :payment_bill and b.driver_code = a.driver_code
                ORDER BY
                    id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':payment_bill', $payment_bill);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }


    public function getTruckInWorkingBill($conn,  $perform_bill_code)
    {
        try {
            $sql = "SELECT
            a.`id`,
            a.`perform_bill_code`,
            a.`bill_date`,
            a.`license_plate`,
            a.`date_back`,
            a.`driver_first_code`,
            ( SELECT CONCAT( b.firstname, ' ', b.lastname ) FROM driver b WHERE b.driver_code = a.driver_first_code ) AS driver_first_name,
            a.`driver_second_code`,
            ( SELECT CONCAT( b.firstname, ' ', b.lastname ) FROM driver b WHERE b.driver_code = a.driver_second_code ) AS driver_second_name,
            a.`start_shipping_date`,
            a.`customer_code`,
            (SELECT c.fullname FROM customer c WHERE c.customer_code = a.customer_code) as customer_name,
            a.`work_order`,
            a.`way`,
            (SELECT concat(d.start, ' - ', d.end) FROM set_way_allowance_transport_rate d WHERE d.way_code = a.way) as way_name,
            a.`store_destination_code`,
            (SELECT e.fullname FROM store_name e WHERE e.id = cast(a.store_destination_code as int)) as store_destination_name,
            a.`product_code`,
            (SELECT f.product_name FROM transported_goods f WHERE f.product_code = a.product_code) as product_name,
            a.`weight`,
            a.`quantity`,
            a.`weight_truck_per_kip`,
            a.`each_per_kip`,
            a.`per_trip_kip`,
            a.`income`,
            a.`allowance`,
            a.`refuel_code`,
            a.`current_diesel_oil_per_lit`,
            a.`lit_per_kip`,
            a.`total_diesel_oil_kip`,
            a.`current_gas_kg`,
            a.`kg_per_kip`,
            a.`total_gas_kip`,
            a.`other_allowance`,
            a.`highway_tolls`,
            a.`truck_mile_number_before`,
            a.`truck_mile_number_after`,
            a.`distance_this_trip_km`,
            a.`sum_distance_last_trip_km`,
            a.`sum_distance_this_trip`,
            a.`avg`,
            a.`determined_oil`,
            a.`oil_compensation_lit`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date` 
        FROM
            `truck_in_working_bill` a  WHERE `perform_bill_code` = :perform_bill_code  ORDER BY id DESC ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':perform_bill_code', $perform_bill_code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDriversWage(
        $conn,
        $paymentBill,
        $paymentDate,
        $desc,
        $truckInWorkingBill,
        $date,
        $licensePlate,
        $driver,
        $allowance,
        $otherAllowance,
        $highwayTolls,
        $tireRepair,
        $truckRepair,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `drivers_wage`(
                `payment_bill`,
                `payment_date`,
                `description`,
                `truck_in_working_bill`,
                `date`,
                `license_plate`,
                `driver_code`,
                `allowance`,
                `other_allownance`,
                `highway_tolls`,
                `tire_repair`,
                `truck_repair`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :payment_bill,
                :payment_date,
                :description,
                :truck_in_working_bill,
                :date,
                :license_plate,
                :driver_code,
                :allowance,
                :other_allownance,
                :highway_tolls,
                :tire_repair,
                :truck_repair,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':payment_bill', $paymentBill);
            $stmt->bindParam(':payment_date', $paymentDate);
            $stmt->bindParam(':description', $desc);
            $stmt->bindParam(':truck_in_working_bill', $truckInWorkingBill);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':driver_code', $driver);
            $stmt->bindParam(':allowance', $allowance);
            $stmt->bindParam(':other_allownance', $otherAllowance);
            $stmt->bindParam(':highway_tolls', $highwayTolls);
            $stmt->bindParam(':tire_repair', $tireRepair);
            $stmt->bindParam(':truck_repair', $truckRepair);
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

    public function updateDriversWage(
        $conn,
        $paymentBill,
        $paymentDate,
        $desc,
        $truckInWorkingBill,
        $date,
        $licensePlate,
        $driver,
        $allowance,
        $otherAllowance,
        $highwayTolls,
        $tireRepair,
        $truckRepair,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `drivers_wage`
        SET
            `payment_date` = :payment_date,
            `description` = :description,
            `truck_in_working_bill` = :truck_in_working_bill,
            `date` = :date,
            `license_plate` = :license_plate,
            `driver_code` = :driver_code,
            `allowance` = :allowance,
            `other_allownance` = :other_allownance,
            `highway_tolls` = :highway_tolls,
            `tire_repair` = :tire_repair,
            `truck_repair` = :truck_repair,
            `update_by` = :update_by,
            `update_date` = sysdate()
        WHERE
          `payment_bill` = :payment_bill";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':payment_bill', $paymentBill);
            $stmt->bindParam(':payment_date', $paymentDate);
            $stmt->bindParam(':description', $desc);
            $stmt->bindParam(':truck_in_working_bill', $truckInWorkingBill);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $licensePlate);
            $stmt->bindParam(':driver_code', $driver);
            $stmt->bindParam(':allowance', $allowance);
            $stmt->bindParam(':other_allownance', $otherAllowance);
            $stmt->bindParam(':highway_tolls', $highwayTolls);
            $stmt->bindParam(':tire_repair', $tireRepair);
            $stmt->bindParam(':truck_repair', $truckRepair);
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

    public function deleteDriversWage($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
