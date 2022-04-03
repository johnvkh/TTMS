<?php

class ReleaseTurckInModel
{


    function __construct()
    {
    }

    public function getAllReleaseTurckIn($conn)
    {
        try {
            $sql = 'SELECT * FROM `release_truck_in` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getReleaseTurckIn($conn,  $id)
    {
        try {
            $sql = 'SELECT * FROM `release_truck_in` WHERE id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewReleaseTurckIn(
        $conn,
        $date,
        $license_plate,
        $date_back,
        $driver_first,
        $driver_sec,
        $start_date,
        $customer_id,
        $work_order,
        $way,
        $store_destination,
        $weight,
        $quatity_per_box,
        $weight_per_truck,
        $quatity_per_one,
        $per_trip,
        $createBy
    ) {

        try {
            $sql = "
                insert into release_truck_in(
                    date, license_plate, date_back, driver_first, driver_sec, start_date, customer_id, work_order, way,
                    store_destination, weight, quatity_per_box, weight_per_truck, quatity_per_one, per_trip, create_by, create_date
                ) values (
                    :date, :license_plate, :date_back, :driver_first, :driver_sec, :start_date, :customer_id, :work_order, :way,
                    :store_destination, :weight, :quatity_per_box, :weight_per_truck, :quatity_per_one, :per_trip, :create_by, sysdate()
                )
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':date_back', $date_back);
            $stmt->bindParam(':driver_first', $driver_first);
            $stmt->bindParam(':driver_sec', $driver_sec);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':work_order', $work_order);
            $stmt->bindParam(':way', $way);
            $stmt->bindParam(':store_destination', $store_destination);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':quatity_per_box', $quatity_per_box);
            $stmt->bindParam(':weight_per_truck', $weight_per_truck);
            $stmt->bindParam(':quatity_per_one', $quatity_per_one);
            $stmt->bindParam(':per_trip', $per_trip);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateReleaseTurckIn(
        $conn,
        $id,
        $date,
        $license_plate,
        $date_back,
        $driver_first,
        $driver_sec,
        $start_date,
        $customer_id,
        $work_order,
        $way,
        $store_destination,
        $weight,
        $quatity_per_box,
        $weight_per_truck,
        $quatity_per_one,
        $per_trip,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE release_truck_in SET 
                date = :date, 
                license_plate = :license_plate, 
                date_back = :date_back, 
                driver_first = :driver_first, 
                driver_sec = :driver_sec, 
                start_date = :start_date, 
                customer_id = :customer_id, 
                work_order = :work_order, 
                way = :way,
                store_destination = :store_destination, 
                weight = :weight, 
                quatity_per_box = :quatity_per_box, 
                weight_per_truck = :weight_per_truck, 
                quatity_per_one = :quatity_per_one, 
                per_trip =:per_trip,
                update_by = :update_by, 
                update_date = sysdate()
                where  id = :id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':license_plate', $license_plate);
            $stmt->bindParam(':date_back', $date_back);
            $stmt->bindParam(':driver_first', $driver_first);
            $stmt->bindParam(':driver_sec', $driver_sec);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':work_order', $work_order);
            $stmt->bindParam(':way', $way);
            $stmt->bindParam(':store_destination', $store_destination);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':quatity_per_box', $quatity_per_box);
            $stmt->bindParam(':weight_per_truck', $weight_per_truck);
            $stmt->bindParam(':quatity_per_one', $quatity_per_one);
            $stmt->bindParam(':per_trip', $per_trip);
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

    public function deleteReleaseTurckIn($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
