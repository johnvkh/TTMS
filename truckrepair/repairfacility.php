<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$RepairFacility = new RepairFacilityModel();
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
        case ActionCode::GET_ALL_REPAIR_FACILITY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPAIR_FACILITY) {
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
                $fromId = isset($data->fromId) ? trim($data->fromId) : null;
                $toId = isset($data->toId) ? trim($data->toId) : null;

                $getAllRepairFacility = $RepairFacility->getAllRepairFacility($conn, $fromId, $toId);
                $num_row = $getAllRepairFacility->rowCount();
                $getAllRepairFacility_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllRepairFacility->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "name" => $name,
                            "enName" => $en_name,
                            "address" => $address,
                            "tel" => $tel,
                            "contactStartDate" => $contact_start_date,
                            "sellType" => $sell_type,
                            "sellGrade" => $sell_grade,
                            "creditAmount" => $credit_amount,
                            "numberCreditsGiven" => $number_credits_given,
                            "contactPersonName" => $contact_person_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllRepairFacility_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllRepairFacility_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_TYPE,
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
        case ActionCode::GET_REPAIR_FACILITY:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_FACILITY) {
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

                if (!isset($data->id) || trim($data->id) == null || trim($data->id) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid id'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $id = isset($data->id) ? trim($data->id) : null;

                $getRepairFacility = $RepairFacility->getRepairFacility($conn, $id);
                $num_row = $getRepairFacility->rowCount();
                $getRepairFacility_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getRepairFacility->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "name" => $name,
                            "enName" => $en_name,
                            "address" => $address,
                            "tel" => $tel,
                            "contactStartDate" => $contact_start_date,
                            "sellType" => $sell_type,
                            "sellGrade" => $sell_grade,
                            "creditAmount" => $credit_amount,
                            "numberCreditsGiven" => $number_credits_given,
                            "contactPersonName" => $contact_person_name,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getRepairFacility_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getRepairFacility_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_TYPE,
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

        case ActionCode::CREATE_REPAIR_FACILITY:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REPAIR_FACILITY) {
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
                if (!isset($data->name) || trim($data->name) == null || trim($data->name) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid name'
                    ));
                    return;
                }

                if (!isset($data->tel) || trim($data->tel) == null || trim($data->tel) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tel'
                    ));
                    return;
                }

                if (!isset($data->contactStartDate) || trim($data->contactStartDate) == null || trim($data->contactStartDate) == "" || !ValidateDate::checkDateFormat($data->contactStartDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid contactStartDate'
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
                $name = isset($data->name) ? trim($data->name) : null;
                $enName = isset($data->enName) ? trim($data->enName) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $contactStartDate = isset($data->contactStartDate) ? trim($data->contactStartDate) : null;
                $sellType = isset($data->sellType) ? trim($data->sellType) : null;
                $sellGrade = isset($data->sellGrade) ? trim($data->sellGrade) : null;
                $creditAmount = isset($data->creditAmount) ? trim($data->creditAmount) : null;
                $numberCreditsGiven = isset($data->numberCreditsGiven) ? trim($data->numberCreditsGiven) : null;
                $contactPersonName = isset($data->contactPersonName) ? trim($data->contactPersonName) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkRepairFacility = $RepairFacility->getRepairFacilityByName($conn, $name);

                if ($checkRepairFacility->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'The Name is already in the system, Please use other Name '
                    ));
                    return;
                }

                $result = $RepairFacility->createNewRepairFacility($conn, $name, $enName, $address, $tel, $contactStartDate, $sellType, $sellGrade, $creditAmount, $numberCreditsGiven, $contactPersonName, $note, $createBy);
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
        case ActionCode::UPDATE_REPAIR_FACILITY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_REPAIR_FACILITY) {
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

                if (!isset($data->id) || trim($data->id) == null || trim($data->id) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid id'
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

                if (!isset($data->tel) || trim($data->tel) == null || trim($data->tel) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tel'
                    ));
                    return;
                }

                if (!isset($data->contactStartDate) || trim($data->contactStartDate) == null || trim($data->contactStartDate) == "" || !ValidateDate::checkDateFormat($data->contactStartDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid contactStartDate'
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
                $id = isset($data->id) ? trim($data->id) : null;
                $name = isset($data->name) ? trim($data->name) : null;
                $enName = isset($data->enName) ? trim($data->enName) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $contactStartDate = isset($data->contactStartDate) ? trim($data->contactStartDate) : null;
                $sellType = isset($data->sellType) ? trim($data->sellType) : null;
                $sellGrade = isset($data->sellGrade) ? trim($data->sellGrade) : null;
                $creditAmount = isset($data->creditAmount) ? trim($data->creditAmount) : null;
                $numberCreditsGiven = isset($data->numberCreditsGiven) ? trim($data->numberCreditsGiven) : null;
                $contactPersonName = isset($data->contactPersonName) ? trim($data->contactPersonName) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $checkRepairFacility = $RepairFacility->getRepairFacilityByName($conn, $name);

                if ($checkRepairFacility->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'The Name is already in the system, Please use other Name '
                    ));
                    return;
                }
                $getRepairFacility = $RepairFacility->getRepairFacility($conn, $id);

                if ($getRepairFacility->rowCount() > 0) {
                    $result = $RepairFacility->updateRepairFacility($conn, $id, $name, $enName, $address, $tel, $contactStartDate, $sellType, $sellGrade, $creditAmount, $numberCreditsGiven, $contactPersonName, $note, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_REPAIR_FACILITY_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_REPAIR_FACILITY_FAIL,
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
        case ActionCode::DELETE_REPAIR_FACILITY:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REPAIR_FACILITY) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid actionNodeId'
                    ));
                    return;
                }

                if (!isset($data->id) || empty($data->id)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid id'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $id = isset($data->id) ? trim($data->id) : null;

                $getRepairFacility = $RepairFacility->getRepairFacility($conn, $id);

                if ($getRepairFacility->rowCount() <= 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'not found this id in the system'
                    ));
                    return;
                }
                $deleteResult = $RepairFacility->deleteRepairFacility($conn, $id);

                if ($deleteResult) {
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
                    'responseCode' => ErrorCode::DELETE_REPAIR_FACILITY_FAIL,
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
