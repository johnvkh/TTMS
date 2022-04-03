<?php

require_once '../../includes/Autoload.php';
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckRegistrationHeadPart = new TruckRegistrationHeadPartModel();
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
        case ActionCode::GET_ALL_TRUCK_REGISTRATION_HEAD_PART:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_ALL_TRUCK_REGISTRATION_HEAD_PART) {
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

                $allRegistrationHeadPart = $TruckRegistrationHeadPart->getAllTruckRegistrationHeadPart($conn);
                $num_row = $allRegistrationHeadPart->rowCount();
                $allRegistrationHeadPart_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $allRegistrationHeadPart->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckColorId" => $truck_color_id,
                            "truckColorName" => $truck_color_name,
                            "truckModelCode" => $truck_model_code,
                            "truckTypeId" => $truck_type_id,
                            "truckTypeName" => $truck_type_name,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "truckEngineNumber" => $truck_engine_number,
                            "truckBodyNumber" => $truck_body_number,
                            "power" => $power,
                            "batteryId" => $battery_id,
                            "batteryName" => $battery_name,
                            "changedBatteryLatestDate" => $change_battery_latest_date,
                            "fireExtinguisher" => $fire_extinguisher,
                            "frontCamera" => $front_camera,
                            "tools" => $tools,
                            "cardCloth" => $card_cloth,
                            "gps" => $gps,
                            "verticalTraffic" => $vertical_traffic,
                            "straps" => $straps,
                            "chains" => $chains,
                            "registrationDate" => $registration_date,
                            "registrationExpiredDate" => $registration_date_expired,
                            "technicRenewalDate" => $technical_renewal_date,
                            "technicExpiredDate" => $technical_expired_date,
                            "driversLicense" => $driver_license,
                            "annualFeeReceipt" => $annual_fee_receipt,
                            "tungsitRenewalDate" => $tungsit_renewal_date,
                            "tungsitRenewalExpiredDate" => $tungsit_renewal_expired_date,
                            "insuranceRenewalDate" => $insurance_renewal_date,
                            "insuranceRenewalExpiredDate" => $insurance_expired_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date,
                        );
                        array_push($allRegistrationHeadPart_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'result' => $num_row,
                        'data' => $allRegistrationHeadPart_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::GET_TRUCK_REGISTRATION_HEAD_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_TRUCK_REGISTRATION_HEAD_PART) {
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


                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $get_truck_registration_head_part_by_license_plate = $TruckRegistrationHeadPart->getTruckRegistrationHeadPart($conn, $licensePlate);
                // print_r($get_truck_registration_head_part_by_license_plate);
                // exit;
                $get_change_oil = $TruckRegistrationHeadPart->getChangeOil($conn, $licensePlate);
                $change_oil_arr = array();
                while ($change_oil_row = $get_change_oil->fetch(PDO::FETCH_ASSOC)) {
                    extract($change_oil_row);
                    $data = array(
                        "licensePlate" => $license_plate,
                        "kmNumberChangeEngineOilNextTime" => $km_number_change_engine_oil_next_time,
                        "kmNumberChangeGearOilNextTime" => $km_number_change_gear_oil_next_time
                    );

                    array_push($change_oil_arr, $data);
                }

                $get_tire_truck_by_license_plate = $TruckRegistrationHeadPart->getTireTruck($conn, $licensePlate);
                $tire_truck_arr = array();
                while ($row_tire_truck = $get_tire_truck_by_license_plate->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_tire_truck);
                    $data = array(
                        "whichPart" => $which_part,
                        "wheelPosition" => $wheel_position,
                        "tireCode" => $tire_code,
                        "changedTireLatestDate" => $changed_tire_latest_date,
                        "tireBrandId" => $tire_brand_id,
                        "tireBrandName" => null,
                        "tireSize" => $tire_size
                    );
                    array_push($tire_truck_arr, $data);
                }

                $num_row = $get_truck_registration_head_part_by_license_plate->rowCount();
                $get_truck_registration_head_part_by_license_plate_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_truck_registration_head_part_by_license_plate->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckColorId" => $truck_color_id,
                            "truckColorName" => $truck_color_name,
                            "truckModelCode" => $truck_model_code,
                            "truckTypeId" => $truck_type_id,
                            "truckTypeName" => $truck_type_name,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "truckEngineNumber" => $truck_engine_number,
                            "truckBodyNumber" => $truck_body_number,
                            "power" => $power,
                            "batteryId" => $battery_id,
                            "batteryName" => $battery_name,
                            "changedBatteryLatestDate" => $change_battery_latest_date,
                            "fireExtinguisher" => $fire_extinguisher,
                            "frontCamera" => $front_camera,
                            "tools" => $tools,
                            "cardCloth" => $card_cloth,
                            "gps" => $gps,
                            "verticalTraffic" => $vertical_traffic,
                            "straps" => $straps,
                            "chains" => $chains,
                            "registrationDate" => $registration_date,
                            "registrationExpiredDate" => $registration_date_expired,
                            "technicRenewalDate" => $technical_renewal_date,
                            "technicExpiredDate" => $technical_expired_date,
                            "driversLicense" => $driver_license,
                            "annualFeeReceipt" => $annual_fee_receipt,
                            "tungsitRenewalDate" => $tungsit_renewal_date,
                            "tungsitRenewalExpiredDate" => $tungsit_renewal_expired_date,
                            "insuranceRenewalDate" => $insurance_renewal_date,
                            "insuranceRenewalExpiredDate" => $insurance_expired_date,
                            "changeOil" => $change_oil_arr,
                            "tireTruck" => $tire_truck_arr,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date,
                        );
                        array_push($get_truck_registration_head_part_by_license_plate_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $get_truck_registration_head_part_by_license_plate_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::CREATE_TRUCK_REGISTRATION_HEAD_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::CREATE_TRUCK_REGISTRATION_HEAD_PART) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || !is_numeric($data->whichPart)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || !is_numeric($data->truckBrandId) || empty($data->truckBrandId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckColorId) || !is_numeric($data->truckColorId) || empty($data->truckColorId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckColorId'
                    ));
                    return;
                }
                //have truck_model_code  can null
