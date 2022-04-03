<?php

class TruckSharingWorkingBillModel
{


    function __construct()
    {
    }

    public function getAllTruckSharingWorkingBill($conn)
    {
        $sql = "select 
        a.id,
        a.bill_release_truck_sharing_no,
        b.go_date,
        b.back_date,
        b.license_plate,
        b.driver_first,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_first) as driver_name_first,
        b.driver_second,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_second) as driver_name_sec,
        b.date_shipping,
        b.product_code,
        (select c.product_name from transported_goods c where c.product_code = b.product_code) as product_name,
        b.destination,
        (select concat(e.start, ' - ', e.end) from set_way_allowance_transport_rate e where e.way_code = b.destination) as destination_name,
        a.wight,
        a.ton_per,
        a.total_price,
        a.create_by,
        a.create_date,
        a.update_by,
        a.update_date
        from truck_sharing_working_bill a, release_truck_sharing b where a.bill_release_truck_sharing_no = b.bill_release_truck_sharing_no order by a.id desc";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckSharingWorkingBill($conn,  $truck_sharing_working_bill_id)
    {

        $sql = "select 
        a.id,
        a.bill_release_truck_sharing_no,
        b.go_date,
        b.back_date,
        b.license_plate,
        b.driver_first,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_first) as driver_name_first,
        b.driver_second,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_second) as driver_name_sec,
        b.date_shipping,
        b.product_code,
        (select c.product_name from transported_goods c where c.product_code = b.product_code) as product_name,
        b.destination,
        (select concat(e.start, ' - ', e.end) from set_way_allowance_transport_rate e where e.way_code = b.destination) as destination_name,
        a.wight,
        a.ton_per,
        a.total_price,
        a.create_by,
        a.create_date,
        a.update_by,
        a.update_date
        from truck_sharing_working_bill a, release_truck_sharing b where a.bill_release_truck_sharing_no = b.bill_release_truck_sharing_no and a.id = :truck_sharing_working_bill_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":truck_sharing_working_bill_id", $truck_sharing_working_bill_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getReleaseTruckSharingBillByBillReleaseTruckSharingNo($conn, $billReleaseTruckSharingNo)
    {
        $sql = "select
        a.bill_release_truck_sharing_no,
        a.go_date,
        a.back_date,
        a.license_plate,
        a.driver_first,
        a.driver_second,
        a.date_shipping,
        a.product_code,
        a.destination 
        from release_truck_sharing a where a.bill_release_truck_sharing_no = :bill_release_truck_sharing_no";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_sharing_no", $billReleaseTruckSharingNo);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTruckSharingWorkingBill(
        $conn,
        $billReleaseTruckSharingNo,
        $weight,
        $ton_per,
        $totalPrice,
        $createBy
    ) {
        $sql = "insert into truck_sharing_working_bill (bill_release_truck_sharing_no, wight, ton_per, total_price, create_by, create_date) "
            . " values (:bill_release_truck_sharing_no, :wight, :ton_per, :total_price, :create_by, sysdate())";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_sharing_no", $billReleaseTruckSharingNo);
            $stmt->bindParam(":wight", $weight);
            $stmt->bindParam(":ton_per", $ton_per);
            $stmt->bindParam(":total_price", $totalPrice);
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

    public function updateTruckSharingWorkingBill(
        $conn,
        $billReleaseTruckSharingNo,
        $weight,
        $ton_per,
        $totalPrice,
        $updateBy
    ) {
        $sql = "update truck_sharing_working_bill set wight = :wight, ton_per = :ton_per, total_price = :total_price, update_by = :update_by, update_date = sysdate() where id = :truck_sharing_working_bill_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":truck_sharing_working_bill_id", $billReleaseTruckSharingNo);
            $stmt->bindParam(":wight", $weight);
            $stmt->bindParam(":ton_per", $ton_per);
            $stmt->bindParam(":total_price", $totalPrice);
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

    public function checkBillReleaseTruckSharingNo($conn, $billReleaseTruckSharingNo)
    {
        $sql = "select bill_release_truck_sharing_no from truck_sharing_working_bill where bill_release_truck_sharing_no = :bill_release_truck_sharing_no";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_sharing_no", $billReleaseTruckSharingNo);
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

    public function checkTruckSharingWorkingBillId($conn, $truckSharingWorkingBillId)
    {
        $sql = "select id from truck_sharing_working_bill where id = :truck_sharing_working_bill_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":truck_sharing_working_bill_id", $truckSharingWorkingBillId);
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
}
