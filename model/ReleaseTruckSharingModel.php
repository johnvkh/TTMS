<?php

class ReleaseTruckSharingModel
{


    function __construct()
    {
    }

    public function getAllReleaseTruckSharing($conn)
    {
        try {
            $sql = "SELECT 
            a.bill_release_truck_sharing_no,
            a.go_date,
            a.back_date,
            a.license_plate,
            a.driver_first,
            a.driver_second,
            a.date_shipping,
            a.product_code,
            a.origin,
            a.destination,
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



    public function getReleaseTruckSharing($conn,  $id)
    {
        try {
            $sql = 'SELECT * FROM `release_truck_sharing` WHERE id = :id ';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewReleaseTruckSharing(
        $conn,
        $date,
        $license_plate,
        $owner_truck_sharing_code,
        $billRunningNumber,
        $customer_code,
        $shipping_bill_code,
        $way_code,
        $store_destination_code,
        $products_code,
        $weight,
        $shipping_cost_per_ton_or_per_each_kip,
        $shipping_cost_per_trip_kip,
        $start_shipping_date,
        $end_shipping_date,
        $note,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO release_truck_sharing (
                date,
                license_plate,
                owner_truck_sharing_code,
                billRunningNumber,
                customer_code,
                shipping_bill_code,
                way_code,
                store_destination_code,
                products_code,
                weight,
                shipping_cost_per_ton_or_per_each_kip,
                shipping_cost_per_trip_kip,
                start_shipping_date,
                end_shipping_date,
                note,
                create_by,
                create_date 
            )
            VALUES
                    (:date,
                    :license_plate,
                    :owner_truck_sharing_code,
                    :billRunningNumber,
                    :customer_code,
                    :shipping_bill_code,
                    :way_code,
                    :store_destination_code,
                    :products_code,
                    :weight,
                    :shipping_cost_per_ton_or_per_each_kip,
                    :shipping_cost_per_trip_kip,
                    :start_shipping_date,
                    :end_shipping_date,
                    :note,
                    :create_by,
                    SYSDATE())";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':owner_truck_sharing_code', $owner_truck_sharing_code);
            $stmt->bindParam(':billRunningNumber', $billRunningNumber);
            $stmt->bindParam(':customer_code', $customer_code);
            $stmt->bindParam(':shipping_bill_code', $shipping_bill_code);
            $stmt->bindParam(':way_code', $way_code);
            $stmt->bindParam(':store_destination_code', $store_destination_code);
            $stmt->bindParam(':products_code', $products_code);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':shipping_cost_per_ton_or_per_each_kip', $shipping_cost_per_ton_or_per_each_kip);
            $stmt->bindParam(':shipping_cost_per_trip_kip', $shipping_cost_per_trip_kip);
            $stmt->bindParam(':start_shipping_date', $start_shipping_date);
            $stmt->bindParam(':end_shipping_date', $end_shipping_date);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function updateReleaseTruckSharing(
        $conn,
        $id,
        $date,
        $license_plate,
        $customer_code,
        $shipping_bill_code,
        $way,
        $store_destination,
        $shipping_cost_per_ton,
        $shipping_cost_per_trip,
        $weight,
        $start_shipping_date,
        $end_shipping_date,
        $note,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE release_truck_sharing SET 
                `date` = :date,
                `license_plate` = :license_plate, 
                `customer_code` = :customer_code, 
                `shipping_bill_code` = :shipping_bill_code, 
                `way` = :way, 
                `store_destination` = :store_destination, 
                `shipping_cost_per_ton` = :shipping_cost_per_ton, 
                `shipping_cost_per_trip` = :shipping_cost_per_trip, 
                `weight` = :weight,     
                `start_shipping_date` = :start_shipping_date, 
                `end_shipping_date` = :end_shipping_date, 
                `note` = :note, 
                update_by = :update_by, 
                update_date = sysdate()
                where  id = :id 
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':customer_code', $customer_code);
            $stmt->bindParam(':shipping_bill_code', $shipping_bill_code);
            $stmt->bindParam(':way', $way);
            $stmt->bindParam(':store_destination', $store_destination);
            $stmt->bindParam(':shipping_cost_per_ton', $shipping_cost_per_ton);
            $stmt->bindParam(':shipping_cost_per_trip', $shipping_cost_per_trip);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':start_shipping_date', $start_shipping_date);
            $stmt->bindParam(':end_shipping_date', $end_shipping_date);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteReleaseTruckSharing($conn)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