//                if (!isset($data->truckModelCode) || !is_numeric($data->truckModelCode) || empty($data->truckModelCode)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truck Model Code'
//                    ));
//                    return;
//                }

                if (!isset($data->truckTypeId) || !is_numeric($data->truckTypeId) || empty($data->truckTypeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeId'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || !is_numeric($data->provinceId) || empty($data->provinceId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

//                //truck engine number can be null
//                if (!isset($data->truckEngineNumber) || !is_numeric($data->truckEngineNumber) || empty($data->truckEngineNumber)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckEngineNumber'
//                    ));
//                    return;
//                }
//                //truck body number can be null
//                if (!isset($data->truckBodyNumber) || !is_numeric($data->truckBodyNumber) || empty($data->truckBodyNumber)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckEngineNumber'
//                    ));
//                    return;
//                }

                if (isset($data->power)) {
                    if (!is_numeric($data->power)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid power'
                        ));
                        return;
                    }
                }

                //long tha bien
                if (isset($data->registrationDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationDate'
                        ));
                        return;
                    }
                }

                //long tha bien mot a yu
                if (isset($data->registrationExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registration ExpiredDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicExpiryDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitExpiryDate'
                        ));
                        return;
                    }
                }
                //annual_fee_receipt kha tham niem thang  can null
//                if (!isset($data->annualFeeReceipt) || !is_numeric($data->annualFeeReceipt) || empty($data->annualFeeReceipt)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid annual Fee Receipt'
//                    ));
//                    return;
//                }
                //khao pa kan phai
                if (isset($data->insuranceRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceRenewalDate'
                        ));
                        return;
                    }
                }

                //pa kan phai mot a yu
                if (isset($data->insuranceExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceExpiryDate'
                        ));
                        return;
                    }
                }

                //driver License can null
