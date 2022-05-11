<?php
require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$TireHistoryModel = new TireHistoryModel();

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
        case ActionCode::GET_ALL_TIRE_HISTORY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TIRE_HISTORY) {
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

                if (!isset($data->pageNo) || $data->pageNo == null || $data->pageNo == "") {
                    $page_no = 1;
                } else {
                    $page_no = (int) trim($data->pageNo);
                }
                $page_arr = $TireHistoryModel->page($conn, $page_no);
                (int) $offset = isset($page_arr['offset']) ? $page_arr['offset'] : 0;
                (int) $per_page = isset($page_arr['perPage']) ? $page_arr['perPage'] : 0;
                $total_result = isset($page_arr['totalResult']) ? $page_arr['totalResult'] : 0;

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $getAllTireHistory = $TireHistoryModel->getAllTireHistory($conn, $offset, $per_page);
                $num_row = $getAllTireHistory->rowCount();
                $getAllTireHistory_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTireHistory->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireCode" => $tire_code,
                            "tireBrand" => $tire_brand,
                            "tireModel" => $tire_model,
                            "size" => $size,
                            "tireType" => $tire_type,
                            "useDate" => $use_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTireHistory_arr['data'], $data);
                    }
                    $res = array(
                        'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllTireHistory_arr['data'],
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
        case ActionCode::GET_TIRE_HISTORY:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIRE_HISTORY) {
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

                if (trim(!isset($data->tireCode)) || trim($data->tireCode) == null || trim($data->tireCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $tireCode = isset($data->tireCode) ? trim($data->tireCode) : null;

                $getTireHistory = $TireHistoryModel->getTireHistory($conn, $tireCode);
                $num_row = $getTireHistory->rowCount();
                $getTireHistory_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTireHistory->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireCode" => $tire_code,
                            "tireBrand" => $tire_brand,
                            "tireModel" => $tire_model,
                            "size" => $size,
                            "tireType" => $tire_type,
                            "useDate" => $use_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTireHistory_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $getTireHistory_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::GET_ALL_REPORT_TIRE_EACH_TYPE:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPORT_TIRE_EACH_TYPE) {
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

                if (!isset($data->tireType) || trim($data->tireType) == null || trim($data->tireType) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tireType'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $tireType = isset($data->tireType) ? trim($data->tireType) : null;

                $getTireHistory = $TireHistoryModel->getTireType($conn, $tireType);
                $num_row = $getTireHistory->rowCount();
                $getTireHistory_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTireHistory->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireCode" => $tire_code,
                            "tireBrand" => $tire_brand,
                            "tireModel" => $tire_model,
                            "size" => $size,
                            "tireType" => $tire_type,
                            "useDate" => $use_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTireHistory_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $getTireHistory_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::CREATE_TIRE_HISTORY:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TIRE_HISTORY) {
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

                if (trim(!isset($data->tireCode)) || trim($data->tireCode) == null || trim($data->tireCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Code'
                    ));
                    return;
                }

                if (!isset($data->tireType) || trim($data->tireType) == null || trim($data->tireType) == "" || !ValidateString::checkTireType(trim($data->tireType))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Tire Type'
                    ));
                    return;
                }

                if (!isset($data->useDate) || trim($data->useDate) == null || trim($data->useDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid use Date'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->useDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format use Date'
                        ));
                        return;
                    }
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
                $tireCode = isset($data->tireCode) ? trim($data->tireCode) : null;
                $tireBrand = isset($data->tireBrand) ? trim($data->tireBrand) : null;
                $tireModel = isset($data->tireModel) ? trim($data->tireModel) : null;
                $size = isset($data->size) ? trim($data->size) : null;
                $tireType = isset($data->tireType) ? trim($data->tireType) : null;
                $useDate = isset($data->useDate) ? trim($data->useDate) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getTireHistory = $TireHistoryModel->getTireHistory($conn, $tireCode);

                if ($getTireHistory->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The  tire Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $TireHistoryModel->createNewTireHistory(
                    $conn,
                    $tireCode,
                    $tireBrand,
                    $tireModel,
                    $size,
                    $tireType,
                    $useDate,
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
                        'responseCode' => ErrorCode::CREATE_TIRE_HISTORY_FAIL,
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
        case ActionCode::UPDATE_TIRE_HISTORY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TIRE_HISTORY) {
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

                if (trim(!isset($data->tireCode)) || trim($data->tireCode) == null || trim($data->tireCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Code'
                    ));
                    return;
                }

                if (!isset($data->tireType) || trim($data->tireType) == null || trim($data->tireType) == "" || !ValidateString::checkTireType(trim($data->tireType))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Tire Type'
                    ));
                    return;
                }


                if (!isset($data->useDate) || trim($data->useDate) == null || trim($data->useDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid use Date'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->useDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format use Date'
                        ));
                        return;
                    }
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
                $tireCode = isset($data->tireCode) ? trim($data->tireCode) : null;
                $tireBrand = isset($data->tireBrand) ? trim($data->tireBrand) : null;
                $tireModel = isset($data->tireModel) ? trim($data->tireModel) : null;
                $size = isset($data->size) ? trim($data->size) : null;
                $tireType = isset($data->tireType) ? trim($data->tireType) : null;
                $useDate = isset($data->useDate) ? trim($data->useDate) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;


                $getTireHistory = $TireHistoryModel->getTireHistory($conn, $tireCode);

                if ($getTireHistory->rowCount() > 0) {
                    $updateResult = $TireHistoryModel->updateTireHistory(
                        $conn,
                        $tireCode,
                        $tireBrand,
                        $tireModel,
                        $size,
                        $tireType,
                        $useDate,
                        $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_TIRE_HISTORY_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        echo json_encode($res);
                        http_response_code(200);
                        return;
                    } else {
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
                    }
                }

                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TIRE_HISTORY_FAIL,
                    'message' => 'The Code is not found in the system, Please use another code'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TIRE_HISTORY:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIRE_HISTORY) {
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
                http_response_code(200); //Expectation Failed
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
