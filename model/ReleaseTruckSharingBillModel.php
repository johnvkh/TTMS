<?php

class ReleaseTruckSharingBillModel
{

    function __construct()
    {
    }

    public function getAllReleaseTruckSharingBill($conn)
    {
        try {
            $sql = "SELECT 
            a.bill_release_truck_sharing_no,
            a.go_date,
            a.back_date,
            a.license_plate,
            a.license_plate_back_part,
            a.driver_first,
            (select concat(b.firstname,' ', b.lastname) from driver b where b.driver_code = a.driver_first) as driver_name_first,
            (select b.mobile_tel from driver b where b.driver_code = a.driver_first) as driver_tel_first,
            a.driver_second,
            (select concat(b.firstname,' ', b.lastname) from driver b where b.driver_code = a.driver_second) as driver_name_sec,
            (select b.mobile_tel from driver b where b.driver_code = a.driver_second) as driver_tel_sec,
            a.date_shipping,
            a.store_code, 
            (select e.fullname from store_name e where e.id = a.store_code) as store_name,
            a.product_code,
            (select c.product_name from transported_goods c where c.product_code = a.product_code) as product_name,
            a.destination,
            (select concat(d.start, ' - ', d.end) from set_way_allowance_transport_rate d where d.way_code = a.destination) as destination_name,
            a.gas,
            a.gas_price,
            a.gas_total_price,
            a.total_allowance,
            a.pay_first,
            a.money_left,
            a.cost_fee_kip,
            a.cost_fee_kip_left,
            a.cost_lao_check_point_overtime_kip,
            a.cost_lao_check_point_overtime_kip_left,
            a.cost_truck_crossing_bridge_kip,
            a.cost_truck_crossing_bridge_kip_left,
            a.cost_disinfectant_fee_kip,
            a.cost_disinfectant_fee_kip_left,
            a.cost_report_passport_kip,
            a.cost_report_passport_kip_left,
            a.cost_police_kip,
            a.cost_police_kip_left,
            a.cost_repair_kip,
            a.cost_repair_kip_left,
            a.other_kip,
            a.other_kip_left,
            a.total_cost_kip,
            a.total_cost_kip_left,
            a.cost_fee_bath,
            a.cost_fee_bath_left,
            a.cost_check_point_overtime_bath,
            a.cost_check_point_overtime_bath_left,
            a.cost_truck_crossing_bridge_bath,
            a.cost_truck_crossing_bridge_bath_left,
            a.cost_disinfectant_fee_bath,
            a.cost_disinfectant_fee_bath_left,
            a.cost_report_passport_bath,
            a.cost_report_passport_bath_left,
            a.cost_police_bath,
            a.cost_police_bath_left,
            a.cost_repair_tire_bath,
            a.cost_repair_tire_bath_left,
            a.other_bath,
            a.other_bath_left,
            a.total_cost_bath,
            a.total_cost_bath_left,
            a.km_goes,
            a.km_back,
            a.distance,
            a.change_oil,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM release_truck_sharing a order by a.id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getReleaseTruckSharingBill($conn, $billReleaseTruckSharingNo)
    {
        try {
            $sql = "SELECT 
            a.bill_release_truck_sharing_no,
            a.go_date,
            a.back_date,
            a.license_plate,
            a.license_plate_back_part,
            (select concat(b.firstname,' ', b.lastname) from driver b where b.driver_code = a.driver_first) as driver_name_first,
            (select b.mobile_tel from driver b where b.driver_code = a.driver_first) as driver_tel_first,
            a.driver_second,
            (select concat(b.firstname,' ', b.lastname) from driver b where b.driver_code = a.driver_second) as driver_name_sec,
            (select b.mobile_tel from driver b where b.driver_code = a.driver_second) as driver_tel_sec,
            a.date_shipping,
            a.store_code, 
            (select e.fullname from store_name e where e.id = a.store_code) as store_name,
            a.product_code,
            (select c.product_name from transported_goods c where c.product_code = a.product_code) as product_name,
            a.destination,
            (select concat(d.start, ' - ', d.end) from set_way_allowance_transport_rate d where d.way_code = a.destination) as destination_name,
            a.gas,
            a.gas_price,
            a.gas_total_price,
            a.total_allowance,
            a.pay_first,
            a.money_left,
            a.cost_fee_kip,
            a.cost_fee_kip_left,
            a.cost_lao_check_point_overtime_kip,
            a.cost_lao_check_point_overtime_kip_left,
            a.cost_truck_crossing_bridge_kip,
            a.cost_truck_crossing_bridge_kip_left,
            a.cost_disinfectant_fee_kip,
            a.cost_disinfectant_fee_kip_left,
            a.cost_report_passport_kip,
            a.cost_report_passport_kip_left,
            a.cost_police_kip,
            a.cost_police_kip_left,
            a.cost_repair_kip,
            a.cost_repair_kip_left,
            a.other_kip,
            a.other_kip_left,
            a.total_cost_kip,
            a.total_cost_kip_left,
            a.cost_fee_bath,
            a.cost_fee_bath_left,
            a.cost_check_point_overtime_bath,
            a.cost_check_point_overtime_bath_left,
            a.cost_truck_crossing_bridge_bath,
            a.cost_truck_crossing_bridge_bath_left,
            a.cost_disinfectant_fee_bath,
            a.cost_disinfectant_fee_bath_left,
            a.cost_report_passport_bath,
            a.cost_report_passport_bath_left,
            a.cost_police_bath,
            a.cost_police_bath_left,
            a.cost_repair_tire_bath,
            a.cost_repair_tire_bath_left,
            a.other_bath,
            a.other_bath_left,
            a.total_cost_bath,
            a.total_cost_bath_left,
            a.km_goes,
            a.km_back,
            a.distance,
            a.change_oil,
            a.create_by,
            a.create_date,
            a.update_by,
            a.update_date
            FROM release_truck_sharing a where a.bill_release_truck_sharing_no = :bill_release_truck_sharing_no";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':bill_release_truck_sharing_no', $billReleaseTruckSharingNo);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkBillReleaseTruckSharingNo($conn, $billReleaseTruckSharingNo)
    {
        try {
            $sql = "SELECT a.bill_release_truck_sharing_no FROM release_truck_sharing a WHERE a.bill_release_truck_sharing_no = :release_truck_sharing";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":release_truck_sharing", $billReleaseTruckSharingNo, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function createNewReleaseTruckSharingBill(
        $conn,
        $billReleaseTruckSharingNo,
        $goDate,
        $backDate,
        $licensePlate,
        $licensePlateBackPart,
        $driverFirst,
        $driverSec,
        $dateShipping,
        $storeCode,
        $product,
        // $origin,
        $destination,
        $gas,
        $gasPrice,
        $gasTotalPrice,
        $totalAllowance,
        $payFirst,
        $moneyLeft,
        $costFeeKip,
        $costFeeKipLeft,
        $costLaoCheckPointOverTimeKip,
        $costLaoCheckPointOverTimeKipLeft,
        $costTruckCrossingBridgeKip,
        $costTruckCrossingBridgeKipLeft,
        $costDisinfectantFeeKip,
        $costDisinfectantFeeKipLeft,
        $costReportPassportKip,
        $costReportPassportKipLeft,
        $costPoliceKip,
        $costPoliceKipLeft,
        $costRepairTireKip,
        $costRepairTireKipLeft,
        $otherKip,
        $otherKipLeft,
        $totalCostKip,
        $totalCostKipLeft,
        $costFeeBath,
        $costFeeBathLeft,
        $costLaoCheckPointOverTimeBath,
        $costLaoCheckPointOverTimeBathLeft,
        $costTruckCrossingBridgeBath,
        $costTruckCrossingBridgeBathLeft,
        $costDisinfectantFeeBath,
        $costDisinfectantFeeBathLeft,
        $costReportPassportBath,
        $costReportPassportBathLeft,
        $costPoliceBath,
        $costPoliceBathLeft,
        $costRepairTireBath,
        $costRepairTireBathLeft,
        $otherBath,
        $otherBathLeft,
        $totalCostBath,
        $totalCostBathLeft,
        $kmGoes,
        $kmBack,
        $distance,
        $changeOil,
        $createBy
    ) {
        $sql = "insert into release_truck_sharing ("
            . "bill_release_truck_sharing_no, go_date, back_date, license_plate, license_plate_back_part, driver_first, driver_second,"
            . "date_shipping, store_code, product_code,destination, gas, gas_price, gas_total_price, total_allowance, pay_first,"
            . "money_left, cost_fee_kip, cost_fee_kip_left, cost_lao_check_point_overtime_kip, cost_lao_check_point_overtime_kip_left, cost_truck_crossing_bridge_kip, cost_truck_crossing_bridge_kip_left,"
            . "cost_disinfectant_fee_kip, "
            . "cost_disinfectant_fee_kip_left, cost_report_passport_kip, cost_report_passport_kip_left, cost_police_kip, cost_police_kip_left, cost_repair_kip, cost_repair_kip_left, other_kip,"
            . "other_kip_left, total_cost_kip, total_cost_kip_left, cost_fee_bath, cost_fee_bath_left, cost_check_point_overtime_bath, cost_check_point_overtime_bath_left, cost_truck_crossing_bridge_bath,"
            . "cost_truck_crossing_bridge_bath_left, cost_disinfectant_fee_bath, cost_disinfectant_fee_bath_left, cost_report_passport_bath, cost_report_passport_bath_left, cost_police_bath, cost_police_bath_left,"
            . "cost_repair_tire_bath, cost_repair_tire_bath_left, other_bath, other_bath_left, total_cost_bath, total_cost_bath_left, km_goes, km_back, distance, change_oil, create_by, create_date)"
            . "values (:bill_release_truck_sharing_no, :go_date, :back_date, :license_plate, :license_plate_back_part, :driver_first, :driver_second,"
            . ":date_shipping, :store_code, :product_code, :destination, :gas, :gas_price, :gas_total_price, :total_allowance, :pay_first,"
            . ":money_left, :cost_fee_kip, :cost_fee_kip_left, :cost_lao_check_point_overtime_kip, :cost_lao_check_point_overtime_kip_left, :cost_truck_crossing_bridge_kip,"
            . ":cost_truck_crossing_bridge_kip_left, :cost_disinfectant_fee_kip, "
            . ":cost_disinfectant_fee_kip_left, :cost_report_passport_kip, :cost_report_passport_kip_left, :cost_police_kip, :cost_police_kip_left, :cost_repair_kip, :cost_repair_kip_left,"
            . ":other_kip, :other_kip_left, :total_cost_kip, :total_cost_kip_left, :costFeeBath, :total_cost_bath_left, :costLaoCheckPointOverTimeBath, :cost_check_point_overtime_bath_left, :costTruckCrossingBridgeBath,"
            . ":cost_truck_crossing_bridge_bath_left, :costDisinfectantFeeBath, :cost_disinfectant_fee_bath_left, :costReportPassportBath, :cost_report_passport_bath_left,"
            . ":costPoliceBath, :cost_police_bath_left, :costRepairTireBath, :cost_repair_tire_bath_left, :otherBath, :other_bath_left, :totalCostBath, :total_cost_bath_left, :km_goes, :km_back, :distance, :change_oil, :create_by, sysdate())";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_sharing_no", $billReleaseTruckSharingNo);
            $stmt->bindParam(":go_date", $goDate);
            $stmt->bindParam(":back_date", $backDate);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->bindParam(":license_plate_back_part", $licensePlateBackPart);
            $stmt->bindParam(":driver_first", $driverFirst);
            $stmt->bindParam(":driver_second", $driverSec);
            $stmt->bindParam(":date_shipping", $dateShipping);
            $stmt->bindParam(":store_code", $storeCode);
            $stmt->bindParam(":product_code", $product);
            // $stmt->bindParam(":origin", $origin);
            $stmt->bindParam(":destination", $destination);
            $stmt->bindParam(":gas", $gas);
            $stmt->bindParam(":gas_price", $gasPrice);
            $stmt->bindParam(":gas_total_price", $gasTotalPrice);
            $stmt->bindParam(":total_allowance", $totalAllowance);
            $stmt->bindParam(":pay_first", $payFirst);
            $stmt->bindParam(":money_left", $moneyLeft);
            $stmt->bindParam(":cost_fee_kip", $costFeeKip);
            $stmt->bindParam(":cost_fee_kip_left", $costFeeKipLeft);
            $stmt->bindParam(":cost_lao_check_point_overtime_kip", $costLaoCheckPointOverTimeKip);
            $stmt->bindParam(":cost_lao_check_point_overtime_kip_left", $costLaoCheckPointOverTimeKipLeft);
            $stmt->bindParam(":cost_truck_crossing_bridge_kip", $costTruckCrossingBridgeKip);
            $stmt->bindParam(":cost_truck_crossing_bridge_kip_left", $costTruckCrossingBridgeKipLeft);
            $stmt->bindParam(":cost_disinfectant_fee_kip", $costDisinfectantFeeKip);
            $stmt->bindParam(":cost_disinfectant_fee_kip_left", $costDisinfectantFeeKipLeft);
            $stmt->bindParam(":cost_report_passport_kip", $costReportPassportKip);
            $stmt->bindParam(":cost_report_passport_kip_left", $costReportPassportKipLeft);
            $stmt->bindParam(":cost_police_kip", $costPoliceKip);
            $stmt->bindParam(":cost_police_kip_left", $costPoliceKipLeft);
            $stmt->bindParam(":cost_repair_kip", $costRepairTireKip);
            $stmt->bindParam(":cost_repair_kip_left", $costRepairTireKipLeft);
            $stmt->bindParam(":other_kip", $otherKip);
            $stmt->bindParam(":other_kip_left", $otherKipLeft);
            $stmt->bindParam(":total_cost_kip", $totalCostKip);
            $stmt->bindParam(":total_cost_kip_left", $totalCostKipLeft);
            $stmt->bindParam(":costFeeBath", $costFeeBath);
            $stmt->bindParam(":cost_fee_bath_left", $costFeeBathLeft);
            $stmt->bindParam(":costLaoCheckPointOverTimeBath", $costLaoCheckPointOverTimeBath);
            $stmt->bindParam(":cost_check_point_overtime_bath_left", $costLaoCheckPointOverTimeBathLeft);
            $stmt->bindParam(":costTruckCrossingBridgeBath", $costTruckCrossingBridgeBath);
            $stmt->bindParam(":cost_truck_crossing_bridge_bath_left", $costTruckCrossingBridgeBathLeft);
            $stmt->bindParam(":costDisinfectantFeeBath", $costDisinfectantFeeBath);
            $stmt->bindParam(":cost_disinfectant_fee_bath_left", $costDisinfectantFeeBathLeft);
            $stmt->bindParam(":costReportPassportBath", $costReportPassportBath);
            $stmt->bindParam(":cost_report_passport_bath_left", $costReportPassportBathLeft);
            $stmt->bindParam(":costPoliceBath", $costPoliceBath);
            $stmt->bindParam(":cost_police_bath_left", $costPoliceBathLeft);
            $stmt->bindParam(":costRepairTireBath", $costRepairTireBath);
            $stmt->bindParam(":cost_repair_tire_bath_left", $costRepairTireBathLeft);
            $stmt->bindParam(":otherBath", $otherBath);
            $stmt->bindParam(":other_bath_left", $otherBathLeft);
            $stmt->bindParam(":totalCostBath", $totalCostBath);
            $stmt->bindParam(":total_cost_bath_left", $totalCostBathLeft);
            $stmt->bindParam(":km_goes", $kmGoes);
            $stmt->bindParam(":km_back", $kmBack);
            $stmt->bindParam(":distance", $distance);
            $stmt->bindParam(":change_oil", $changeOil);
            $stmt->bindParam(":create_by", $createBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateReleaseTruckSharingBill(
        $conn,
        $billReleaseTruckSharingNo,
        $goDate,
        $backDate,
        $licensePlate,
        $licensePlateBackPart,
        $driverFirst,
        $driverSec,
        $dateShipping,
        $storeCode,
        $product,
        // $origin,
        $destination,
        $gas,
        $gasPrice,
        $gasTotalPrice,
        $totalAllowance,
        $payFirst,
        $moneyLeft,
        $costFeeKip,
        $costFeeKipLeft,
        $costLaoCheckPointOverTimeKip,
        $costLaoCheckPointOverTimeKipLeft,
        $costTruckCrossingBridgeKip,
        $costTruckCrossingBridgeKipLeft,
        $costDisinfectantFeeKip,
        $costDisinfectantFeeKipLeft,
        $costReportPassportKip,
        $costReportPassportKipLeft,
        $costPoliceKip,
        $costPoliceKipLeft,
        $costRepairTireKip,
        $costRepairTireKipLeft,
        $otherKip,
        $otherKipLeft,
        $totalCostKip,
        $totalCostKipLeft,
        $costFeeBath,
        $costFeeBathLeft,
        $costLaoCheckPointOverTimeBath,
        $costLaoCheckPointOverTimeBathLeft,
        $costTruckCrossingBridgeBath,
        $costTruckCrossingBridgeBathLeft,
        $costDisinfectantFeeBath,
        $costDisinfectantFeeBathLeft,
        $costReportPassportBath,
        $costReportPassportBathLeft,
        $costPoliceBath,
        $costPoliceBathLeft,
        $costRepairTireBath,
        $costRepairTireBathLeft,
        $otherBath,
        $otherBathLeft,
        $totalCostBath,
        $totalCostBathLeft,
        $kmGoes,
        $kmBack,
        $distance,
        $changeOil,
        $updateBy
    ) {

        $sql = "update release_truck_sharing set "
            . "go_date = :go_date, back_date = :back_date, license_plate = :license_plate, license_plate_back_part = :licensePlateBackPart, driver_first = :driver_first, driver_second = :driver_second, "
            . "date_shipping = :date_shipping, store_code = :store_code, product_code = :product_code, destination = :destination, gas = :gas, gas_price = :gas_price, "
            . "gas_total_price = :gas_total_price, total_allowance = :total_allowance, pay_first = :pay_first, money_left = :money_left, "
            . "cost_fee_kip = :cost_fee_kip, cost_fee_kip_left = :cost_fee_kip_left, cost_lao_check_point_overtime_kip = :cost_lao_check_point_overtime_kip, "
            . "cost_lao_check_point_overtime_kip_left = :cost_lao_check_point_overtime_kip_left, cost_truck_crossing_bridge_kip = :cost_truck_crossing_bridge_kip, cost_truck_crossing_bridge_kip_left = :cost_truck_crossing_bridge_kip_left, "
            . "cost_disinfectant_fee_kip = :cost_disinfectant_fee_kip, cost_disinfectant_fee_kip_left = :cost_disinfectant_fee_kip_left, cost_report_passport_kip = :cost_report_passport_kip, cost_report_passport_kip_left = :cost_report_passport_kip_left, "
            . "cost_police_kip = :cost_police_kip, cost_police_kip_left = :cost_police_kip_left, cost_repair_kip = :cost_repair_kip, cost_repair_kip_left = :cost_repair_kip_left, other_kip = :other_kip, other_kip_left = :other_kip_left, "
            . "total_cost_kip = :total_cost_kip, total_cost_kip_left = :total_cost_kip_left, "
            . "cost_fee_bath = :cost_fee_bath, cost_fee_bath_left = :cost_fee_bath_left, cost_check_point_overtime_bath = :cost_check_point_overtime_bath, cost_check_point_overtime_bath_left = :cost_check_point_overtime_bath_left, "
            . "cost_truck_crossing_bridge_bath = :cost_truck_crossing_bridge_bath, cost_truck_crossing_bridge_bath_left = :cost_truck_crossing_bridge_bath_left, "
            . "cost_disinfectant_fee_bath = :cost_disinfectant_fee_bath, cost_disinfectant_fee_bath_left = :cost_disinfectant_fee_bath_left, cost_report_passport_bath = :cost_report_passport_bath, "
            . "cost_report_passport_bath_left = :cost_report_passport_bath_left, cost_police_bath = :cost_police_bath, cost_police_bath_left = :cost_police_bath_left, cost_repair_tire_bath = :cost_repair_tire_bath, "
            . "cost_repair_tire_bath_left = :cost_repair_tire_bath_left, other_bath = :other_bath, other_bath_left = :other_bath_left, total_cost_bath = :total_cost_bath, total_cost_bath_left = :total_cost_bath_left, "
            . "km_goes = :km_goes, km_back = :km_back, distance = :distance, change_oil = :change_oil, update_by = :update_by, update_date = sysdate() "
            . " where bill_release_truck_sharing_no = :bill_release_truck_sharing_no";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_sharing_no", $billReleaseTruckSharingNo);
            $stmt->bindParam(":go_date", $goDate);
            $stmt->bindParam(":back_date", $backDate);
            $stmt->bindParam(":license_plate", $licensePlate);
            $stmt->bindParam(":licensePlateBackPart", $licensePlateBackPart);
            $stmt->bindParam(":driver_first", $driverFirst);
            $stmt->bindParam(":driver_second", $driverSec);
            $stmt->bindParam(":date_shipping", $dateShipping);
            $stmt->bindParam(":store_code", $storeCode);
            $stmt->bindParam(":product_code", $product);
            // $stmt->bindParam(":origin", $origin);
            $stmt->bindParam(":destination", $destination);
            $stmt->bindParam(":gas", $gas);
            $stmt->bindParam(":gas_price", $gasPrice);
            $stmt->bindParam(":gas_total_price", $gasTotalPrice);
            $stmt->bindParam(":total_allowance", $totalAllowance);
            $stmt->bindParam(":pay_first", $payFirst);
            $stmt->bindParam(":money_left", $moneyLeft);
            $stmt->bindParam(":cost_fee_kip", $costFeeKip);
            $stmt->bindParam(":cost_fee_kip_left", $costFeeKipLeft);
            $stmt->bindParam(":cost_lao_check_point_overtime_kip", $costLaoCheckPointOverTimeKip);
            $stmt->bindParam(":cost_lao_check_point_overtime_kip_left", $costLaoCheckPointOverTimeKipLeft);
            $stmt->bindParam(":cost_truck_crossing_bridge_kip", $costTruckCrossingBridgeKip);
            $stmt->bindParam(":cost_truck_crossing_bridge_kip_left", $costTruckCrossingBridgeKipLeft);
            $stmt->bindParam(":cost_disinfectant_fee_kip", $costDisinfectantFeeKip);
            $stmt->bindParam(":cost_disinfectant_fee_kip_left", $costDisinfectantFeeKipLeft);
            $stmt->bindParam(":cost_report_passport_kip", $costReportPassportKip);
            $stmt->bindParam(":cost_report_passport_kip_left", $costReportPassportKipLeft);
            $stmt->bindParam(":cost_police_kip", $costPoliceKip);
            $stmt->bindParam(":cost_police_kip_left", $costPoliceKipLeft);
            $stmt->bindParam(":cost_repair_kip", $costRepairTireKip);
            $stmt->bindParam(":cost_repair_kip_left", $costRepairTireKipLeft);
            $stmt->bindParam(":other_kip", $otherKip);
            $stmt->bindParam(":other_kip_left", $otherKipLeft);
            $stmt->bindParam(":total_cost_kip", $totalCostKip);
            $stmt->bindParam(":total_cost_kip_left", $totalCostKipLeft);
            $stmt->bindParam(":cost_fee_bath", $costFeeBath);
            $stmt->bindParam(":cost_fee_bath_left", $costFeeBathLeft);
            $stmt->bindParam(":cost_check_point_overtime_bath", $costLaoCheckPointOverTimeBath);
            $stmt->bindParam(":cost_check_point_overtime_bath_left", $costLaoCheckPointOverTimeBathLeft);
            $stmt->bindParam(":cost_truck_crossing_bridge_bath", $costTruckCrossingBridgeBath);
            $stmt->bindParam(":cost_truck_crossing_bridge_bath_left", $costTruckCrossingBridgeBathLeft);
            $stmt->bindParam(":cost_disinfectant_fee_bath", $costDisinfectantFeeBath);
            $stmt->bindParam(":cost_disinfectant_fee_bath_left", $costDisinfectantFeeBathLeft);
            $stmt->bindParam(":cost_report_passport_bath", $costReportPassportBath);
            $stmt->bindParam(":cost_report_passport_bath_left", $costReportPassportBathLeft);
            $stmt->bindParam(":cost_police_bath", $costPoliceBath);
            $stmt->bindParam(":cost_police_bath_left", $costPoliceBathLeft);
            $stmt->bindParam(":cost_repair_tire_bath", $costRepairTireBath);
            $stmt->bindParam(":cost_repair_tire_bath_left", $costRepairTireBathLeft);
            $stmt->bindParam(":other_bath", $otherBath);
            $stmt->bindParam(":other_bath_left", $otherBathLeft);
            $stmt->bindParam(":total_cost_bath", $totalCostBath);
            $stmt->bindParam(":total_cost_bath_left", $totalCostBathLeft);
            $stmt->bindParam(":km_goes", $kmGoes);
            $stmt->bindParam(":km_back", $kmBack);
            $stmt->bindParam(":distance", $distance);
            $stmt->bindParam(":change_oil", $changeOil);
            $stmt->bindParam(":update_by", $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function deleteReleaseTruckSharingBill($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