//                if (!isset($data->driverLicense) || !is_numeric($data->driverLicense) || empty($data->driverLicense)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid driver License'
//                    ));
//                    return;
//                }

                if (isset($data->kmChangeEngineOilNextTime)) {
                    if (!is_numeric($data->kmChangeEngineOilNextTime)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid kmChangeEngineOilNextTime'
                        ));
                        return;
                    }
                }

                if (isset($data->kmChangeGearOilNextTime)) {
                    if (!is_numeric($data->kmChangeGearOilNextTime)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid kmChangeGearOilNextTime'
                        ));
                        return;
                    }
                }

                if (!isset($data->batteryId) || !is_numeric($data->batteryId) || empty($data->batteryId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryId'
                    ));
                    return;
                }

                if (isset($data->changeBatteryLatestDate)) {
                    if (!ValidateDate::checkDateFormat($data->changeBatteryLatestDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid batteryId'
                        ));
                        return;
                    }
                }

                if (!isset($data->createBy) || empty($data->createBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckColorId = isset($data->truckColorId) ? trim($data->truckColorId) : null;
                $truckModelCode = isset($data->truckModelCode) ? trim($data->truckModelCode) : null;
                $truckTypeId = isset($data->truckTypeId) ? trim($data->truckTypeId) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $truckEngineNumber = isset($data->truckEngineNumber) ? trim($data->truckEngineNumber) : null;
                $truckBodyNumber = isset($data->truckBodyNumber) ? trim($data->truckBodyNumber) : null;
                $power = isset($data->power) ? trim($data->power) : null;
                $registrationDate = isset($data->registrationDate) ? trim($data->registrationDate) : null;
                $registrationExpiredDate = isset($data->registrationExpiredDate) ? trim($data->registrationExpiredDate) : null;
                $technicRenewalDate = isset($data->technicRenewalDate) ? trim($data->technicRenewalDate) : null;
                $technicExpiryDate = isset($data->technicExpiryDate) ? trim($data->technicExpiryDate) : null;
                $tungsitRenewalDate = isset($data->tungsitRenewalDate) ? trim($data->tungsitRenewalDate) : null;
                $tungsitExpiryDate = isset($data->tungsitExpiryDate) ? trim($data->tungsitExpiryDate) : null;
                $annualFeeReceipt = isset($data->annualFeeReceipt) ? trim($data->annualFeeReceipt) : null;
                $insuranceRenewalDate = isset($data->insuranceRenewalDate) ? trim($data->insuranceRenewalDate) : null;
                $insuranceExpiryDate = isset($data->insuranceExpiryDate) ? trim($data->insuranceExpiryDate) : null;
                $driverLicense = isset($data->driverLicense) ? trim($data->driverLicense) : null;
                $kmChangeEngineOilNextTime = isset($data->kmChangeEngineOilNextTime) ? trim($data->kmChangeEngineOilNextTime) : null;
                $kmChangeGearOilNextTime = isset($data->kmChangeGearOilNextTime) ? trim($data->kmChangeGearOilNextTime) : null;
                $batteryId = isset($data->batteryId) ? trim($data->batteryId) : null;
                $changeBatteryLatestDate = isset($data->changeBatteryLatestDate) ? trim($data->changeBatteryLatestDate) : null;

                $fireExtinguisher = isset($data->fireExtinguisher) ? trim($data->fireExtinguisher) : null;
                $frontCamera = isset($data->frontCamera) ? trim($data->frontCamera) : null;
                $tools = isset($data->tools) ? trim($data->tools) : null;
                $cardCloth = isset($data->cardCloth) ? trim($data->cardCloth) : null;
                $gps = isset($data->gps) ? trim($data->gps) : null;
                $verticalTraffic = isset($data->verticalTraffic) ? trim($data->verticalTraffic) : null;
                $straps = isset($data->straps) ? trim($data->straps) : null;
                $chains = isset($data->chains) ? trim($data->chains) : null;

                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_license_plate_is_exists = $TruckRegistrationHeadPart->checkLicensePlate($conn, $licensePlate);
                if ($check_license_plate_is_exists) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The license plate is already in the system, Please use another license plate'
                    ));
                    return;
                }

                $conn->beginTransaction();
                $result_create = $TruckRegistrationHeadPart->createTruckRegistrationHeadPart(
                        $conn,
                        $whichPart,
                        $licensePlate,
                        $truckBrandId,
                        $truckColorId,
                        $truckModelCode,
                        $truckTypeId,
                        $provinceId,
                        $truckEngineNumber,
                        $truckBodyNumber,
                        $power,
                        $batteryId,
                        $changeBatteryLatestDate,
                        $fireExtinguisher,
                        $frontCamera,
                        $tools,
                        $cardCloth,
                        $gps,
                        $verticalTraffic,
                        $straps,
                        $chains,
                        $registrationDate,
                        $registrationExpiredDate,
                        $technicRenewalDate,
                        $technicExpiryDate,
                        $driverLicense,
                        $annualFeeReceipt,
                        $tungsitRenewalDate,
                        $tungsitExpiryDate,
                        $insuranceRenewalDate,
                        $insuranceExpiryDate,
                        $kmChangeEngineOilNextTime,
                        $kmChangeGearOilNextTime,
                        $createBy
                );

                if ($result_create) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    $conn->commit();
                    return;
                }

                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::CREATE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                    'message' => 'success',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollback();
                return;
            } catch (Exception $ex) {
                $conn->rollback();
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART) {
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


                if (!isset($data->whichPart) || !is_numeric($data->whichPart)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || !is_numeric($data->truckBrandId) || empty($data->truckBrandId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckColorId) || !is_numeric($data->truckColorId) || empty($data->truckColorId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckColorId'
                    ));
                    return;
                }
                //have truck_model_code  can null
