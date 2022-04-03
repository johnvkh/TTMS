<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckSharingWorkingBill = new TruckSharingWorkingBillModel();

$timestamp = date("Y-m-d h:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->actionCode)) {
        http_response_code(200);
        echo json_encode(array(
            'responseCode' => ErrorCode::UNKNOWN_ERROR,
            'message' => 'Something went wrong. Please check your data and try again.',
            'timestamp' => $timestamp
        ));
        exit;
    }

    switch ($data->actionCode) {
        case ActionCode::GET_ALL_TRUCK_SHARING_WORKING_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRUCK_SHARING_WORKING_BILL) {
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

                $getAllTruckSharingWorkingBill = $TruckSharingWorkingBill->getAllTruckSharingWorkingBill($conn);
                $num_row = $getAllTruckSharingWorkingBill->rowCount();
                $getAllTruckSharingWorkingBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTruckSharingWorkingBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckSharingWorkingBillId" => $id,
                            "billReleaseTruckSharingNo" => $bill_release_truck_sharing_no,
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
                        array_push($getAllTruckSharingWorkingBill_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllTruckSharingWorkingBill_arr['data'],
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

                if (!isset($data->truckSharingWorkingBillId) || !is_numeric($data->truckSharingWorkingBillId) || empty($data->truckSharingWorkingBillId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingBillId'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckSharingWorkingBillId = isset($data->truckSharingWorkingBillId) ? trim($data->truckSharingWorkingBillId) : null;

                $get_truck_sharing_working_bill = $TruckSharingWorkingBill->getTruckSharingWorkingBill($conn, $truckSharingWorkingBillId);
                $num_row = $get_truck_sharing_working_bill->rowCount();
                $get_truck_sharing_working_bill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_truck_sharing_working_bill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckSharingWorkingBillId" => $id,
                            "billReleaseTruckSharingNo" => $bill_release_truck_sharing_no,
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
                        array_push($get_truck_sharing_working_bill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_truck_sharing_working_bill_arr['data'],
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
        case ActionCode::GET_RELEASE_TRUCK_SHARING_BILL_BY_RELEASE_TRUCK_SHARING_BILL_NO:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_RELEASE_TRUCK_SHARING_BILL_BY_RELEASE_TRUCK_SHARING_BILL_NO) {
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
                        'message' => 'Invalid Action Node Id'
                    ));
                    return;
                }

                if (!isset($data->billReleaseTruckSharingNo) || !is_numeric($data->billReleaseTruckSharingNo) || empty($data->billReleaseTruckSharingNo)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid billReleaseTruckSharingNo'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $billReleaseTruckSharingNo = isset($data->billReleaseTruckSharingNo) ? trim($data->billReleaseTruckSharingNo) : null;

                $get_release_truck_sharing_bill = $TruckSharingWorkingBill->getReleaseTruckSharingBillByBillReleaseTruckSharingNo($conn, $billReleaseTruckSharingNo);
                $num_row = $get_release_truck_sharing_bill->rowCount();
                $get_release_truck_sharing_bill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_release_truck_sharing_bill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "billReleaseTruckSharingNo" => $bill_release_truck_sharing_no,
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
                        array_push($get_release_truck_sharing_bill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_release_truck_sharing_bill_arr['data'],
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
        case ActionCode::CREATE_TRUCK_SHARING_WORKING_BILL:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRUCK_SHARING_WORKING_BILL) {
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

                if (!isset($data->billReleaseTruckSharingNo) || empty($data->billReleaseTruckSharingNo) || !is_numeric($data->billReleaseTruckSharingNo)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid billReleaseTruckSharingNo'
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
                $billReleaseTruckSharingNo = isset($data->billReleaseTruckSharingNo) ? trim($data->billReleaseTruckSharingNo) : null;
                $weight = isset($data->weight) ? trim($data->weight) : null;
                $ton_per = isset($data->ton_per) ? trim($data->ton_per) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_bill_release_truck_sharing_no = $TruckSharingWorkingBill->checkBillReleaseTruckSharingNo($conn, $billReleaseTruckSharingNo);
                if ($check_bill_release_truck_sharing_no) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_IN_WORKING_BILL_FAIL,
                        'message' => 'bill release truck sharing no already has in the system, please use other bill release truck sharing no',
                    ));
                    return;
                }

                $result_create = $TruckSharingWorkingBill->createNewTruckSharingWorkingBill(
                    $conn,
                    $billReleaseTruckSharingNo,
                    $weight,
                    $ton_per,
                    $totalPrice,
                    $createBy
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
                    'responseCode' => ErrorCode::CREATE_TRUCK_SHARING_WORKING_BILL_FAIL,
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
        case ActionCode::UPDATE_TRUCK_SHARING_WORKING_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRUCK_SHARING_WORKING_BILL) {
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

                if (!isset($data->truckSharingWorkingBillId) || empty($data->truckSharingWorkingBillId) || !is_numeric($data->truckSharingWorkingBillId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingBillId'
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

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckSharingWorkingBillId = isset($data->truckSharingWorkingBillId) ? trim($data->truckSharingWorkingBillId) : null;
                $weight = isset($data->weight) ? trim($data->weight) : null;
                $ton_per = isset($data->ton_per) ? trim($data->ton_per) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_truck_sharing_working_bill_id = $TruckSharingWorkingBill->checkTruckSharingWorkingBillId($conn, $truckSharingWorkingBillId);
                if (!$check_truck_sharing_working_bill_id) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_SHARING_WORKING_BILL,
                        'message' => 'not found data in the system.',
                    ));
                    return;
                }

                $result_update = $TruckSharingWorkingBill->updateTruckSharingWorkingBill(
                    $conn,
                    $truckSharingWorkingBillId,
                    $weight,
                    $ton_per,
                    $totalPrice,
                    $updateBy
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
                    'responseCode' => ErrorCode::UPDATE_TRUCK_SHARING_WORKING_BILL_FAIL,
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
        case ActionCode::DELETE_TRUCK_SHARING_WORKING_BILL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_SHARING_WORKING_BILL) {
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
                    'responseCode' => ErrorCode::DELETE_DELIVERY_LOCATION_FAIL,
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
