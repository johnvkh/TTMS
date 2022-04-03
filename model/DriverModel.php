<?php

class DriverModel
{

    function __construct()
    {
    }

    public function getAllDriver($conn)
    {
        try {
            $sql = 'SELECT * FROM `driver` order by create_date desc';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getDriver($conn, $driverCode)
    {
        try {
            $sql = 'SELECT * FROM driver WHERE driver_code = :driver_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':driver_code', $driverCode);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function reportDriverLicenseExpired($conn)
    {
        try {
            $sql = "SELECT * FROM driver WHERE DATE_FORMAT(expire_date_driver_license,'%Y-%m-%d') < DATE_FORMAT(SYSDATE(),'%Y-%m-%d');";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDriver(
        $conn,
        $driverCode,
        $namePrefix,
        $firstname,
        $lastname,
        $idCard,
        $taxPayerCode,
        $driverLicenseNumber,
        $issueDateDriverLicense,
        $expireDateDriverLicense,
        $districtIssuedDriverLicense,
        $provinceIssuedDriverLicense,
        $address,
        $homeTel,
        $mobileTel,
        $contactNameRef,
        $relationshipRef,
        $mobileTelRef,
        $dateAdmission,
        $status,
        $reasionLeavingWork,
        $leavingWorkDate,
        $incomePerDay,
        $incomePerMonth,
        $phoneBill,
        $imagePath,
        $createBy
    ) {
        $sql = "INSERT INTO `driver` (
            `driver_code`, `name_prefix`, `firstname`, `lastname`, `id_card`,
          `tax_payer_code`, `driver_license_number`, `issue_date_driver_license`,
           `expire_date_driver_license`, `district_issued_driver_license`, 
           `province_issued_driver_license`, `address`, `home_tel`, `mobile_tel`,
            `contact_name_ref`, `relationship_ref`, `mobile_tel_ref`, `date_admission`,
             `status`, `reasion_leaving_work`, `leaving_work_date`, `income_per_day`,
              `income_per_month`, `phone_bill`, `image_path`, `create_by`, `create_date`
               )
        VALUES (
            :driver_code, :name_prefix, :firstname, :lastname, :id_card,
            :tax_payer_code, :driver_license_number, :issue_date_driver_license,
            :expire_date_driver_license, :district_issued_driver_license, 
            :province_issued_driver_license, :address, :home_tel, :mobile_tel,
            :contact_name_ref, :relationship_ref, :mobile_tel_ref, :date_admission,
            :status, :reasion_leaving_work, :leaving_work_date, :income_per_day,
            :income_per_month, :phone_bill, :image_path, :create_by, sysdate()
        )";


        // echo $sql;


        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':driver_code', $driverCode);
        $stmt->bindParam(':name_prefix', $namePrefix);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':id_card', $idCard);
        $stmt->bindParam(':tax_payer_code', $taxPayerCode);
        $stmt->bindParam(':driver_license_number', $driverLicenseNumber);
        $stmt->bindParam(':issue_date_driver_license', $issueDateDriverLicense);
        $stmt->bindParam(':expire_date_driver_license', $expireDateDriverLicense);
        $stmt->bindParam(':district_issued_driver_license', $districtIssuedDriverLicense);
        $stmt->bindParam(':province_issued_driver_license', $provinceIssuedDriverLicense);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':home_tel', $homeTel);
        $stmt->bindParam(':mobile_tel', $mobileTel);
        $stmt->bindParam(':contact_name_ref', $contactNameRef);
        $stmt->bindParam(':relationship_ref', $relationshipRef);
        $stmt->bindParam(':mobile_tel_ref', $mobileTelRef);
        $stmt->bindParam(':date_admission', $dateAdmission);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':reasion_leaving_work', $reasionLeavingWork);
        $stmt->bindParam(':leaving_work_date', $leavingWorkDate);
        $stmt->bindParam(':income_per_day', $incomePerDay);
        $stmt->bindParam(':income_per_month', $incomePerMonth);
        $stmt->bindParam(':phone_bill', $phoneBill);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->bindParam(':create_by', $createBy);
        if ($stmt->execute()) {
            return true;
        }
        return false;
        try {
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateDriver(
        $conn,
        $driverCode,
        $namePrefix,
        $firstname,
        $lastname,
        $idCard,
        $taxPayerCode,
        $driverLicenseNumber,
        $issueDateDriverLicense,
        $expireDateDriverLicense,
        $districtIssuedDriverLicense,
        $provinceIssuedDriverLicense,
        $address,
        $homeTel,
        $mobileTel,
        $contactNameRef,
        $relationshipRef,
        $mobileTelRef,
        $dateAdmission,
        $status,
        $reasionLeavingWork,
        $leavingWorkDate,
        $incomePerDay,
        $incomePerMonth,
        $phoneBill,
        $imagePath,
        $updateBy
    ) {
        try {

            $sql = "UPDATE `driver` SET 
                `name_prefix` = :name_prefix,
                `firstname` = :firstname,
                `lastname` = :lastname,
                `id_card` = :id_card,
                `tax_payer_code` = :tax_payer_code,
                `driver_license_number` = :driver_license_number, 
                `issue_date_driver_license` = :issue_date_driver_license,
                `expire_date_driver_license` = :expire_date_driver_license,
                `district_issued_driver_license` = :district_issued_driver_license, 
                `province_issued_driver_license` = :province_issued_driver_license,
                `address` = :address,
                `home_tel`= :home_tel,
                `mobile_tel` = :mobile_tel,
                `contact_name_ref` = :contact_name_ref,
                `relationship_ref` = :relationship_ref,
                `mobile_tel_ref` = :mobile_tel_ref,
                `date_admission` = :date_admission,
                `status` = :status,
                `reasion_leaving_work` = :reasion_leaving_work, 
                `leaving_work_date` = :leaving_work_date,
                `income_per_day` = :income_per_day,
                `income_per_month` = :income_per_month,
                `phone_bill` = :phone_bill,
                `image_path` = :image_path,
                `update_by` = :update_by, 
                `update_date` = sysdate() 
                where driver_code = :driver_code";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name_prefix', $namePrefix);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':id_card', $idCard);
            $stmt->bindParam(':tax_payer_code', $taxPayerCode);
            $stmt->bindParam(':driver_license_number', $driverLicenseNumber);
            $stmt->bindParam(':issue_date_driver_license', $issueDateDriverLicense);
            $stmt->bindParam(':expire_date_driver_license', $expireDateDriverLicense);
            $stmt->bindParam(':district_issued_driver_license', $districtIssuedDriverLicense);
            $stmt->bindParam(':province_issued_driver_license', $provinceIssuedDriverLicense);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':home_tel', $homeTel);
            $stmt->bindParam(':mobile_tel', $mobileTel);
            $stmt->bindParam(':contact_name_ref', $contactNameRef);
            $stmt->bindParam(':relationship_ref', $relationshipRef);
            $stmt->bindParam(':mobile_tel_ref', $mobileTelRef);
            $stmt->bindParam(':date_admission', $dateAdmission);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':reasion_leaving_work', $reasionLeavingWork);
            $stmt->bindParam(':leaving_work_date', $leavingWorkDate);
            $stmt->bindParam(':income_per_day', $incomePerDay);
            $stmt->bindParam(':income_per_month', $incomePerMonth);
            $stmt->bindParam(':phone_bill', $phoneBill);
            $stmt->bindParam(':image_path', $imagePath);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':driver_code', $driverCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDriver($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