//                if (!isset($data->truckModelCode) || !is_numeric($data->truckModelCode) || empty($data->truckModelCode)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truck Model Code'
//                    ));
//                    return;
//                }

                if (!isset($data->truckTypeId) || !is_numeric($data->truckTypeId) || empty($data->truckTypeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeId'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || !is_numeric($data->provinceId) || empty($data->provinceId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

//                //truck engine number can be null
//                if (!isset($data->truckEngineNumber) || !is_numeric($data->truckEngineNumber) || empty($data->truckEngineNumber)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckEngineNumber'
//                    ));
//                    return;
//                }
//                //truck body number can be null
//                if (!isset($data->truckBodyNumber) || !is_numeric($data->truckBodyNumber) || empty($data->truckBodyNumber)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckEngineNumber'
//                    ));
//                    return;
//                }

                if (isset($data->power)) {
                    if (!is_numeric($data->power)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid power'
                        ));
                        return;
                    }
                }

                //long tha bien
                if (isset($data->registrationDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationDate'
                        ));
                        return;
                    }
                }

                //long tha bien mot a yu
                if (isset($data->registrationExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registration ExpiredDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicExpiryDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitExpiryDate'
                        ));
                        return;
                    }
                }
                //annual_fee_receipt kha tham niem thang  can null
//                if (!isset($data->annualFeeReceipt) || !is_numeric($data->annualFeeReceipt) || empty($data->annualFeeReceipt)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid annual Fee Receipt'
//                    ));
//                    return;
//                }
                //khao pa kan phai
                if (isset($data->insuranceRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceRenewalDate'
                        ));
                        return;
                    }
                }

                //pa kan phai mot a yu
                if (isset($data->insuranceExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceExpiryDate'
                        ));
                        return;
                    }
                }

                //driver License can null
