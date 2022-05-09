<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckSharingWage = new TruckSharingWageModel();

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
        case ActionCode::GET_ALL_TRUCK_SHARING_WAGE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRUCK_SHARING_WAGE) {
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
                $date = isset($data->date) ? trim($data->date) : null;
                $ownerTruckSharingCode = isset($data->ownerTruckSharingCode) ? trim($data->ownerTruckSharingCode) : null;

                $getAllTruckSharingWage = $TruckSharingWage->getAllTruckSharingWage($conn, $date, $ownerTruckSharingCode);
                $num_row = $getAllTruckSharingWage->rowCount();
                $getAllTruckSharingWage_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTruckSharingWage->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckSharingPaymentBill" => $truck_sharing_payment_bill,
                            "paymentDate" => $paytment_date,
                            "ownerTruckSharingCode" => $owner_truck_truck_sharing_code,
                            "truckSharingWorkingBill" => $truck_sharing_work_bill,
                            "truckSharingWorkingDate" => $truck_sharing_working_date,
                            "licensePlate" => $license_plate,
                            "truckSharingMoney" => (int)$truck_sharing_money,
                            "costOil" => (int)$cost_oil,
                            "damages" => (int)$damnages,
                            "productInsurance" => (int)$product_insurance,
                            "other" => (int)$other,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTruckSharingWage_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllTruckSharingWage_arr['data'],
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
        case ActionCode::GET_TRUCK_SHARING_WAGE:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TRUCK_SHARING_WAGE) {
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

                if (trim(!isset($data->truckSharingPaymentBill)) || trim($data->truckSharingPaymentBill) == null || trim($data->truckSharingPaymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingPaymentBill'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckSharingPaymentBill = isset($data->truckSharingPaymentBill) ? trim($data->truckSharingPaymentBill) : null;

                $getTruckSharingWage = $TruckSharingWage->getTruckSharingWage($conn, $truckSharingPaymentBill);
                $num_row = $getTruckSharingWage->rowCount();
                $getTruckSharingWage_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTruckSharingWage->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckSharingPaymentBill" => $truck_sharing_payment_bill,
                            "paymentDate" => $paytment_date,
                            "ownerTruckSharingCode" => $owner_truck_truck_sharing_code,
                            "truckSharingWorkingBill" => $truck_sharing_work_bill,
                            "truckSharingWorkingDate" => $truck_sharing_working_date,
                            "licensePlate" => $license_plate,
                            "truckSharingMoney" => (int)$truck_sharing_money,
                            "costOil" => (int)$cost_oil,
                            "damages" => (int)$damnages,
                            "productInsurance" => (int)$product_insurance,
                            "other" => (int)$other,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTruckSharingWage_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getTruckSharingWage_arr['data'],
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
        case ActionCode::CREATE_TRUCK_SHARING_WAGE:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRUCK_SHARING_WAGE) {
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


                if (!isset($data->truckSharingPaymentBill) || trim($data->truckSharingPaymentBill) == null || trim($data->truckSharingPaymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingPaymentBill'
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

                if (!isset($data->ownerTruckSharingCode) || trim($data->ownerTruckSharingCode) == null || trim($data->ownerTruckSharingCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ownerTruckSharingCode'
                    ));
                    return;
                }


                if (!isset($data->truckSharingWorkingBill) || trim($data->truckSharingWorkingBill) == null || trim($data->truckSharingWorkingBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingBill'
                    ));
                    return;
                }

                if (!isset($data->truckSharingWorkingDate) || trim($data->truckSharingWorkingDate) == null || trim($data->truckSharingWorkingDate) == "" || !ValidateDate::checkDateFormat($data->truckSharingWorkingDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingDate'
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

                if (isset($data->truckSharingMoney) && (!is_int($data->truckSharingMoney) || $data->truckSharingMoney < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid costOil'
                    ));
                    return;
                }
                if (isset($data->costOil) && (!is_int($data->costOil) || $data->costOil < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid costOil'
                    ));
                    return;
                }
                if (isset($data->damages) && (!is_int($data->damages) || $data->damages < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid damages'
                    ));
                    return;
                }
                if (isset($data->productInsurance) && (!is_int($data->productInsurance) || $data->productInsurance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid productInsurance'
                    ));
                    return;
                }
                if (isset($data->other) && (!is_int($data->other) || $data->other < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid other'
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
                $truckSharingPaymentBill = isset($data->truckSharingPaymentBill) ? trim($data->truckSharingPaymentBill) : null;
                $paymentDate = isset($data->paymentDate) ? trim($data->paymentDate) : null;
                $ownerTruckSharingCode = isset($data->ownerTruckSharingCode) ? trim($data->ownerTruckSharingCode) : null;
                $truckSharingWorkingBill = isset($data->truckSharingWorkingBill) ? trim($data->truckSharingWorkingBill) : null;
                $truckSharingWorkingDate = isset($data->truckSharingWorkingDate) ? trim($data->truckSharingWorkingDate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckSharingMoney = isset($data->truckSharingMoney) ? trim($data->truckSharingMoney) : null;
                $costOil = isset($data->costOil) ? trim($data->costOil) : null;
                $damages = isset($data->damages) ? trim($data->damages) : null;
                $productInsurance = isset($data->productInsurance) ? trim($data->productInsurance) : null;
                $other = isset($data->other) ? trim($data->other) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getTruckSharingWage = $TruckSharingWage->getTruckSharingWage($conn, $truckSharingPaymentBill);
                if ($getTruckSharingWage->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
                        'message' => 'The Bill Code is already in the system, Please use other Bill Code.'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $TruckSharingWage->createNewTruckSharingWage($conn, $truckSharingPaymentBill, $paymentDate, $ownerTruckSharingCode, $truckSharingWorkingBill, $truckSharingWorkingDate, $licensePlate, $truckSharingMoney, $costOil, $damages, $productInsurance, $other, $createBy);
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
        case ActionCode::UPDATE_TRUCK_SHARING_WAGE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRUCK_SHARING_WAGE) {
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


                if (!isset($data->truckSharingPaymentBill) || trim($data->truckSharingPaymentBill) == null || trim($data->truckSharingPaymentBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingPaymentBill'
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

                if (!isset($data->ownerTruckSharingCode) || trim($data->ownerTruckSharingCode) == null || trim($data->ownerTruckSharingCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ownerTruckSharingCode'
                    ));
                    return;
                }


                if (!isset($data->truckSharingWorkingBill) || trim($data->truckSharingWorkingBill) == null || trim($data->truckSharingWorkingBill) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingBill'
                    ));
                    return;
                }

                if (!isset($data->truckSharingWorkingDate) || trim($data->truckSharingWorkingDate) == null || trim($data->truckSharingWorkingDate) == "" || !ValidateDate::checkDateFormat($data->truckSharingWorkingDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckSharingWorkingDate'
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

                if (isset($data->truckSharingMoney) && (!is_int($data->truckSharingMoney) || $data->truckSharingMoney < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid costOil'
                    ));
                    return;
                }
                if (isset($data->costOil) && (!is_int($data->costOil) || $data->costOil < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid costOil'
                    ));
                    return;
                }
                if (isset($data->damages) && (!is_int($data->damages) || $data->damages < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid damages'
                    ));
                    return;
                }
                if (isset($data->productInsurance) && (!is_int($data->productInsurance) || $data->productInsurance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid productInsurance'
                    ));
                    return;
                }
                if (isset($data->other) && (!is_int($data->other) || $data->other < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid other'
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
                $truckSharingPaymentBill = isset($data->truckSharingPaymentBill) ? trim($data->truckSharingPaymentBill) : null;
                $paymentDate = isset($data->paymentDate) ? trim($data->paymentDate) : null;
                $ownerTruckSharingCode = isset($data->ownerTruckSharingCode) ? trim($data->ownerTruckSharingCode) : null;
                $truckSharingWorkingBill = isset($data->truckSharingWorkingBill) ? trim($data->truckSharingWorkingBill) : null;
                $truckSharingWorkingDate = isset($data->truckSharingWorkingDate) ? trim($data->truckSharingWorkingDate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckSharingMoney = isset($data->truckSharingMoney) ? trim($data->truckSharingMoney) : null;
                $costOil = isset($data->costOil) ? trim($data->costOil) : null;
                $damages = isset($data->damages) ? trim($data->damages) : null;
                $productInsurance = isset($data->productInsurance) ? trim($data->productInsurance) : null;
                $other = isset($data->other) ? trim($data->other) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getTruckSharingWage = $TruckSharingWage->getTruckSharingWage($conn, $truckSharingPaymentBill);
                if ($getTruckSharingWage->rowCount() > 0) {
                    $result = $TruckSharingWage->updateTruckSharingWage($conn, $truckSharingPaymentBill, $paymentDate, $ownerTruckSharingCode, $truckSharingWorkingBill, $truckSharingWorkingDate, $licensePlate, $truckSharingMoney, $costOil, $damages, $productInsurance, $other, $updateBy);
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
        case ActionCode::DELETE_TRUCK_SHARING_WAGE:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_SHARING_WAGE) {
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
