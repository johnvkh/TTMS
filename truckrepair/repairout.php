<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$RepairOut = new RepairOutModel();

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
        case ActionCode::GET_ALL_REPAIR_OUT:
            echo 'get repair out';
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPAIR_OUT) {
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


                $getAllRepairOut = $RepairOut->getAllRepairOut($conn);
                $num_row = $getAllRepairOut->rowCount();
                $getAllRepairOut_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllRepairOut->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "repairOutBillCode" => $repair_out_bill_code,
                            "saveAt" => $create_date,
                            "licensePlate" => $license_plate,
                            "totalCost" => $total_cost,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllRepairOut_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllRepairOut_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_REPAIR_OUT:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_OUT) {
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

                if (!isset($data->repairOutBillCode) || trim($data->repairOutBillCode) == null || trim($data->repairOutBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOutBillCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairOutBillCode = isset($data->repairOutBillCode) ? trim($data->repairOutBillCode) : null;

                $getRepairOut = $RepairOut->getRepairOut($conn, $repairOutBillCode);
                $num_row = $getRepairOut->rowCount();
                $getRepairOut_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getRepairOut->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "repairOutBillCode" => $repair_out_bill_code,
                            "expenseDate"  => $expense_date,
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "repairOrderDate" => $repair_order_date,
                            "repairedDate" => $repair_date,
                            "mileNumber" => $mile_number,
                            "repairTypeId" => $repair_type_id,
                            "note" => $note,
                            "repairStoreId" => $repair_store_code,
                            "invoiceCode" => $invoiceCode,
                            "date" => $date,
                            "totalCost" => $total_cost,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getRepairOut_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getRepairOut_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_REPAIR_OUT:
            try {
                // print_r($data->truckPartsDetails[0]);

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REPAIR_OUT) {
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

                if (!isset($data->repairOutBillCode) || empty($data->repairOutBillCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOutBillCode'
                    ));
                    return;
                }

                if (!isset($data->expenseDate) || empty($data->expenseDate) || !ValidateDate::checkDateFormat($data->expenseDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid expenseDate'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || !in_array($data->whichPart, array(0, 1))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->repairOrderDate) || empty($data->repairOrderDate) || !ValidateDate::checkDateFormat($data->repairOrderDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOrderDate'
                    ));
                    return;
                }

                if (!isset($data->repairedDate) || empty($data->repairedDate) || !ValidateDate::checkDateFormat($data->repairedDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairedDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || empty($data->mileNumber)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }

                if (!isset($data->repairTypeId) || empty($data->repairTypeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }

                if (!isset($data->note) || empty($data->note)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid note'
                    ));
                    return;
                }

                if (!isset($data->repairStoreId) || empty($data->repairStoreId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairStoreId'
                    ));
                    return;
                }

                if (!isset($data->invoiceCode) || empty($data->invoiceCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid invoiceCode'
                    ));
                    return;
                }

                if (!isset($data->date) || empty($data->date) || !ValidateDate::checkDateFormat($data->date)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                }

                if (!isset($data->totalCost) || empty($data->totalCost)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalCost'
                    ));
                    return;
                }


                if (!isset($data->createBy) || empty($data->createBy)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairOutBillCode = isset($data->repairOutBillCode) ? trim($data->repairOutBillCode) : null;
                $expenseDate = isset($data->expenseDate) ? trim($data->expenseDate) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairOrderDate = isset($data->repairOrderDate) ? trim($data->repairOrderDate) : null;
                $repairedDate = isset($data->repairedDate) ? trim($data->repairedDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $repairStoreId = isset($data->repairStoreId) ? trim($data->repairStoreId) : null;
                $invoiceCode = isset($data->invoiceCode) ? trim($data->invoiceCode) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $totalCost = isset($data->totalCost) ? trim($data->totalCost) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkRepiarOutBillCodeExist = $RepairOut->getRepairOut($conn, $repairOutBillCode);
                if ($checkRepiarOutBillCodeExist->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'the repair out bill code already has in the system, please use other repair out bill code'
                    ));
                    return;
                }

                $result = $RepairOut->createNewRepairOut(
                    $conn,
                    $repairOutBillCode,
                    $expenseDate,
                    $whichPart,
                    $licensePlate,
                    $repairOrderDate,
                    $repairedDate,
                    $mileNumber,
                    $repairTypeId,
                    $note,
                    $repairStoreId,
                    $invoiceCode,
                    $date,
                    $totalCost,
                    $createBy
                );
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
                        'responseCode' => ErrorCode::CREATE_REPAIR_OUT_FAIL,
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
        case ActionCode::UPDATE_REPAIR_OUT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_REPAIR_OUT) {
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
                if (!isset($data->repairOutBillCode) || empty($data->repairOutBillCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOutBillCode'
                    ));
                    return;
                }

                if (!isset($data->expenseDate) || empty($data->expenseDate) || !ValidateDate::checkDateFormat($data->expenseDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid expenseDate'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || !in_array($data->whichPart, array(0, 1))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->repairOrderDate) || empty($data->repairOrderDate) || !ValidateDate::checkDateFormat($data->repairOrderDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOrderDate'
                    ));
                    return;
                }

                if (!isset($data->repairedDate) || empty($data->repairedDate) || !ValidateDate::checkDateFormat($data->repairedDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairedDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || empty($data->mileNumber)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }

                if (!isset($data->repairTypeId) || empty($data->repairTypeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }

                if (!isset($data->note) || empty($data->note)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid note'
                    ));
                    return;
                }

                if (!isset($data->repairStoreId) || empty($data->repairStoreId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairStoreId'
                    ));
                    return;
                }

                if (!isset($data->invoiceCode) || empty($data->invoiceCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid invoiceCode'
                    ));
                    return;
                }

                if (!isset($data->date) || empty($data->date) || !ValidateDate::checkDateFormat($data->date)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                }

                if (!isset($data->totalCost) || empty($data->totalCost)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalCost'
                    ));
                    return;
                }


                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairOutBillCode = isset($data->repairOutBillCode) ? trim($data->repairOutBillCode) : null;
                $expenseDate = isset($data->expenseDate) ? trim($data->expenseDate) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairOrderDate = isset($data->repairOrderDate) ? trim($data->repairOrderDate) : null;
                $repairedDate = isset($data->repairedDate) ? trim($data->repairedDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $repairStoreId = isset($data->repairStoreId) ? trim($data->repairStoreId) : null;
                $invoiceCode = isset($data->invoiceCode) ? trim($data->invoiceCode) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $totalCost = isset($data->totalCost) ? trim($data->totalCost) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $checkRepiarOutBillCodeExist = $RepairOut->getRepairOut($conn, $repairOutBillCode);

                if ($checkRepiarOutBillCodeExist->rowCount() > 0) {
                    $result = $RepairOut->updateRepairOut(
                        $conn,
                        $repairOutBillCode,
                        $expenseDate,
                        $whichPart,
                        $licensePlate,
                        $repairOrderDate,
                        $repairedDate,
                        $mileNumber,
                        $repairTypeId,
                        $note,
                        $repairStoreId,
                        $invoiceCode,
                        $date,
                        $totalCost,
                        $updateBy
                    );
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_REPAIR_OUT_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_REPAIR_OUT_FAIL,
                    'message' => 'The repair out bill code is not found in the system, Please use other repair out bill code'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_REPAIR_OUT:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REPAIR_OUT) {
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
                if (!isset($data->repairOutBillCode) || empty($data->repairOutBillCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOutBillCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairOutBillCode = isset($data->repairOutBillCode) ? trim($data->repairOutBillCode) : null;

                $checkRepiarOutBillCodeExist = $RepairOut->getRepairOut($conn, $repairOutBillCode);
                if ($checkRepiarOutBillCodeExist->rowCount() <= 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'not found repairOutBillCode'
                    ));
                    return;
                }
                $result_delete = $RepairOut->deleteRepairOut($conn, $repairOutBillCode);
                if ($result_delete) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'delete success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_REPAIR_OUT_FAIL,
                    'message' => 'delete fail',
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
        case ActionCode::GET_ALL_REPAIR_OUT:
            echo 'get repair out';
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPAIR_OUT) {
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


                $getAllRepairOut = $RepairOut->getAllRepairOut($conn);
                $num_row = $getAllRepairOut->rowCount();
                $getAllRepairOut_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllRepairOut->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "repairOutBillCode" => $repair_out_bill_code,
                            "saveAt" => $create_date,
                            "licensePlate" => $license_plate,
                            "totalCost" => $total_cost,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllRepairOut_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllRepairOut_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::REPORT_REPAIR_OUT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::REPORT_REPAIR_OUT) {
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

                if (!isset($data->fromRepairOutBillCode) || empty($data->fromRepairOutBillCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid fromRepairOutBillCode'
                    ));
                    return;
                }

                if (!isset($data->toRepairOutBillCode) || empty($data->toRepairOutBillCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid toRepairOutBillCode'
                    ));
                    return;
                }

                if (isset($data->fromDate) && !empty($data->fromDate)) {
                    if (!ValidateDate::checkDateFormat($data->fromDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromDate'
                        ));
                        return;
                    }
                }
                if (isset($data->toDate) && !empty($data->toDate)) {
                    if (!ValidateDate::checkDateFormat($data->toDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid toDate'
                        ));
                        return;
                    }
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $fromRepairOutBillCode = isset($data->fromRepairOutBillCode) ? trim($data->fromRepairOutBillCode) : null;
                $toRepairOutBillCode = isset($data->toRepairOutBillCode) ? trim($data->toRepairOutBillCode) : null;
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;

                $getReprtRepairOut = $RepairOut->reportRepairOut($conn, $fromRepairOutBillCode, $toRepairOutBillCode, $fromDate, $toDate);
                $num_row = $getReprtRepairOut->rowCount();
                $getReprtRepairOut_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getReprtRepairOut->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "repairOutBillCode" => $repair_out_bill_code,
                            "expenseDate"  => $expense_date,
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "repairOrderDate" => $repair_order_date,
                            "repairedDate" => $repair_date,
                            "mileNumber" => $mile_number,
                            "repairTypeId" => $repair_type_id,
                            "note" => $note,
                            "repairStoreId" => $repair_store_code,
                            "invoiceCode" => $invoiceCode,
                            "date" => $date,
                            "totalCost" => $total_cost,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getReprtRepairOut_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getReprtRepairOut_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::REPORT_DAILY_TRUCK_REPAIR_OUT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::REPORT_DAILY_TRUCK_REPAIR_OUT) {
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


                if (!isset($data->fromDate) || empty($data->fromDate)) {
                    if (!ValidateDate::checkDateFormat($data->fromDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromDate'
                        ));
                        return;
                    }
                }
                if (!isset($data->toDate) || empty($data->toDate)) {
                    if (!ValidateDate::checkDateFormat($data->toDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid toDate'
                        ));
                        return;
                    }
                }

                if (!isset($data->whichPart) || !in_array($data->whichPart, array(0, 1))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                // if (!isset($data->fromLicensePlate) || empty($data->fromLicensePlate)) {
                //     echo json_encode(array(
                //         'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //         'message' => 'Invalid fromLicensePlate'
                //     ));
                //     return;
                // }

                // if (!isset($data->toLicensePlate) || empty($data->toLicensePlate)) {
                //     echo json_encode(array(
                //         'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //         'message' => 'Invalid toLicensePlate'
                //     ));
                //     return;
                // }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $fromLicensePlate = isset($data->fromLicensePlate) ? trim($data->fromLicensePlate) : null;
                $toLicensePlate = isset($data->toLicensePlate) ? trim($data->toLicensePlate) : null;



                $getReportDailyRepairOut = $RepairOut->reportDailyRepairOut($conn, $fromDate, $toDate, $whichPart, $fromLicensePlate, $toLicensePlate);
                $num_row = $getReportDailyRepairOut->rowCount();
                $getReportDailyRepairOut_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getReportDailyRepairOut->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "expenseDate" => $expense_date,
                            "repairOutBillCode"  => $repair_out_bill_code,
                            "whichPart" => $which_part,
                            "whichPartName" => $which_part_name,
                            "licensePlate" => $license_plate,
                            "repairOrderDate" => $repair_order_date,
                            "repairDate" => $repair_date,
                            "repairTypeId" => $repair_type_id,
                            "note" => $note,
                            "repairStoreId" => $repair_store_code,
                            "invoiceCode" => $invoiceCode,
                            "date" => $date,
                            "totalCost" => $total_cost,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getReportDailyRepairOut_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getReportDailyRepairOut_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_OUT,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
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
