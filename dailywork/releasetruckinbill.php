<?php

require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$ReleaseTruckInBill = new ReleaseTruckInBillModel();

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
        case ActionCode::GET_ALL_RELEASE_TRUCK_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_RELEASE_TRUCK_IN_BILL) {
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

                $getAllReleaseTruckInBill = $ReleaseTruckInBill->getAllReleaseTruckInBill($conn);
                $num_row = $getAllReleaseTruckInBill->rowCount();
                $getAllReleaseTruckInBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllReleaseTruckInBill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "billReleaseTruckInNo" => $bill_release_truck_in_no,
                            "goDate" => $go_date,
                            "backDate" => $back_date,
                            "licensePlate" => $license_plate,
                            "licensePlateBackPart" => $license_plate_back_part,
                            "driverFirst" => $driver_first,
                            "driverNameFirst" => $driver_name_first,
                            "driverTelFirst" => $driver_tel_first,
                            "driverSec" => $driver_second,
                            "driverNameSec" => $driver_name_sec,
                            "driverTelSec" => $driver_tel_sec,
                            "dateShipping" => $date_shipping,
                            "storeId" => $store_code,
                            "storeName" => $store_name,
                            "product" => $product_code,
                            "productName" => $product_name,
                            "destination" => $destination,
                            "destinationName" => $destination_name,
                            "gas" => $gas,
                            "gasPrice" => $gas_price,
                            "gasTotalPrice" => $gas_total_price,
                            "totalAllowance" => $total_allowance,
                            "payFirst" => $pay_first,
                            "moneyLeft" => $money_left,
                            "costFeeKip" => $cost_fee_kip,
                            "costFeeKipLeft" => $cost_fee_kip_left,
                            "costLaoCheckPointOverTimeKip" => $cost_lao_check_point_overtime_kip,
                            "costLaoCheckPointOverTimeKipLeft" => $cost_lao_check_point_overtime_kip_left,
                            "costTruckCrossingBridgeKip" => $cost_truck_crossing_bridge_kip,
                            "costTruckCrossingBridgeKipLeft" => $cost_truck_crossing_bridge_kip_left,
                            "costDisinfectantFeeKip" => $cost_disinfectant_fee_kip,
                            "costDisinfectantFeeKipLeft" => $cost_disinfectant_fee_kip_left,
                            "costReportPassportKip" => $cost_report_passport_kip,
                            "costReportPassportKipLeft" => $cost_report_passport_kip_left,
                            "costPoliceKip" => $cost_police_kip,
                            "costPoliceKipLeft" => $cost_police_kip_left,
                            "costReportTireKip" => $cost_repair_kip,
                            "costReportTireKipLeft" => $cost_repair_kip_left,
                            "otherKip" => $other_kip,
                            "otherKipLeft" => $other_kip_left,
                            "totalCostKip" => $total_cost_kip,
                            "totalCostKipLeft" => $total_cost_kip_left,
                            "costFeeBath" => $cost_fee_bath,
                            "costFeeBathLeft" => $cost_fee_bath_left,
                            "costLaoCheckPointOverTimeBath" => $cost_check_point_overtime_bath,
                            "costLaoCheckPointOverTimeBathLeft" => $cost_check_point_overtime_bath_left,
                            "costTruckCrossingBridgeBath" => $cost_truck_crossing_bridge_bath,
                            "costTruckCrossingBridgeBathLeft" => $cost_truck_crossing_bridge_bath_left,
                            "costDisinfectantFeeBath" => $cost_disinfectant_fee_bath,
                            "costDisinfectantFeeBathLeft" => $cost_disinfectant_fee_bath_left,
                            "costReportPassportBath" => $cost_report_passport_bath,
                            "costReportPassportBathLeft" => $cost_report_passport_bath_left,
                            "costPoliceBath" => $cost_police_bath,
                            "costPoliceBathLeft" => $cost_police_bath_left,
                            "costReportTireBath" => $cost_repair_tire_bath,
                            "costReportTireBathLeft" => $cost_repair_tire_bath_left,
                            "otherBath" => $other_bath,
                            "otherBathLeft" => $other_bath_left,
                            "totalCostBath" => $total_cost_bath,
                            "totalCostBathLeft" => $total_cost_bath_left,
                            "kmGoes" => $km_goes,
                            "kmBack" => $km_back,
                            "distance" => $distance,
                            "changeOil" => $change_oil,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllReleaseTruckInBill_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllReleaseTruckInBill_arr['data'],
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
        case ActionCode::GET_RELEASE_TRUCK_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_RELEASE_TRUCK_IN_BILL) {
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

                if (trim(!isset($data->billReleaseTruckIn)) || trim($data->billReleaseTruckIn) == null || trim($data->billReleaseTruckIn) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid billReleaseTruckIn'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $billReleaseTruckIn = isset($data->billReleaseTruckIn) ? trim($data->billReleaseTruckIn) : null;

                $get_release_truck_in_bill = $ReleaseTruckInBill->getReleaseTruckInBill($conn, $billReleaseTruckIn);
                $num_row = $get_release_truck_in_bill->rowCount();
                $getReleaseTruckInBill_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_release_truck_in_bill->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "billReleaseTruckInNo" => $bill_release_truck_in_no,
                            "goDate" => $go_date,
                            "backDate" => $back_date,
                            "licensePlate" => $license_plate,
                            "licensePlateBackPart" => $license_plate_back_part,
                            "driverFirst" => $driver_first,
                            "driverNameFirst" => $driver_name_first,
                            "driverTelFirst" => $driver_tel_first,
                            "driverSec" => $driver_second,
                            "driverNameSec" => $driver_name_sec,
                            "driverTelSec" => $driver_tel_sec,
                            "dateShipping" => $date_shipping,
                            "storeId" => $store_code,
                            "storeName" => $store_name,
                            "product" => $product_code,
                            "productName" => $product_name,
                            "destination" => $destination,
                            "destinationName" => $destination_name,
                            "gas" => $gas,
                            "gasPrice" => $gas_price,
                            "gasTotalPrice" => $gas_total_price,
                            "totalAllowance" => $total_allowance,
                            "payFirst" => $pay_first,
                            "moneyLeft" => $money_left,
                            "costFeeKip" => $cost_fee_kip,
                            "costFeeKipLeft" => $cost_fee_kip_left,
                            "costLaoCheckPointOverTimeKip" => $cost_lao_check_point_overtime_kip,
                            "costLaoCheckPointOverTimeKipLeft" => $cost_lao_check_point_overtime_kip_left,
                            "costTruckCrossingBridgeKip" => $cost_truck_crossing_bridge_kip,
                            "costTruckCrossingBridgeKipLeft" => $cost_truck_crossing_bridge_kip_left,
                            "costDisinfectantFeeKip" => $cost_disinfectant_fee_kip,
                            "costDisinfectantFeeKipLeft" => $cost_disinfectant_fee_kip_left,
                            "costReportPassportKip" => $cost_report_passport_kip,
                            "costReportPassportKipLeft" => $cost_report_passport_kip_left,
                            "costPoliceKip" => $cost_police_kip,
                            "costPoliceKipLeft" => $cost_police_kip_left,
                            "costRepairTireKip" => $cost_repair_kip,
                            "costRepairTireKipLeft" => $cost_repair_kip_left,
                            "otherKip" => $other_kip,
                            "otherKipLeft" => $other_kip_left,
                            "totalCostKip" => $total_cost_kip,
                            "totalCostKipLeft" => $total_cost_kip_left,
                            "costFeeBath" => $cost_fee_bath,
                            "costFeeBathLeft" => $cost_fee_bath_left,
                            "costLaoCheckPointOverTimeBath" => $cost_check_point_overtime_bath,
                            "costLaoCheckPointOverTimeBathLeft" => $cost_check_point_overtime_bath_left,
                            "costTruckCrossingBridgeBath" => $cost_truck_crossing_bridge_bath,
                            "costTruckCrossingBridgeBathLeft" => $cost_truck_crossing_bridge_bath_left,
                            "costDisinfectantFeeBath" => $cost_disinfectant_fee_bath,
                            "costDisinfectantFeeBathLeft" => $cost_disinfectant_fee_bath_left,
                            "costReportPassportBath" => $cost_report_passport_bath,
                            "costReportPassportBathLeft" => $cost_report_passport_bath_left,
                            "costPoliceBath" => $cost_police_bath,
                            "costPoliceBathLeft" => $cost_police_bath_left,
                            "costRepairTireBath" => $cost_repair_tire_bath,
                            "costRepairTireBathLeft" => $cost_repair_tire_bath_left,
                            "otherBath" => $other_bath,
                            "otherBathLeft" => $other_bath_left,
                            "totalCostBath" => $total_cost_bath,
                            "totalCostBathLeft" => $total_cost_bath_left,
                            "kmGoes" => $km_goes,
                            "kmBack" => $km_back,
                            "distance" => $distance,
                            "changeOil" => $change_oil,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getReleaseTruckInBill_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getReleaseTruckInBill_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_RELEASE_TRUCK_IN_BILL,
                    'message' => 'Not Found Data',
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
        case ActionCode::CREATE_RELEASE_TRUCK_IN_BILL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_RELEASE_TRUCK_IN_BILL) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid actionNodeId'
                    ));
                    return;
                }

                if (!isset($data->billReleaseTruckInNo) || empty($data->billReleaseTruckInNo)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid bill release truck in no'
                    ));
                    return;
                }

                if (isset($data->goDate)) {
                    if (!ValidateDate::checkDateFormat($data->goDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid goDate'
                        ));
                        return;
                    }
                }

                if (isset($data->backDate)) {
                    if (!ValidateDate::checkDateFormat($data->backDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid backDate'
                        ));
                        return;
                    }
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid license plate.'
                    ));
                    return;
                }
                //license_plate_back_part can null 
