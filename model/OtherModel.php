<?php

class OtherModel
{


    function __construct()
    {
    }

    public function getAllOther($conn)
    {
        try {
            $sql = 'SELECT * FROM `other` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getOther($conn,  $which_part, $license_plate)
    {
        try {
            // $sql = 'SELECT * FROM `other` WHERE which_part = :which_part and license_plate = :license_plate';
            // $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':which_part', $which_part);
            // $stmt->bindParam(':license_plate', $license_plate);
            // if ($stmt->execute()) {
            //     return $stmt;
            // }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewOther(
        $conn,
        $compensation_for_employees_per_lit,
        $social_security_per_month,
        $diesel_fuel_price,
        $gas_cost_price_per_kg,
        $average_car_driven,
        $createBy
    ) {

        try {
            $sql = "
                insert into other(
                    compensation_for_employees_per_lit, social_security_per_month, 
                    diesel_fuel_price, gas_cost_price_per_kg, average_car_driven,
                    create_by, create_date
                ) values (
                    :compensation_for_employees_per_lit, :social_security_per_month, :diesel_fuel_price,
                    :gas_cost_price_per_kg, :average_car_driven,
                    :create_by, sysdate()
                )  
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':compensation_for_employees_per_lit', $compensation_for_employees_per_lit);
            $stmt->bindParam(':social_security_per_month', $social_security_per_month);
            $stmt->bindParam(':diesel_fuel_price', $diesel_fuel_price);
            $stmt->bindParam(':gas_cost_price_per_kg', $gas_cost_price_per_kg);
            $stmt->bindParam(':average_car_driven', $average_car_driven);
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

    public function updateOther(
        $conn,
        $which_part,
        $license_plate,
        $position_wheel,
        $old_tire_number,
        $change_tire_date,
        $reason_for_change,
        $new_tire_number,
        $updateBy
    ) {
        try {
            // $sql = "
            //     UPDATE other SET 
            //     position_wheel = :position_wheel, 
            //     old_tire_number = :old_tire_number, 
            //     change_tire_date = :change_tire_date,
            //     reason_for_change = :reason_for_change, 
            //     new_tire_number = :new_tire_number, 
            //     update_by = :update_by, 
            //     update_date = sysdate()
            //     where  which_part = :which_part and  license_plate = :license_plate
            // ";
            // $stmt = $conn->prepare($sql);
            // $stmt->bindParam(':position_wheel', $position_wheel);
            // $stmt->bindParam(':old_tire_number', $old_tire_number);
            // $stmt->bindParam(':change_tire_date', $change_tire_date);
            // $stmt->bindParam(':reason_for_change', $reason_for_change);
            // $stmt->bindParam(':new_tire_number', $new_tire_number);
            // $stmt->bindParam(':update_by', $updateBy);
            // $stmt->bindParam(':which_part', $which_part);
            // $stmt->bindParam(':license_plate', $license_plate);
            // if ($stmt->execute()) {
            //     return true;
            // }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteOther($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
