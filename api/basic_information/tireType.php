<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$tireTypeModel = new TireTypeModel();
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
        case ActionCode::GET_TIRE_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIRE_TYPE) {
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

                $allTireType = $tireTypeModel->getAllTireType($conn);
                $num_row = $allTireType->rowCount();
                $allTireType_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $allTireType->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireTypeId" => $tire_type_id,
                            "tireTypeName" => $tire_type_name,
                            "status" => $status,
                        );
                        array_push($allTireType_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $allTireType_arr['data'],
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
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_TYPE,
                        'message' => 'data not found',
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
        case ActionCode::INSERT_TIRE_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::INSERT_TIRE_TYPE) {
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

                if (!isset($data->tireTypeName) || trim($data->tireTypeName) == null || trim($data->tireTypeName) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Type Name'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $tireTypeName = isset($data->tireTypeName) ? trim($data->tireTypeName) : null;
                $result = $tireTypeModel->InsertTireType($conn, $tireTypeName);
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
                        'responseCode' => ErrorCode::INSERT_TIRE_TYPE_FAIL,
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
        case ActionCode::UPDATE_TIRE_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TIRE_TYPE) {
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

                if (!isset($data->tireTypeId) || trim($data->tireTypeId) == null || trim($data->tireTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Type Id'
                    ));
                    return;
                }
                if (!isset($data->tireTypeName) || trim($data->tireTypeName) == null || trim($data->tireTypeName) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Type Name'
                    ));
                    return;
                }
                if (!isset($data->status) || trim($data->status) == null || trim($data->status) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid status'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $tireTypeId = isset($data->tireTypeId) ? trim($data->tireTypeId) : null;
                $tireTypeName = isset($data->tireTypeName) ? trim($data->tireTypeName) : null;
                $status = isset($data->status) ? trim($data->status) : null;

                $updateResult = $tireTypeModel->updateTireType($conn, $tireTypeId, $tireTypeName, $status);
                if (!$updateResult) {
                    $res = array(
                        'responseCode' => ErrorCode::UPDATE_TIRE_TYPE_FAIL,
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
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::DELETE_TIRE_TYPE:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIRE_TYPE) {
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

                if (!isset($data->tireTypeId) || trim($data->tireTypeId) == null || trim($data->tireTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Type Id'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $tireTypeId = isset($data->tireTypeId) ? $data->tireTypeId : null;

                $result = $tireTypeModel->deleteTireType($conn, $tireTypeId);
                if ($result) {
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
                    'responseCode' => ErrorCode::DELETE_TIRE_TYPE_FAIL,
                    'message' => 'fail',
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
