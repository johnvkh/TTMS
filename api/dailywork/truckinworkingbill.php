<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckInWorkingBill = new TruckInWorkingBillModel();

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
        case ActionCode::GET_ALL_TRUCK_IN_WORKING_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRUCK_IN_WORKING_BILL) {
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
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;

                $getAllTruckInWorkingBill = $TruckInWorkingBill->getAllTruckInWorkingBill($conn, $fromDate, $toDate);
                $num_row = $getAllTruckInWorkingBill->rowCount();
                $getAllTruckInWorkingBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTruckInWorkingBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckInWorkingBillId" => $id,
                            "billReleaseTruckInNo" => $bill_release_truck_in_no,
                            "goDate" => $go_date,
                            "backDate" => $back_date,
                            "licensePlate" => $license_plate,
                            "driverCodeFirst" => $driver_first,
                            "driverNameFirst" => $driver_name_first,
                            "driverCodeSec" => $driver_second,
                            "driverNameSec" => $driver_name_sec,
                            "shippingDate" => $date_shipping,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "destinationCode" => $destination,
                            "destinationName" => $destination_name,
                            "weight" => $wight,
                            "tonPer" => $ton_per,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTruckInWorkingBill_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllTruckInWorkingBill_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TRUCK_IN_WORKING_BILL,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
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
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckInWorkingBillId) || !is_numeric($data->truckInWorkingBillId) || empty($data->truckInWorkingBillId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckInWorkingBillId'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckInWorkingBillId = isset($data->truckInWorkingBillId) ? trim($data->truckInWorkingBillId) : null;

                $getTruckInWorkingBill = $TruckInWorkingBill->getTruckInWorkingBill($conn, $truckInWorkingBillId);
                $num_row = $getTruckInWorkingBill->rowCount();
                $getTruckInWorkingBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTruckInWorkingBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckInWorkingBillId" => $id,
                            "billReleaseTruckInNo" => $bill_release_truck_in_no,
                            "goDate" => $go_date,
                            "backDate" => $back_date,
                            "licensePlate" => $license_plate,
                            "driverCodeFirst" => $driver_first,
                            "driverNameFirst" => $driver_name_first,
                            "driverCodeSec" => $driver_second,
                            "driverNameSec" => $driver_name_sec,
                            "shippingDate" => $date_shipping,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "destinationCode" => $destination,
                            "destinationName" => $destination_name,
                            "weight" => $wight,
                            "tonPer" => $ton_per,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTruckInWorkingBill_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getTruckInWorkingBill_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_RELEASE_TRUCK_IN_BILL_BY_BILL_RELEASE_TRUCK_IN_NO:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_RELEASE_TRUCK_IN_BILL_BY_BILL_RELEASE_TRUCK_IN_NO) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->billReleaseTruckInNo) || empty($data->billReleaseTruckInNo) || !is_numeric($data->billReleaseTruckInNo)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid billReleaseTruckInNo'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $billReleaseTruckInNo = isset($data->billReleaseTruckInNo) ? trim($data->billReleaseTruckInNo) : null;

                $get_bill_release_truck_in_no = $TruckInWorkingBill->getReleaseTruckInBillByBillReleaseTruckInNo($conn, $billReleaseTruckInNo);
                $num_row = $get_bill_release_truck_in_no->rowCount();
                $get_bill_release_truck_in_no_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_bill_release_truck_in_no->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "billReleaseTruckInNo" => $bill_release_truck_in_no,
                            "goDate" => $go_date,
                            "backDate" => $back_date,
                            "licensePlate" => $license_plate,
                            "driverCodeFirst" => $driver_first,
                            "driverNameFirst" => null,
                            "driverCodeSec" => $driver_second,
                            "driverNameSec" => null,
                            "shippingDate" => $date_shipping,
                            "productCode" => $product_code,
                            "productName" => null,
                            "destination" => $destination,
                            "destinationName" => null
                        );
                        array_push($get_bill_release_truck_in_no_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_bill_release_truck_in_no_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_TRUCK_IN_WORKING_BILL:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRUCK_IN_WORKING_BILL) {
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

                if (!isset($data->billReleaseTruckInNo) || empty($data->billReleaseTruckInNo)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid billReleaseTruckInNo'
                    ));
                    return;
                }

                if (!isset($data->weight) || empty($data->weight) || !is_numeric($data->weight)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid weight'
                    ));
                    return;
                }


                if (!isset($data->ton_per) || empty($data->ton_per) || !is_numeric($data->ton_per)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ton_per'
                    ));
                    return;
                }


                if (!isset($data->totalPrice) || empty($data->totalPrice) || !is_numeric($data->totalPrice)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }


                if (!isset($data->createBy) || empty($data->createBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $billReleaseTruckInNo = isset($data->billReleaseTruckInNo) ? trim($data->billReleaseTruckInNo) : null;
                $weight = isset($data->weight) ? trim($data->weight) : null;
                $ton_per = isset($data->ton_per) ? trim($data->ton_per) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_bill_release_truck_in_no = $TruckInWorkingBill->checkBillReleaseTruckInNo($conn, $billReleaseTruckInNo);
                if ($check_bill_release_truck_in_no) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_IN_WORKING_BILL_FAIL,
                        'message' => 'bill release truck in no already has in the system, please use other bill release truck in no',
                    ));
                    return;
                }

                $result_create = $TruckInWorkingBill->cerateNewTruckInWorkingBill(
                        $conn, $billReleaseTruckInNo, $weight, $ton_per, $totalPrice, $createBy
                );

                if ($result_create) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::CREATE_TRUCK_IN_WORKING_BILL_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));

                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_TRUCK_IN_WORKING_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRUCK_IN_WORKING_BILL) {
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

                if (!isset($data->truckInWorkingBillId) || !is_numeric($data->truckInWorkingBillId) || empty($data->truckInWorkingBillId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckInWorkingBillId'
                    ));
                    return;
                }

                if (!isset($data->weight) || empty($data->weight) || !is_numeric($data->weight)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid weight'
                    ));
                    return;
                }


                if (!isset($data->ton_per) || empty($data->ton_per) || !is_numeric($data->ton_per)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ton_per'
                    ));
                    return;
                }


                if (!isset($data->totalPrice) || empty($data->totalPrice) || !is_numeric($data->totalPrice)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckInWorkingBillId = isset($data->truckInWorkingBillId) ? trim($data->truckInWorkingBillId) : null;
                $weight = isset($data->weight) ? trim($data->weight) : null;
                $ton_per = isset($data->ton_per) ? trim($data->ton_per) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_truck_in_working_bill_id = $TruckInWorkingBill->checkTruckInWorkingBillId($conn, $truckInWorkingBillId);
                if (!$check_truck_in_working_bill_id) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_IN_WORKING_BILL,
                        'message' => 'not found data in the system.',
                    ));
                    return;
                }

                $result_update = $TruckInWorkingBill->updateTruckWorkingBill(
                        $conn, $truckInWorkingBillId, $weight, $ton_per, $totalPrice, $updateBy
                );

                if ($result_update) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TRUCK_IN_WORKING_BILL_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TRUCK_IN_WORKING_BILL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_IN_WORKING_BILL) {
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
                    'responseCode' => ErrorCode::DELETE_TRUCK_IN_WORKING_BILL_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
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
