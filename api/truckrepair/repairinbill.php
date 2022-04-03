<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$RepairInBill = new RepairInBillModel();

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
        case ActionCode::GET_ALL_REPAIR_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPAIR_IN_BILL) {
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

                if (isset($data->fromRepairDate) && !empty($data->toRepairDate)) {
                    if (!ValidateDate::checkDateFormat($data->fromRepairDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromRepairDate'
                        ));
                        return;
                    }
                }
                if (isset($data->toRepairDate) && !empty($data->toRepairDate)) {
                    if (!ValidateDate::checkDateFormat($data->toRepairDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid toRepairDate'
                        ));
                        return;
                    }
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $fromRepairBillCode = isset($data->fromRepairBillCode) ? trim($data->fromRepairBillCode) : null;
                $toRepairBillCode = isset($data->toRepairBillCode) ? trim($data->toRepairBillCode) : null;
                $fromRepairDate = isset($data->fromRepairDate) ? trim($data->fromRepairDate) : null;
                $toRepairDate = isset($data->toRepairDate) ? trim($data->toRepairDate) : null;

                $getAllRepairInBill = $RepairInBill->getAllRepairInBill($conn, $fromRepairBillCode, $toRepairBillCode, $fromRepairDate, $toRepairDate);
                $num_row = $getAllRepairInBill->rowCount();
                $getAllRepairInBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllRepairInBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "repairBillCode" => $repair_bill_code,
                            "licensePlate" => $license_plate,
                            "truckMileNumber" => $truck_mile_number,
                            "truckTypeId" => $repair_type_id,
                            "truckTypeName" => $repair_type_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllRepairInBill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllRepairInBill_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN_BILL,
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
        case ActionCode::GET_REPAIR_IN_BILL:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_IN_BILL) {
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

                if (!isset($data->repairBillCode) || trim($data->repairBillCode) == null || trim($data->repairBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairBillCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairBillCode = isset($data->repairBillCode) ? trim($data->repairBillCode) : null;

                $getRepairInBill = $RepairInBill->getRepairInBill($conn, $repairBillCode);
                $num_row = $getRepairInBill->rowCount();
                $getRepairInBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getRepairInBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "repairBillCode" => $repair_bill_code,
                            "licensePlate" => $license_plate,
                            "truckMileNumber" => $truck_mile_number,
                            "truckTypeId" => $repair_type_id,
                            "truckTypeName" => $repair_type_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getRepairInBill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getRepairInBill_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN_BILL,
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
        case ActionCode::GET_REPAIR_IN_BILL_BY_LICENSE_PLATE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_IN_BILL_BY_LICENSE_PLATE) {
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

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getRepairInBillByLicensePlate = $RepairInBill->getRepairInBillByLicensePlate($conn, $licensePlate);
                $num_row = $getRepairInBillByLicensePlate->rowCount();
                $getRepairInBillByLicensePlate_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getRepairInBillByLicensePlate->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "repairBillCode" => $repair_bill_code,
                            "licensePlate" => $license_plate,
                            "truckTypeName" => $truck_type_name,
                            "driverName" => $driver_name,
                            "truckMileNumber" => $truck_mile_number,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getRepairInBillByLicensePlate_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getRepairInBillByLicensePlate_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN_BILL,
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
        case ActionCode::CREATE_REPAIR_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REPAIR_IN_BILL) {
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
                if (!isset($data->repairBillCode) || trim($data->repairBillCode) == null || trim($data->repairBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairBillCode'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
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

                if (!isset($data->repairDate) || trim($data->repairDate) == null || trim($data->repairDate) == "" || !ValidateDate::checkDateFormat($data->repairDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || trim($data->mileNumber) == null || trim($data->mileNumber) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }


                if (!isset($data->repairTypeId) || trim($data->repairTypeId) == null || trim($data->repairTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
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
                $repairBillCode = isset($data->repairBillCode) ? trim($data->repairBillCode) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairDate = isset($data->repairDate) ? trim($data->repairDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getRepairInBill = $RepairInBill->getRepairInBill($conn, $repairBillCode);
                if ($getRepairInBill->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_REPAIR_IN_BILL_FAIL,
                        'message' => 'The repair bill code is already in the system, Please use other repair bill code '
                    ));
                    return;
                }

                $result = $RepairInBill->createNewRepairInBill($conn, $repairBillCode, $whichPart, $licensePlate, $repairDate, $mileNumber, $repairTypeId, $note, $createBy);
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
                        'responseCode' => ErrorCode::CREATE_REPAIR_TYPE_FAIL,
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
        case ActionCode::UPDATE_REPAIR_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_REPAIR_IN_BILL) {
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

                if (!isset($data->repairBillCode) || trim($data->repairBillCode) == null || trim($data->repairBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairBillCode'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
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

                if (!isset($data->repairDate) || trim($data->repairDate) == null || trim($data->repairDate) == "" || !ValidateDate::checkDateFormat($data->repairDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || trim($data->mileNumber) == null || trim($data->mileNumber) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }


                if (!isset($data->repairTypeId) || trim($data->repairTypeId) == null || trim($data->repairTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }


                if (!isset($data->updateBy) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairBillCode = isset($data->repairBillCode) ? trim($data->repairBillCode) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairDate = isset($data->repairDate) ? trim($data->repairDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getRepairInBill = $RepairInBill->getRepairInBill($conn, $repairBillCode);

                if ($getRepairInBill->rowCount() > 0) {
                    $result = $RepairInBill->updateRepairInBill($conn, $repairBillCode, $whichPart, $licensePlate, $repairDate, $mileNumber, $repairTypeId,$note, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_REPAIR_IN_BILL_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_REPAIR_IN_BILL_FAIL,
                    'message' => 'The id is not found in the system, Please use other id'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_REPAIR_IN_BILL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REPAIR_IN_BILL) {
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
                    'responseCode' => ErrorCode::DELETE_REPAIR_IN_BILL_FAIL,
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