//                if (!isset($data->license_plate_back_part) || empty($data->license_plate_back_part)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'invalid license plate back part.'
//                    ));
//                    return;
//                }

                if (!isset($data->driverFirst) || empty($data->driverFirst)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid driver first.'
                    ));
                    return;
                }

                //driverSecond can null
                if (!isset($data->storeId) || empty($data->storeId) || !is_numeric($data->storeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid store id.'
                    ));
                    return;
                }

                if (isset($data->dateShipping)) {
                    if (!ValidateDate::checkDateFormat($data->dateShipping)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid date Shipping'
                        ));
                        return;
                    }
                }

                if (trim(!isset($data->product)) || trim($data->product) == null || trim($data->product) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product'
                    ));
                    return;
                }

                if (trim(!isset($data->destination)) || trim($data->destination) == null || trim($data->destination) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid destination'
                    ));
                    return;
                }

                if (trim(!isset($data->gas)) || trim($data->gas) == null || trim($data->gas) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid gas'
                    ));
                    return;
                }

                if (trim(!isset($data->gasPrice)) || trim($data->gasPrice) == null || trim($data->gasPrice) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid gas Price'
                    ));
                    return;
                }

                if (trim(!isset($data->gasTotalPrice)) || trim($data->gasTotalPrice) == null || trim($data->gasTotalPrice) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid gas Total Price'
                    ));
                    return;
                }

                if (trim(!isset($data->totalAllowance)) || trim($data->totalAllowance) == null || trim($data->totalAllowance) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid total Allowance'
                    ));
                    return;
                }

                if (trim(!isset($data->payFirst)) || trim($data->payFirst) == null || trim($data->payFirst) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid pay First'
                    ));
                    return;
                }

                if (trim(!isset($data->moneyLeft)) || trim($data->moneyLeft) == null || trim($data->moneyLeft) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid money Left'
                    ));
                    return;
                }

                if (trim(!isset($data->kmGoes)) || trim($data->kmGoes) == null || trim($data->kmGoes) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid km Goes'
                    ));
                    return;
                }

                if (trim(!isset($data->kmBack)) || trim($data->kmBack) == null || trim($data->kmBack) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid km back'
                    ));
                    return;
                }

                if (trim(!isset($data->distance)) || trim($data->distance) == null || trim($data->distance) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid distance'
                    ));
                    return;
                }

                if (trim(!isset($data->changeOil)) || trim($data->changeOil) == null || trim($data->changeOil) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid change Oil'
                    ));
                    return;
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

                $billReleaseTruckInNo = isset($data->billReleaseTruckInNo) ? trim($data->billReleaseTruckInNo) : null;
                $goDate = isset($data->goDate) ? trim($data->goDate) : null;
                $backDate = isset($data->backDate) ? trim($data->backDate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $licensePlateBackPart = isset($data->licensePlateBackPart) ? trim($data->licensePlateBackPart) : null;
                $driverFirst = isset($data->driverFirst) ? trim($data->driverFirst) : null;
                $driverSec = isset($data->driverSec) ? trim($data->driverSec) : null;
                $storeId = isset($data->storeId) ? trim($data->storeId) : null;
                $dateShipping = isset($data->dateShipping) ? trim($data->dateShipping) : null;
                $product = isset($data->product) ? trim($data->product) : null;
                $destination = isset($data->destination) ? trim($data->destination) : null;
                $gas = isset($data->gas) ? trim($data->gas) : null;
                $gasPrice = isset($data->gasPrice) ? trim($data->gasPrice) : null;
                $gasTotalPrice = isset($data->gasTotalPrice) ? trim($data->gasTotalPrice) : null;
                $totalAllowance = isset($data->totalAllowance) ? trim($data->totalAllowance) : null;
                $payFirst = isset($data->payFirst) ? trim($data->payFirst) : null;
                $moneyLeft = isset($data->moneyLeft) ? trim($data->moneyLeft) : null;

                $costFeeKip = isset($data->costFeeKip) ? trim($data->costFeeKip) : null;
                $costFeeKipLeft = isset($data->costFeeKipLeft) ? trim($data->costFeeKipLeft) : null;
                $costLaoCheckPointOverTimeKip = isset($data->costLaoCheckPointOverTimeKip) ? trim($data->costLaoCheckPointOverTimeKip) : null;
                $costLaoCheckPointOverTimeKipLeft = isset($data->costLaoCheckPointOverTimeKipLeft) ? trim($data->costLaoCheckPointOverTimeKipLeft) : null;
                $costTruckCrossingBridgeKip = isset($data->costTruckCrossingBridgeKip) ? trim($data->costTruckCrossingBridgeKip) : null;
                $costTruckCrossingBridgeKipLeft = isset($data->costTruckCrossingBridgeKipLeft) ? trim($data->costTruckCrossingBridgeKipLeft) : null;
                $costDisinfectantFeeKip = isset($data->costDisinfectantFeeKip) ? trim($data->costDisinfectantFeeKip) : null;
                $costDisinfectantFeeKipLeft = isset($data->costDisinfectantFeeKipLeft) ? trim($data->costDisinfectantFeeKipLeft) : null;
                $costReportPassportKip = isset($data->costReportPassportKip) ? trim($data->costReportPassportKip) : null;
                $costReportPassportKipLeft = isset($data->costReportPassportKipLeft) ? trim($data->costReportPassportKipLeft) : null;
                $costPoliceKip = isset($data->costPoliceKip) ? trim($data->costPoliceKip) : null;
                $costPoliceKipLeft = isset($data->costPoliceKipLeft) ? trim($data->costPoliceKipLeft) : null;
                $costRepairTireKip = isset($data->costRepairTireKip) ? trim($data->costRepairTireKip) : null;
                $costRepairTireKipLeft = isset($data->costRepairTireKipLeft) ? trim($data->costRepairTireKipLeft) : null;
                $otherKip = isset($data->otherKip) ? trim($data->otherKip) : null;
                $otherKipLeft = isset($data->otherKipLeft) ? trim($data->otherKipLeft) : null;
                $totalCostKip = isset($data->totalCostKip) ? trim($data->totalCostKip) : null;
                $totalCostKipLeft = isset($data->totalCostKipLeft) ? trim($data->totalCostKipLeft) : null;
                $costFeeBath = isset($data->costFeeBath) ? trim($data->costFeeBath) : null;
                $costFeeBathLeft = isset($data->costFeeBathLeft) ? trim($data->costFeeBathLeft) : null;
                $costLaoCheckPointOverTimeBath = isset($data->costLaoCheckPointOverTimeBath) ? trim($data->costLaoCheckPointOverTimeBath) : null;
                $costLaoCheckPointOverTimeBathLeft = isset($data->costLaoCheckPointOverTimeBathLeft) ? trim($data->costLaoCheckPointOverTimeBathLeft) : null;
                $costTruckCrossingBridgeBath = isset($data->costTruckCrossingBridgeBath) ? trim($data->costTruckCrossingBridgeBath) : null;
                $costTruckCrossingBridgeBathLeft = isset($data->costTruckCrossingBridgeBathLeft) ? trim($data->costTruckCrossingBridgeBathLeft) : null;
                $costDisinfectantFeeBath = isset($data->costDisinfectantFeeBath) ? trim($data->costDisinfectantFeeBath) : null;
                $costDisinfectantFeeBathLeft = isset($data->costDisinfectantFeeBathLeft) ? trim($data->costDisinfectantFeeBathLeft) : null;
                $costReportPassportBath = isset($data->costReportPassportBath) ? trim($data->costReportPassportBath) : null;
                $costReportPassportBathLeft = isset($data->costReportPassportBathLeft) ? trim($data->costReportPassportBathLeft) : null;
                $costPoliceBath = isset($data->costPoliceBath) ? trim($data->costPoliceBath) : null;
                $costPoliceBathLeft = isset($data->costPoliceBathLeft) ? trim($data->costPoliceBathLeft) : null;
                $costRepairTireBath = isset($data->costRepairTireBath) ? trim($data->costRepairTireBath) : null;
                $costRepairTireBathLeft = isset($data->costRepairTireBathLeft) ? trim($data->costRepairTireBathLeft) : null;
                $otherBath = isset($data->otherBath) ? trim($data->otherBath) : null;
                $otherBathLeft = isset($data->otherBathLeft) ? trim($data->otherBathLeft) : null;
                $totalCostBath = isset($data->totalCostBath) ? trim($data->totalCostBath) : null;
                $totalCostBathLeft = isset($data->totalCostBathLeft) ? trim($data->totalCostBathLeft) : null;

                $kmGoes = isset($data->kmGoes) ? trim($data->kmGoes) : null;
                $kmBack = isset($data->kmBack) ? trim($data->kmBack) : null;
                $distance = isset($data->distance) ? trim($data->distance) : null;
                $changeOil = isset($data->changeOil) ? trim($data->changeOil) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_bill_release_truck_in_no = $ReleaseTruckInBill->checkBillReleaseTruckInNo($conn, $billReleaseTruckInNo);
                if ($check_bill_release_truck_in_no) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_RELEASE_TRUCK_IN_BILL_FAIL,
                        'message' => 'bill release truck in already has in the system, please use other bill release truck in',
                    ));
                    return;
                }
                $result = $ReleaseTruckInBill->createNewReleaseTruckInBill(
                        $conn, $billReleaseTruckInNo, $goDate, $backDate, $licensePlate, $licensePlateBackPart, $driverFirst, $driverSec, $dateShipping, $storeId, $product, $destination, $gas, $gasPrice, $gasTotalPrice, $totalAllowance, $payFirst, $moneyLeft, $costFeeKip, $costFeeKipLeft, $costLaoCheckPointOverTimeKip, $costLaoCheckPointOverTimeKipLeft, $costTruckCrossingBridgeKip, $costTruckCrossingBridgeKipLeft, $costDisinfectantFeeKip, $costDisinfectantFeeKipLeft, $costReportPassportKip, $costReportPassportKipLeft, $costPoliceKip, $costPoliceKipLeft, $costRepairTireKip, $costRepairTireKipLeft, $otherKip, $otherKipLeft, $totalCostKip, $totalCostKipLeft, $costFeeBath, $costFeeBathLeft, $costLaoCheckPointOverTimeBath, $costLaoCheckPointOverTimeBathLeft, $costTruckCrossingBridgeBath, $costTruckCrossingBridgeBathLeft, $costDisinfectantFeeBath, $costDisinfectantFeeBathLeft, $costReportPassportBath, $costReportPassportBathLeft, $costPoliceBath, $costPoliceBathLeft, $costRepairTireBath, $costRepairTireBathLeft, $otherBath, $otherBathLeft, $totalCostBath, $totalCostBathLeft, $kmGoes, $kmBack, $distance, $changeOil, $createBy
                );

                if ($result) {
                    $res = array(
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
                        'responseCode' => ErrorCode::CREATE_RELEASE_TRUCK_IN_BILL_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );

                    http_response_code(200);
                    echo json_encode($res);
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

                if (isset($data->goDate)) {
                    if (!ValidateDate::checkDateFormat($data->goDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid goDate'
                        ));
                        return;
                    }
                }

                if (isset($data->backDate)) {
                    if (!ValidateDate::checkDateFormat($data->backDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid backDate'
                        ));
                        return;
                    }
                }

                if (isset($data->dateShipping)) {
                    if (!ValidateDate::checkDateFormat($data->dateShipping)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid dateShipping'
                        ));
                        return;
                    }
                }

                if (!isset($data->storeId) || empty($data->storeId) || !is_numeric($data->storeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid store id'
                    ));
                    return;
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
                $billReleaseTruckInNo = isset($data->billReleaseTruckInNo) ? trim($data->billReleaseTruckInNo) : null;
                $goDate = isset($data->goDate) ? trim($data->goDate) : null;
                $backDate = isset($data->backDate) ? trim($data->backDate) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $licensePlateBackPart = isset($data->licensePlateBackPart) ? trim($data->licensePlateBackPart) : null;
                $driverFirst = isset($data->driverFirst) ? trim($data->driverFirst) : null;
                $driverSec = isset($data->driverSec) ? trim($data->driverSec) : null;
                $dateShipping = isset($data->dateShipping) ? trim($data->dateShipping) : null;
                $storeId = isset($data->storeId) ? trim($data->storeId) : null;
                $product = isset($data->product) ? trim($data->product) : null;
                // $origin = isset($data->origin) ? trim($data->origin) : null;
                $destination = isset($data->destination) ? trim($data->destination) : null;
                $gas = isset($data->gas) ? trim($data->gas) : null;
                $gasPrice = isset($data->gasPrice) ? trim($data->gasPrice) : null;
                $gasTotalPrice = isset($data->gasTotalPrice) ? trim($data->gasTotalPrice) : null;
                $totalAllowance = isset($data->totalAllowance) ? trim($data->totalAllowance) : null;
                $payFirst = isset($data->payFirst) ? trim($data->payFirst) : null;
                $moneyLeft = isset($data->moneyLeft) ? trim($data->moneyLeft) : null;
                $costFeeKip = isset($data->costFeeKip) ? trim($data->costFeeKip) : null;
                $costFeeKipLeft = isset($data->costFeeKipLeft) ? trim($data->costFeeKipLeft) : null;
                $costLaoCheckPointOverTimeKip = isset($data->costLaoCheckPointOverTimeKip) ? trim($data->costLaoCheckPointOverTimeKip) : null;
                $costLaoCheckPointOverTimeKipLeft = isset($data->costLaoCheckPointOverTimeKipLeft) ? trim($data->costLaoCheckPointOverTimeKipLeft) : null;
                $costTruckCrossingBridgeKip = isset($data->costTruckCrossingBridgeKip) ? trim($data->costTruckCrossingBridgeKip) : null;
                $costTruckCrossingBridgeKipLeft = isset($data->costTruckCrossingBridgeKipLeft) ? trim($data->costTruckCrossingBridgeKipLeft) : null;
                $costDisinfectantFeeKip = isset($data->costDisinfectantFeeKip) ? trim($data->costDisinfectantFeeKip) : null;
                $costDisinfectantFeeKipLeft = isset($data->costDisinfectantFeeKipLeft) ? trim($data->costDisinfectantFeeKipLeft) : null;
                $costReportPassportKip = isset($data->costReportPassportKip) ? trim($data->costReportPassportKip) : null;
                $costReportPassportKipLeft = isset($data->costReportPassportKipLeft) ? trim($data->costReportPassportKipLeft) : null;
                $costPoliceKip = isset($data->costPoliceKip) ? trim($data->costPoliceKip) : null;
                $costPoliceKipLeft = isset($data->costPoliceKipLeft) ? trim($data->costPoliceKipLeft) : null;
                $costRepairTireKip = isset($data->costRepairTireKip) ? trim($data->costRepairTireKip) : null;
                $costRepairTireKipLeft = isset($data->costRepairTireKipLeft) ? trim($data->costRepairTireKipLeft) : null;
                $otherKip = isset($data->otherKip) ? trim($data->otherKip) : null;
                $otherKipLeft = isset($data->otherKipLeft) ? trim($data->otherKipLeft) : null;
                $totalCostKip = isset($data->totalCostKip) ? trim($data->totalCostKip) : null;
                $totalCostKipLeft = isset($data->totalCostKipLeft) ? trim($data->totalCostKipLeft) : null;
                $costFeeBath = isset($data->costFeeBath) ? trim($data->costFeeBath) : null;
                $costFeeBathLeft = isset($data->costFeeBathLeft) ? trim($data->costFeeBathLeft) : null;
                $costLaoCheckPointOverTimeBath = isset($data->costLaoCheckPointOverTimeBath) ? trim($data->costLaoCheckPointOverTimeBath) : null;
                $costLaoCheckPointOverTimeBathLeft = isset($data->costLaoCheckPointOverTimeBathLeft) ? trim($data->costLaoCheckPointOverTimeBathLeft) : null;
                $costTruckCrossingBridgeBath = isset($data->costTruckCrossingBridgeBath) ? trim($data->costTruckCrossingBridgeBath) : null;
                $costTruckCrossingBridgeBathLeft = isset($data->costTruckCrossingBridgeBathLeft) ? trim($data->costTruckCrossingBridgeBathLeft) : null;
                $costDisinfectantFeeBath = isset($data->costDisinfectantFeeBath) ? trim($data->costDisinfectantFeeBath) : null;
                $costDisinfectantFeeBathLeft = isset($data->costDisinfectantFeeBathLeft) ? trim($data->costDisinfectantFeeBathLeft) : null;
                $costReportPassportBath = isset($data->costReportPassportBath) ? trim($data->costReportPassportBath) : null;
                $costReportPassportBathLeft = isset($data->costReportPassportBathLeft) ? trim($data->costReportPassportBathLeft) : null;
                $costPoliceBath = isset($data->costPoliceBath) ? trim($data->costPoliceBath) : null;
                $costPoliceBathLeft = isset($data->costPoliceBathLeft) ? trim($data->costPoliceBathLeft) : null;
                $costRepairTireBath = isset($data->costRepairTireBath) ? trim($data->costRepairTireBath) : null;
                $costRepairTireBathLeft = isset($data->costRepairTireBathLeft) ? trim($data->costRepairTireBathLeft) : null;
                $otherBath = isset($data->otherBath) ? trim($data->otherBath) : null;
                $otherBathLeft = isset($data->otherBathLeft) ? trim($data->otherBathLeft) : null;
                $totalCostBath = isset($data->totalCostBath) ? trim($data->totalCostBath) : null;
                $totalCostBathLeft = isset($data->totalCostBathLeft) ? trim($data->totalCostBathLeft) : null;
                $kmGoes = isset($data->kmGoes) ? trim($data->kmGoes) : null;
                $kmBack = isset($data->kmBack) ? trim($data->kmBack) : null;
                $distance = isset($data->distance) ? trim($data->distance) : null;
                $changeOil = isset($data->changeOil) ? trim($data->changeOil) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_bill_release_truck_in_no = $ReleaseTruckInBill->checkBillReleaseTruckInNo($conn, $billReleaseTruckInNo);
                if (!$check_bill_release_truck_in_no) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_RELEASE_TRUCK_IN_BILL,
                        'message' => 'not found data in the system.',
                    ));
                    return;
                }
                $result = $ReleaseTruckInBill->updateReleaseTruckInBill(
                        $conn, $billReleaseTruckInNo, $goDate, $backDate, $licensePlate, $licensePlaceBackBart, $driverFirst, $driverSec, $dateShipping, $storeId, $product, $destination, $gas, $gasPrice, $gasTotalPrice, $totalAllowance, $payFirst, $moneyLeft, $costFeeKip, $costFeeKipLeft, $costLaoCheckPointOverTimeKip, $costLaoCheckPointOverTimeKipLeft, $costTruckCrossingBridgeKip, $costTruckCrossingBridgeKipLeft, $costDisinfectantFeeKip, $costDisinfectantFeeKipLeft, $costReportPassportKip, $costReportPassportKipLeft, $costPoliceKip, $costPoliceKipLeft, $costRepairTireKip, $costRepairTireKipLeft, $otherKip, $otherKipLeft, $totalCostKip, $totalCostKipLeft, $costFeeBath, $costFeeBathLeft, $costLaoCheckPointOverTimeBath, $costLaoCheckPointOverTimeBathLeft, $costTruckCrossingBridgeBath, $costTruckCrossingBridgeBathLeft, $costDisinfectantFeeBath, $costDisinfectantFeeBathLeft, $costReportPassportBath, $costReportPassportBathLeft, $costPoliceBath, $costPoliceBathLeft, $costRepairTireBath, $costRepairTireBathLeft, $otherBath, $otherBathLeft, $totalCostBath, $totalCostBathLeft, $kmGoes, $kmBack, $distance, $changeOil, $updateBy
                );
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
                    'responseCode' => ErrorCode::UPDATE_RELEASE_TRUCK_IN_BILL_FAIL,
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
