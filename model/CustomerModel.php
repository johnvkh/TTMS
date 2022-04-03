<?php

class CustomerModel
{


    function __construct()
    {
    }

    public function getAllCustomerModel($conn)
    {
        try {
            $sql = 'SELECT * FROM `customer` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getCustomerModel($conn, $customer_code)
    {
        try {
            $sql = 'SELECT * FROM `customer` WHERE customer_code = :customer_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':customer_code', $customer_code);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewCustomerModel(
        $conn,
        $customer_code,
        $fullname,
        $address,
        $tel,
        $fax,
        $mobile_phone,
        $email,
        $contact_name_by,
        $start_contact_date,
        $note,
        $createBy
    ) {

        try {
            $sql = "
                insert into customer (customer_code, fullname, address, tel, fax, mobile_phone, email, contact_name_by, start_contact_date, note, create_by, create_date) 
                values (:customer_code, :fullname, :address, :tel, :fax, :mobile_phone, :email, :contact_name_by, :start_contact_date, :note, :create_by, sysdate())
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':customer_code', $customer_code);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':fax', $fax);
            $stmt->bindParam(':mobile_phone', $mobile_phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact_name_by', $contact_name_by);
            $stmt->bindParam(':start_contact_date', $start_contact_date);
            $stmt->bindParam(':note', $note);
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

    public function updateCustomerModel(
        $conn,
        $customer_code,
        $fullname,
        $address,
        $tel,
        $fax,
        $mobile_phone,
        $email,
        $contact_name_by,
        $start_contact_date,
        $note,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE customer SET 
                 fullname = :fullname,
                  address = :address,
                   tel = :tel, 
                   fax = :fax,
                    mobile_phone = :mobile_phone,
                     email = :email,
                      contact_name_by = :contact_name_by, 
                      start_contact_date = :start_contact_date,
                       note = :note, 
                       update_by = :update_by, 
                       update_date = sysdate()
                       where customer_code = :customer_code
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':fax', $fax);
            $stmt->bindParam(':mobile_phone', $mobile_phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact_name_by', $contact_name_by);
            $stmt->bindParam(':start_contact_date', $start_contact_date);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':customer_code', $customer_code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDeliveryLocation($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
