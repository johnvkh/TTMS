<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$refuel = new RefuelModel();

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
        case ActionCode::GET_ALL_REFUEL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REFUEL) {
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

                if (isset($data->fromDate) && ($data->fromDate != "" || $data->fromDate != null)) {
                    if (!ValidateDate::checkDateFormat($data->fromDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromDate'
                        ));
                        return;
                    }
                }

                if (isset($data->toDate) && ($data->toDate != "" || $data->toDate != null)) {
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
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;

                $getAllrefuel = $refuel->getAllrefuel($conn, $fromDate, $toDate);
                $num_row = $getAllrefuel->rowCount();
                $getAllrefuel_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllrefuel->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "performBill" => $perform_bill,
                            "licensePlate" => $license_plate,
                            "refuelDate" => $date_refuel,
                            "sellerCode" => $seller_code,
                            "invoiceCode" => $invoice_code,
                            "dieselPerL" => $qty,
                            "price" => $price,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllrefuel_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllrefuel_arr['data'],
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
        case ActionCode::GET_REFUEL:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REFUEL) {
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

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $performBill = isset($data->performBill) ? trim($data->performBill) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getrefuel = $refuel->getrefuel($conn, $performBill, $licensePlate);
                $num_row = $getrefuel->rowCount();
                $getrefuel_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getrefuel->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "performBill" => $perform_bill,
                            "licensePlate" => $license_plate,
                            "refuelDate" => $date_refuel,
                            "sellerCode" => $seller_code,
                            "invoiceCode" => $invoice_code,
                            "dieselPerL" => $qty,
                            "price" => $price,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getrefuel_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getrefuel_arr['data'],
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
        case ActionCode::CREATE_REFUEL:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REFUEL) {
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

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->refuelDate) || trim($data->refuelDate) == null || trim($data->refuelDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid refuelDate'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->refuelDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format refuelDate'
                        ));
                        return;
                    }
                }


                if (trim(!isset($data->sellerCode)) || trim($data->sellerCode) == null || trim($data->sellerCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid sellerCode'
                    ));
                    return;
                }

                if (trim(!isset($data->dieselPerL)) || trim($data->dieselPerL) == null || trim($data->dieselPerL) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid dieselPerL'
                    ));
                    return;
                }

                if (trim(!isset($data->price)) || trim($data->price) == null || trim($data->price) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid price'
                    ));
                    return;
                }

                if (trim(!isset($data->totalPrice)) || trim($data->totalPrice) == null || trim($data->totalPrice) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }


                if (trim(!isset($data->createBy)) || trim($data->createBy) == null || trim($data->createBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $performBill = isset($data->performBill) ? trim($data->performBill) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $refuelDate = isset($data->refuelDate) ? trim($data->refuelDate) : null;
                $sellerCode = isset($data->sellerCode) ? trim($data->sellerCode) : null;
                $invoiceCode = isset($data->invoiceCode) ? trim($data->invoiceCode) : null;
                $way = isset($data->way) ? trim($data->way) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $dieselPerL = isset($data->dieselPerL) ? trim($data->dieselPerL) : null;
                $price = isset($data->price) ? trim($data->price) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkPerformBill = $refuel->getPerformBill($conn, $performBill);

                if ($checkPerformBill->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_RELEASE_TRUCK_SHARING_BILL_FAIL,
                        'message' => 'The PerformBill is already in the system, Please use other PerformBill'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $refuel->createNewRefuel($conn, $performBill, $licensePlate, $refuelDate, $sellerCode, $invoiceCode, $way, $productCode, $dieselPerL, $price, $totalPrice, $createBy);
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
                        'responseCode' => ErrorCode::CREATE_REFUEL_FAIL,
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
        case ActionCode::UPDATE_RELEASE_TRUCK_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_RELEASE_TRUCK_IN_BILL) {
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

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->refuelDate) || trim($data->refuelDate) == null || trim($data->refuelDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid refuelDate'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->refuelDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format refuelDate'
                        ));
                        return;
                    }
                }


                if (trim(!isset($data->sellerCode)) || trim($data->sellerCode) == null || trim($data->sellerCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid sellerCode'
                    ));
                    return;
                }

                if (trim(!isset($data->dieselPerL)) || trim($data->dieselPerL) == null || trim($data->dieselPerL) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid dieselPerL'
                    ));
                    return;
                }

                if (trim(!isset($data->price)) || trim($data->price) == null || trim($data->price) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid price'
                    ));
                    return;
                }

                if (trim(!isset($data->totalPrice)) || trim($data->totalPrice) == null || trim($data->totalPrice) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
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
                $performBill = isset($data->performBill) ? trim($data->performBill) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $refuelDate = isset($data->refuelDate) ? trim($data->refuelDate) : null;
                $sellerCode = isset($data->sellerCode) ? trim($data->sellerCode) : null;
                $invoiceCode = isset($data->invoiceCode) ? trim($data->invoiceCode) : null;
                $way = isset($data->way) ? trim($data->way) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $dieselPerL = isset($data->dieselPerL) ? trim($data->dieselPerL) : null;
                $price = isset($data->price) ? trim($data->price) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;


                $getRefuel = $refuel->getRefuel($conn, $performBill, $licensePlate);
                if ($getRefuel->rowCount() > 0) {
                    $result = $refuel->updateRefuel($conn, $performBill, $licensePlate, $refuelDate, $sellerCode, $invoiceCode, $way, $productCode, $dieselPerL, $price, $totalPrice, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_REFUEL_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_REFUEL_FAIL,
                    'message' => 'The billRunningNumber is not found in the system, Please use other billRunningNumber'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_REFUEL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REFUEL) {
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


                $actionCode = isset($data->actionCode) ?  trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ?  trim($data->actionNodeId) : null;
                $performBill = isset($data->performBill) ?  trim($data->performBill) : null;

                $check_data = $refuel->getPerformBill($conn, $performBill);
                if (!$check_data > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_REFUEL_FAIL,
                        'message' => 'The perform bill is not found in the system, Please use other perform bill'
                    ));
                    return;
                }

                $result_delete = $refuel->deleteRefuel($conn, $performBill);
                if ($result_delete) {
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
                    'responseCode' => ErrorCode::DELETE_REFUEL_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
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
