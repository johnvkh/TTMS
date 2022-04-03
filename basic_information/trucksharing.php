<?php

require_once '../../includes/Autoload.php';

$db = new DatabaseConfig();
$conn = $db->connection();
$TruckSharingModel = new TruckSharingModel();
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
        case ActionCode::GET_ALL_TRUCK_SHARING:

            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_ALL_TRUCK_SHARING) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionCode) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $allruckSharing = $TruckSharingModel->getAllTruckSharing($conn);

                $num_row = $allruckSharing->rowCount();
                $allruckSharing_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $allruckSharing->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "licensePlate" => $license_plate,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckTypeCode" => $truck_type_code,
                            "truckTypeName" => $truck_type_name,
                            "ownerTruckId" => $owner_truck_code,
                            "ownerTruckName" => $owner_truck_name,
                            "truckRegistrationBackPart" => $truck_registration_back_part,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($allruckSharing_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $allruckSharing_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                } else {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_SHARING,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::GET_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_TRUCK_SHARING) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || $data->licensePlate == null || $data->licensePlate == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $checkTruckSharing = $TruckSharingModel->getTruckSharing($conn, $licensePlate);

                $num_row = $checkTruckSharing->rowCount();
                $truckRegistrationHeadPart_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $checkTruckSharing->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "licensePlate" => $license_plate,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckTypeCode" => $truck_type_code,
                            "truckTypeName" => $truck_type_name,
                            "ownerTruckId" => $owner_truck_code,
                            "ownerTruckName" => $owner_truck_name,
                            "truckRegistrationBackPart" => $truck_registration_back_part,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($truckRegistrationHeadPart_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $truckRegistrationHeadPart_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                        'message' => 'Not Found Data With That Code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::CREATE_TRUCK_SHARING) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || $data->licensePlate === null || $data->licensePlate === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || $data->provinceId === null || $data->provinceId === "" || !ValidateString::checkProvince($data->provinceId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || $data->truckBrandId === null || $data->truckBrandId === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckTypeCode) || $data->truckTypeCode === null || $data->truckTypeCode === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeCode'
                    ));
                    return;
                }

                if (!isset($data->ownerTruckCode) || $data->ownerTruckCode === null || $data->ownerTruckCode === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ownerTruckCode'
                    ));
                    return;
                }

                if (!isset($data->truckRegistrationBackPart) || $data->truckRegistrationBackPart === null || $data->truckRegistrationBackPart === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckRegistrationBackPart'
                    ));
                    return;
                }


                if (!isset($data->createBy) || $data->createBy === null || $data->createBy === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckTypeCode = isset($data->truckTypeCode) ? trim($data->truckTypeCode) : null;
                $ownerTruckCode = isset($data->ownerTruckCode) ? trim($data->ownerTruckCode) : null;
                $truckRegistrationBackPart = isset($data->truckRegistrationBackPart) ? trim($data->truckRegistrationBackPart) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkTruckSharing = $TruckSharingModel->getTruckSharing($conn, $licensePlate);

                if ($checkTruckSharing->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_SHARING_FAIL,
                        'message' => 'The licensePlate is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $TruckSharingModel->createNewTruckSharing(
                        $conn, $licensePlate, $provinceId, $truckBrandId, $truckTypeCode, $ownerTruckCode, $truckRegistrationBackPart, $createBy
                );

                if (!$result) {
                    $res = array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_SHARING_FAIL,
                        'message' => 'success',
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
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::UPDATE_TRUCK_SHARING:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::UPDATE_TRUCK_SHARING) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || $data->licensePlate === null || $data->licensePlate === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || $data->provinceId === null || $data->provinceId === "" || !ValidateString::checkProvince($data->provinceId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || $data->truckBrandId === null || $data->truckBrandId === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckTypeCode) || $data->truckTypeCode === null || $data->truckTypeCode === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeCode'
                    ));
                    return;
                }

                if (!isset($data->ownerTruckCode) || $data->ownerTruckCode === null || $data->ownerTruckCode === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid ownerTruckCode'
                    ));
                    return;
                }

                if (!isset($data->truckRegistrationBackPart) || $data->truckRegistrationBackPart === null || $data->truckRegistrationBackPart === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckRegistrationBackPart'
                    ));
                    return;
                }


                if (!isset($data->updateBy) || $data->updateBy === null || $data->updateBy === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckTypeCode = isset($data->truckTypeCode) ? trim($data->truckTypeCode) : null;
                $ownerTruckCode = isset($data->ownerTruckCode) ? trim($data->ownerTruckCode) : null;
                $truckRegistrationBackPart = isset($data->truckRegistrationBackPart) ? trim($data->truckRegistrationBackPart) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $checkTruckSharing = $TruckSharingModel->getTruckSharing($conn, $licensePlate);

                if ($checkTruckSharing->rowCount() > 0) {
                    $updateResult = $TruckSharingModel->updateTruckSharing(
                            $conn, $licensePlate, $provinceId, $truckBrandId, $truckTypeCode, $ownerTruckCode, $truckRegistrationBackPart, $updateBy
                    );

                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_TRUCK_SHARING_FAIL,
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

                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TRUCK_SHARING_FAIL,
                    'message' => 'The licensePlate Code is not found in the system, Please use another code'
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
        case ActionCode::DELETE_TRUCK_SHARING:
            try {
                if (!isset($data->truckRegistrationBackPart) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_SHARING) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckTypeCode) || $data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));
                    return;
                }
                
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TRUCK_SHARING_FAIL,
                    'message' => 'The Id is not found in the system, Please use another code'
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
        default:
            echo json_encode(array(
                'responseCode' => ErrorCode::UNKNOWN_ERROR,
                'message' => 'Something went wrong. Please check your data and try again.',
                'timestamp' => $timestamp
            ));
            http_response_code(200);
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