//                if (!isset($data->driverLicense) || !is_numeric($data->driverLicense) || empty($data->driverLicense)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid driver License'
//                    ));
//                    return;
//                }

                if (isset($data->kmChangeEngineOilNextTime)) {
                    if (!is_numeric($data->kmChangeEngineOilNextTime)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid kmChangeEngineOilNextTime'
                        ));
                        return;
                    }
                }

                if (isset($data->kmChangeGearOilNextTime)) {
                    if (!is_numeric($data->kmChangeGearOilNextTime)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid kmChangeGearOilNextTime'
                        ));
                        return;
                    }
                }

                if (!isset($data->batteryId) || !is_numeric($data->batteryId) || empty($data->batteryId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryId'
                    ));
                    return;
                }

                if (isset($data->changeBatteryLatestDate)) {
                    if (!ValidateDate::checkDateFormat($data->changeBatteryLatestDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid batteryId'
                        ));
                        return;
                    }
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckColorId = isset($data->truckColorId) ? trim($data->truckColorId) : null;
                $truckModelCode = isset($data->truckModelCode) ? trim($data->truckModelCode) : null;
                $truckTypeId = isset($data->truckTypeId) ? trim($data->truckTypeId) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $truckEngineNumber = isset($data->truckEngineNumber) ? trim($data->truckEngineNumber) : null;
                $truckBodyNumber = isset($data->truckBodyNumber) ? trim($data->truckBodyNumber) : null;
                $power = isset($data->power) ? trim($data->power) : null;
                $registrationDate = isset($data->registrationDate) ? trim($data->registrationDate) : null;
                $registrationExpiredDate = isset($data->registrationExpiredDate) ? trim($data->registrationExpiredDate) : null;
                $technicRenewalDate = isset($data->technicRenewalDate) ? trim($data->technicRenewalDate) : null;
                $technicExpiryDate = isset($data->technicExpiryDate) ? trim($data->technicExpiryDate) : null;
                $tungsitRenewalDate = isset($data->tungsitRenewalDate) ? trim($data->tungsitRenewalDate) : null;
                $tungsitExpiryDate = isset($data->tungsitExpiryDate) ? trim($data->tungsitExpiryDate) : null;
                $annualFeeReceipt = isset($data->annualFeeReceipt) ? trim($data->annualFeeReceipt) : null;
                $insuranceRenewalDate = isset($data->insuranceRenewalDate) ? trim($data->insuranceRenewalDate) : null;
                $insuranceExpiryDate = isset($data->insuranceExpiryDate) ? trim($data->insuranceExpiryDate) : null;
                $driverLicense = isset($data->driverLicense) ? trim($data->driverLicense) : null;
                $kmChangeEngineOilNextTime = isset($data->kmChangeEngineOilNextTime) ? trim($data->kmChangeEngineOilNextTime) : null;
                $kmChangeGearOilNextTime = isset($data->kmChangeGearOilNextTime) ? trim($data->kmChangeGearOilNextTime) : null;
                $batteryId = isset($data->batteryId) ? trim($data->batteryId) : null;
                $changeBatteryLatestDate = isset($data->changeBatteryLatestDate) ? trim($data->changeBatteryLatestDate) : null;

                $fireExtinguisher = isset($data->fireExtinguisher) ? trim($data->fireExtinguisher) : null;
                $frontCamera = isset($data->frontCamera) ? trim($data->frontCamera) : null;
                $tools = isset($data->tools) ? trim($data->tools) : null;
                $cardCloth = isset($data->cardCloth) ? trim($data->cardCloth) : null;
                $gps = isset($data->gps) ? trim($data->gps) : null;
                $verticalTraffic = isset($data->verticalTraffic) ? trim($data->verticalTraffic) : null;
                $straps = isset($data->straps) ? trim($data->straps) : null;
                $chains = isset($data->chains) ? trim($data->chains) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_license_plate_is_exists = $TruckRegistrationHeadPart->checkLicensePlate($conn, $licensePlate);
                $check_license_plate_is_exists_on_change_oil = $TruckRegistrationHeadPart->checkLicensePlateOnChangeOil($conn, $licensePlate);
                if (!$check_license_plate_is_exists || !$check_license_plate_is_exists_on_change_oil) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                        'message' => 'The license plate is not found in the system, Please use another license plate'
                    ));
                    return;
                }
                $conn->beginTransaction();
                $update_result = $TruckRegistrationHeadPart->updateTruckRegistrationHeadPartByLicensePlate(
                        $conn,
                        $whichPart,
                        $licensePlate,
                        $truckBrandId,
                        $truckColorId,
                        $truckModelCode,
                        $truckTypeId,
                        $provinceId,
                        $truckEngineNumber,
                        $truckBodyNumber,
                        $power,
                        $batteryId,
                        $changeBatteryLatestDate,
                        $fireExtinguisher,
                        $frontCamera,
                        $tools,
                        $cardCloth,
                        $gps,
                        $verticalTraffic,
                        $straps,
                        $chains,
                        $registrationDate,
                        $registrationExpiredDate,
                        $technicRenewalDate,
                        $technicExpiryDate,
                        $driverLicense,
                        $annualFeeReceipt,
                        $tungsitRenewalDate,
                        $tungsitExpiryDate,
                        $insuranceRenewalDate,
                        $insuranceExpiryDate,
                        $kmChangeEngineOilNextTime,
                        $kmChangeGearOilNextTime,
                        $updateBy
                );

                if ($update_result) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    $conn->commit();
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollback();
                return;
            } catch (Exception $ex) {
                $conn->rollback();
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TRUCK_REGISTRATION_HEAD_PART:
            try {
                if (!isset($data->truckRegistrationBackPart) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_REGISTRATION_HEAD_PART) {
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


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                    'message' => 'license plate is not found in the system'
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
