<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$CentralMoney = new CentralMoneyModel();

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
        case ActionCode::GET_ALL_CENTRAL_MONEY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_CENTRAL_MONEY) {
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

                $getAllCentralMoney = $CentralMoney->getAllCentralMoney($conn);
                $num_row = $getAllCentralMoney->rowCount();
                $getAllCentralMoney_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllCentralMoney->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "cmDate" => $cm_date,
                            "cmCode" => $cm_code,
                            "name" => $name,
                            "price" => $price,
                            "numberOfTruck" => $number_truck,
                            "avg" => $avg,
                            "totalPrice" => $total_price,
                            "totalAvg" => $total_avg,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllCentralMoney_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllCentralMoney_arr['data'],
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
        case ActionCode::GET_CENTRAL_MONEY:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_CENTRAL_MONEY) {
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

                if (trim(!isset($data->cmCode)) || trim($data->cmCode) == null || trim($data->cmCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid cmCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $cmCode = isset($data->cmCode) ? trim($data->cmCode) : null;

                $getCentralMoney = $CentralMoney->getCentralMoney($conn, $cmCode);
                $num_row = $getCentralMoney->rowCount();
                $getCentralMoney_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getCentralMoney->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "cmDate" => $cm_date,
                            "cmCode" => $cm_code,
                            "name" => $name,
                            "price" => $price,
                            "numberOfTruck" => $number_truck,
                            "avg" => $avg,
                            "totalPrice" => $total_price,
                            "totalAvg" => $total_avg,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getCentralMoney_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getCentralMoney_arr['data'],
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

        case ActionCode::CREATE_CENTRAL_MONEY:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_CENTRAL_MONEY) {
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

                // if (!isset($data->cmDate) || trim($data->cmDate) == null || trim($data->cmDate) == "" || !ValidateDate::checkDateFormat($data->cmDate)) {
                //     echo json_encode(array(
                //         'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //         'message' => 'Invalid date'
                //     ));
                //     return;
                // }

                if (!isset($data->cmCode) || trim($data->cmCode) == null || trim($data->cmCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid cmCode'
                    ));
                    return;
                }

                if (!isset($data->name) || trim($data->name) == null || trim($data->name) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid name'
                    ));
                    return;
                }

                if (!isset($data->price) || trim($data->price) == null || trim($data->price) == "" || !is_numeric($data->price) || $data->price < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid price'
                    ));
                    return;
                }

                if (!isset($data->numberOfTruck) || trim($data->numberOfTruck) == null || trim($data->numberOfTruck) == "" || !is_numeric($data->numberOfTruck) || $data->numberOfTruck < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid numberOfTruck'
                    ));
                    return;
                }

                if (!isset($data->avg) || trim($data->avg) == null || trim($data->avg) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid avg'
                    ));
                    return;
                }

                if (!isset($data->totalPrice) || trim($data->totalPrice) == null || trim($data->totalPrice) == "" || !is_numeric($data->totalPrice) || $data->totalPrice < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }


                if (!isset($data->totalAvg) || trim($data->totalAvg) == null || trim($data->totalAvg) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalAvg'
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
                $cmDate = isset($data->cmDate) ? trim($data->cmDate) : null;
                $cmCode = isset($data->cmCode) ? trim($data->cmCode) : null;
                $name = isset($data->name) ? trim($data->name) : null;
                $price = isset($data->price) ? trim($data->price) : null;
                $numberOfTruck = isset($data->numberOfTruck) ? trim($data->numberOfTruck) : null;
                $avg = isset($data->avg) ? trim($data->avg) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $totalAvg = isset($data->totalAvg) ? trim($data->totalAvg) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getCentralMoney = $CentralMoney->getCentralMoney($conn, $cmCode);

                if ($getCentralMoney->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'The cmCode is already in this month, Please use other cmCode '
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $CentralMoney->createNewCentralMoney($conn, $cmDate, $cmCode, $name, $price, $numberOfTruck, $avg, $totalPrice, $totalAvg, $createBy);
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
        case ActionCode::UPDATE_CENTRAL_MONEY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_CENTRAL_MONEY) {
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
                if (!isset($data->cmCode) || trim($data->cmCode) == null || trim($data->cmCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid cmCode'
                    ));
                    return;
                }

                if (!isset($data->name) || trim($data->name) == null || trim($data->name) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid name'
                    ));
                    return;
                }

                if (!isset($data->price) || trim($data->price) == null || trim($data->price) == "" || !is_numeric($data->price) || $data->price < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid price'
                    ));
                    return;
                }

                if (!isset($data->numberOfTruck) || trim($data->numberOfTruck) == null || trim($data->numberOfTruck) == "" || !is_numeric($data->numberOfTruck) || $data->numberOfTruck < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid numberOfTruck'
                    ));
                    return;
                }

                if (!isset($data->avg) || trim($data->avg) == null || trim($data->avg) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid avg'
                    ));
                    return;
                }

                if (!isset($data->totalPrice) || trim($data->totalPrice) == null || trim($data->totalPrice) == "" || !is_numeric($data->totalPrice) || $data->totalPrice < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }


                if (!isset($data->totalAvg) || trim($data->totalAvg) == null || trim($data->totalAvg) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalAvg'
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
                $cmDate = isset($data->cmDate) ? trim($data->cmDate) : null;
                $cmCode = isset($data->cmCode) ? trim($data->cmCode) : null;
                $name = isset($data->name) ? trim($data->name) : null;
                $price = isset($data->price) ? trim($data->price) : null;
                $numberOfTruck = isset($data->numberOfTruck) ? trim($data->numberOfTruck) : null;
                $avg = isset($data->avg) ? trim($data->avg) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $totalAvg = isset($data->totalAvg) ? trim($data->totalAvg) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getCentralMoney = $CentralMoney->getCentralMoney($conn, $cmCode);
                if ($getCentralMoney->rowCount() > 0) {
                    $result = $CentralMoney->updateCentralMoney($conn, $cmDate, $cmCode, $name, $price, $numberOfTruck, $avg, $totalPrice, $totalAvg, $updateBy);
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
