<?php
require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$OwnerTruckSharingModel = new OwnerTruckSharingModel();
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
        case ActionCode::GET_ALL_OWNER_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_OWNER_TRUCK_SHARING) {
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

                $getAllOwnerTruckSharing = $OwnerTruckSharingModel->getAllOwnerTruckSharing($conn);
                $num_row = $getAllOwnerTruckSharing->rowCount();
                $getAllOwnerTruckSharing_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllOwnerTruckSharing->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "code" => $code,
                            "name" => $name,
                            "name_en" => $name_en,
                            "address" => $address,
                            "tel" => $tel,
                            "startContactDate" => $start_contact_date,
                            "type" => $type,
                            "grade" => $grade,
                            "createLimit" => $credit_limit,
                            "numberDayCredit" => $number_day_credit,
                            "contactNameWith" => $contact_with_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllOwnerTruckSharing_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllOwnerTruckSharing_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_OWNER_TRUCK_SHARING,
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
        case ActionCode::GET_OWNER_TRUCK_SHARING:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_OWNER_TRUCK_SHARING) {
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

                if (trim(!isset($data->code)) || trim($data->code) == null || trim($data->code) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid code'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $code = isset($data->code) ? trim($data->code) : null;

                $getOwnerTruckSharing = $OwnerTruckSharingModel->getOwnerTruckSharing($conn, $code);
                $num_row = $getOwnerTruckSharing->rowCount();
                $getOwnerTruckSharing_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getOwnerTruckSharing->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "code" => $code,
                            "name" => $name,
                            "name_en" => $name_en,
                            "address" => $address,
                            "tel" => $tel,
                            "startContactDate" => $start_contact_date,
                            "type" => $type,
                            "grade" => $grade,
                            "createLimit" => $credit_limit,
                            "numberDayCredit" => $number_day_credit,
                            "contactNameWith" => $contact_with_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getOwnerTruckSharing_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getOwnerTruckSharing_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
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
        case ActionCode::CREATE_OWNER_TRUCK_SHARING:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_OWNER_TRUCK_SHARING) {
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

                if (trim(!isset($data->code)) || trim($data->code) == null || trim($data->code) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid code'
                    ));
                    return;
                }
                if (trim(!isset($data->startContactDate)) || trim($data->startContactDate) == null || trim($data->startContactDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid startContactDate'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->startContactDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start contact date'
                        ));
                        return;
                    }
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $code = isset($data->code) ? trim($data->code) : null;
                $name = isset($data->name) ? trim($data->name) : null;
                $nameEn = isset($data->nameEn) ? trim($data->nameEn) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $startContactDate = isset($data->startContactDate) ? trim($data->startContactDate) : null;
                $type = isset($data->type) ? trim($data->type) : null;
                $grade = isset($data->grade) ? trim($data->grade) : null;
                $limitCreditCard = isset($data->limitCreditCard) ? trim($data->limitCreditCard) : null;
                $numberDayCredit = isset($data->numberDayCredit) ? trim($data->numberDayCredit) : null;
                $contactNameWith = isset($data->contactNameWith) ? trim($data->contactNameWith) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;
                $getOwnerTruckSharing = $OwnerTruckSharingModel->getOwnerTruckSharing($conn, $code);
                if ($getOwnerTruckSharing->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The  Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $OwnerTruckSharingModel->createNewOwnerTruckSharing(
                    $conn,
                    $code,
                    $name,
                    $nameEn,
                    $address,
                    $tel,
                    $startContactDate,
                    $type,
                    $grade,
                    $limitCreditCard,
                    $numberDayCredit,
                    $contactNameWith,
                    $note,
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
                        'responseCode' => ErrorCode::CREATE_TIRE_TRUCK_FAIL,
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
        case ActionCode::UPDATE_OWNER_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_OWNER_TRUCK_SHARING) {
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


                if (trim(!isset($data->code)) || trim($data->code) == null || trim($data->code) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid code'
                    ));
                    return;
                }
                if (trim(!isset($data->startContactDate)) || trim($data->startContactDate) == null || trim($data->startContactDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid startContactDate'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->startContactDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start contact date'
                        ));
                        return;
                    }
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $code = isset($data->code) ? trim($data->code) : null;
                $name = isset($data->name) ? trim($data->name) : null;
                $nameEn = isset($data->nameEn) ? trim($data->nameEn) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $startContactDate = isset($data->startContactDate) ? trim($data->startContactDate) : null;
                $type = isset($data->type) ? trim($data->type) : null;
                $grade = isset($data->grade) ? trim($data->grade) : null;
                $limitCreditCard = isset($data->limitCreditCard) ? trim($data->limitCreditCard) : null;
                $numberDayCredit = isset($data->numberDayCredit) ? trim($data->numberDayCredit) : null;
                $contactNameWith = isset($data->contactNameWith) ? trim($data->contactNameWith) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $updateBy = 'admin';

                $getOwnerTruckSharing = $OwnerTruckSharingModel->getOwnerTruckSharing($conn, $code);

                if ($getOwnerTruckSharing->rowCount() > 0) {
                    $updateResult = $OwnerTruckSharingModel->updateOwnerTruckSharing(
                        $conn,
                        $code,
                        $name,
                        $nameEn,
                        $address,
                        $tel,
                        $startContactDate,
                        $type,
                        $grade,
                        $limitCreditCard,
                        $numberDayCredit,
                        $contactNameWith,
                        $note,
                        $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_OWNER_TRUCK_SHARING_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_OWNER_TRUCK_SHARING_FAIL,
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
        case ActionCode::DELETE_OWNER_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_OWNER_TRUCK_SHARING) {
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
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_DELIVERY_LOCATION_FAIL,
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
