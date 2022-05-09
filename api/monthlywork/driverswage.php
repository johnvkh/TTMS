<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$DriversWage = new DriversWageModel();

$timestamp = date("Y-m-d h:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->actionCode)) {
        echo json_encode(array(
            'responseCode' => ErrorCode::UNKNOWN_ERROR,
            'message' => 'Something went wrong. Please check your data and try again.',
            'timestamp' => $timestamp
        ));
        http_response_code(200);
        exit;
    }

    switch ($data->actionCode) {
        case ActionCode::GET_ALL_DRIVERS_WAGE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_DRIVERS_WAGE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $getAllDriversWage = $DriversWage->getAllDriversWage($conn);
                $num_row = $getAllDriversWage->rowCount();
                $getAllDriversWage_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllDriversWage->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "paymentBill" => $payment_bill,
                            "paymentDate" => $payment_date,
                            "desc" => $description,
                            "truckInWorkingBill" => $truck_in_working_bill,
                            "date" => $date,
                            "licensePlate" => $license_plate,
                            "driver" => $driver_code,
                            "driverName" => $driver_name,
                            "allowance" => $allowance,
                            "highwayTolls" => $highway_tolls,
                            "tireRepair" => $tire_repair,
                            "truckRepair" => $truck_repair,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllDriversWage_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllDriversWage_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_DRIVERS_WAGE:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_DRIVERS_WAGE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (trim(!isset($data->paymentBill)) || trim($data->paymentBill) == null || trim($data->paymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid paymentBill'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $paymentBill = isset($data->paymentBill) ? trim($data->paymentBill) : null;

                $getDriversWage = $DriversWage->getDriversWage($conn, $paymentBill);
                $num_row = $getDriversWage->rowCount();
                $getDriversWage_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getDriversWage->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "paymentBill" => $payment_bill,
                            "paymentDate" => $payment_date,
                            "desc" => $description,
                            "truckInWorkingBill" => $truck_in_working_bill,
                            "date" => $date,
                            "licensePlate" => $license_plate,
                            "driver" => $driver_code,
                            "driverName" => $driver_name,
                            "allowance" => $allowance,
                            "otherAllowance" => $other_allownance,
                            "highwayTolls" => $highway_tolls,
                            "tireRepair" => $tire_repair,
                            "truckRepair" => $truck_repair,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getDriversWage_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getDriversWage_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_TRUCK_IN_WORKING_BILL:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TRUCK_IN_WORKING_BILL) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (trim(!isset($data->performBill)) || trim($data->performBill) == null || trim($data->performBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid performBill'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $performBill = isset($data->performBill) ? trim($data->performBill) : null;

                $getTruckInWorkingBill = $DriversWage->getTruckInWorkingBill($conn, $performBill);
                $num_row = $getTruckInWorkingBill->rowCount();
                $getTruckInWorkingBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTruckInWorkingBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "performBill" => $perform_bill_code,
                            "billDate" => $bill_date,
                            "licensePlate" => $license_plate,
                            "dateBack" => $date_back,
                            "driverFirst" => $driver_first_code,
                            "driverFirstName" => $driver_first_name,
                            "driverSecond" => $driver_second_code,
                            "driverSecondName" => $driver_second_name,
                            "startShippingDate" => $start_shipping_date,
                            "customerCode" => $customer_code,
                            "customerName" => $customer_name,
                            "way" => $way,
                            "wayName" => $way_name,
                            "storeDestination" => $store_destination_code,
                            "productCode" => $product_code,
                            "weight" => $weight,
                            "quantity" => $quantity,
                            "weightPerTruckKip" => $weight_truck_per_kip,
                            "eachPerKip" => $each_per_kip,
                            "perTripKip" => $per_trip_kip,
                            "income" => $income,
                            "allowance" => $allowance,
                            "refuelCode" => $refuel_code,
                            "currentDieselOilPerLit" => $current_diesel_oil_per_lit,
                            "litPerKip" => $lit_per_kip,
                            "totalDieselOilKip" => $total_diesel_oil_kip,
                            "currentGasKg" => $current_gas_kg,
                            "kgPerKip" => $kg_per_kip,
                            "totalGasKip" => $total_gas_kip,
                            "otherAllowance" => $other_allowance,
                            "highwayTolls" => $highway_tolls,
                            "truckMileNumberBeforeAction" => $truck_mile_number_before,
                            "truckMileNumberAfterAction" => $truck_mile_number_after,
                            "distanceKm" => $distance_this_trip_km,
                            "sumDistanceLastTripKm" => $sum_distance_last_trip_km,
                            "sumDistanceThisTripKm" => $sum_distance_this_trip,
                            "avg" => $avg,
                            "determinedOil" => $determined_oil,
                            "oilCompensationLit" => $oil_compensation_lit,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTruckInWorkingBill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getTruckInWorkingBill_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
        case ActionCode::CREATE_DRIVERS_WAGE:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_DRIVERS_WAGE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->paymentBill) || trim($data->paymentBill) == null || trim($data->paymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid paymentBill'
                    ));
                    return;
                }

                if (!isset($data->paymentDate) || trim($data->paymentDate) == null || trim($data->paymentDate) == "" || !ValidateDate::checkDateFormat($data->paymentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid paymentDate'
                    ));
                    return;
                }

                if (!isset($data->truckInWorkingBill) || trim($data->truckInWorkingBill) == null || trim($data->truckInWorkingBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckInWorkingBill'
                    ));
                    return;
                }


                if (!isset($data->date) || trim($data->date) == null || trim($data->date) == "" || !ValidateDate::checkDateFormat($data->paymentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->driver) || trim($data->driver) == null || trim($data->driver) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driver'
                    ));
                    return;
                }

                if (!isset($data->allowance) || !is_int($data->allowance) || $data->allowance <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid allowance'
                    ));
                    return;
                }
                if (isset($data->otherAllowance) && (!is_int($data->otherAllowance) || $data->otherAllowance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid otherAllowance'
                    ));
                    return;
                }
                if (isset($data->highwayTolls) && (!is_int($data->highwayTolls) || $data->highwayTolls < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid highwayTolls'
                    ));
                    return;
                }
                if (isset($data->truckRepair) && (!is_int($data->truckRepair) || $data->truckRepair < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckRepair'
                    ));
                    return;
                }

                if (isset($data->tireRepair) && (!is_int($data->tireRepair) || $data->tireRepair < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                if (!isset($data->createBy) || trim($data->createBy) == null || trim($data->createBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $paymentBill = isset($data->paymentBill) ? trim($data->paymentBill) : null;
                $paymentDate = isset($data->paymentDate) ? trim($data->paymentDate) : null;
                $desc = isset($data->desc) ? trim($data->desc) : null;
                $truckInWorkingBill = isset($data->truckInWorkingBill) ? trim($data->truckInWorkingBill) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $driver = isset($data->driver) ? trim($data->driver) : null;
                $allowance = isset($data->allowance) ? trim($data->allowance) : null;
                $otherAllowance = isset($data->otherAllowance) ? trim($data->otherAllowance) : null;
                $highwayTolls = isset($data->highwayTolls) ? trim($data->highwayTolls) : null;
                $police = isset($data->police) ? trim($data->police) : null;
                $tireRepair = isset($data->tireRepair) ? trim($data->tireRepair) : null;
                $truckRepair = isset($data->truckRepair) ? trim($data->truckRepair) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkLicensePlate = $DriversWage->checkLicensePlate($conn, $licensePlate);

                if ($checkLicensePlate->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'The licensePlate is already in this month, Please use other licensePlate '
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $DriversWage->createNewDriversWage($conn, $paymentBill, $paymentDate, $desc, $truckInWorkingBill, $date, $licensePlate, $driver, $allowance, $otherAllowance, $highwayTolls, $tireRepair, $truckRepair, $createBy);
                if ($result) {
                    $res = array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );

                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_DRIVERS_WAGE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_DRIVERS_WAGE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->paymentBill) || trim($data->paymentBill) == null || trim($data->paymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid paymentBill'
                    ));
                    return;
                }

                if (!isset($data->paymentDate) || trim($data->paymentDate) == null || trim($data->paymentDate) == "" || !ValidateDate::checkDateFormat($data->paymentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid paymentDate'
                    ));
                    return;
                }

                if (!isset($data->truckInWorkingBill) || trim($data->truckInWorkingBill) == null || trim($data->truckInWorkingBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckInWorkingBill'
                    ));
                    return;
                }


                if (!isset($data->date) || trim($data->date) == null || trim($data->date) == "" || !ValidateDate::checkDateFormat($data->paymentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->driver) || trim($data->driver) == null || trim($data->driver) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driver'
                    ));
                    return;
                }

                if (!isset($data->allowance) || !is_int($data->allowance) || $data->allowance <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid allowance'
                    ));
                    return;
                }
                if (isset($data->otherAllowance) && (!is_int($data->otherAllowance) || $data->otherAllowance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid otherAllowance'
                    ));
                    return;
                }
                if (isset($data->highwayTolls) && (!is_int($data->highwayTolls) || $data->highwayTolls < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid highwayTolls'
                    ));
                    return;
                }
                if (isset($data->truckRepair) && (!is_int($data->truckRepair) || $data->truckRepair < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckRepair'
                    ));
                    return;
                }

                if (isset($data->tireRepair) && (!is_int($data->tireRepair) || $data->tireRepair < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                if (trim(!isset($data->updateBy)) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }



                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $paymentBill = isset($data->paymentBill) ? trim($data->paymentBill) : null;
                $paymentDate = isset($data->paymentDate) ? trim($data->paymentDate) : null;
                $desc = isset($data->desc) ? trim($data->desc) : null;
                $truckInWorkingBill = isset($data->truckInWorkingBill) ? trim($data->truckInWorkingBill) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $driver = isset($data->driver) ? trim($data->driver) : null;
                $allowance = isset($data->allowance) ? trim($data->allowance) : null;
                $otherAllowance = isset($data->otherAllowance) ? trim($data->otherAllowance) : null;
                $highwayTolls = isset($data->highwayTolls) ? trim($data->highwayTolls) : null;
                $police = isset($data->police) ? trim($data->police) : null;
                $tireRepair = isset($data->tireRepair) ? trim($data->tireRepair) : null;
                $truckRepair = isset($data->truckRepair) ? trim($data->truckRepair) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getDriversWage = $DriversWage->getDriversWage($conn, $paymentBill);
                if ($getDriversWage->rowCount() > 0) {
                    $result = $DriversWage->updateDriversWage($conn, $paymentBill, $paymentDate, $desc, $truckInWorkingBill, $date, $licensePlate, $driver, $allowance, $otherAllowance, $highwayTolls, $tireRepair, $truckRepair, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_DRIVERS_WAGE_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                    } else {
                        $res = array(
                            'responseCode' => ErrorCode::SUCCESS,
                            'message' => 'success',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                    }
                    echo json_encode($res);
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_DRIVERS_WAGE_FAIL,
                    'message' => 'The licensePlate is not found in the system, Please use other licensePlate'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_DRIVERS_WAGE:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_DRIVERS_WAGE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionCode == null || $data->actionCode == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }


                $actionCode = trim($data->actionCode);
                $actionNodeId = $data->actionNodeId;

                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_DRIVERS_WAGE_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        default:
            echo json_encode(array(
                'responseCode' => ErrorCode::UNKNOWN_ERROR,
                'message' => 'Something went wrong. Please check your data and try again.',
                'timestamp' => $timestamp
            ));
            http_response_code(200);
            exit;
            break;
    }
} else {
    echo json_encode(array(
        'responseCode' => ErrorCode::UNKNOWN_ERROR,
        'message' => 'Something went wrong. Please check your data and try again.',
        'timestamp' => $timestamp
    ));
    http_response_code(200);
    exit;
}
