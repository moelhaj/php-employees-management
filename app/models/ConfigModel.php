<?php

class ConfigModel
{
    public static function get()
    {
    	$stmt = Database::connect()->prepare("SELECT title, value FROM config");
        $stmt->execute();
        $config = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $config;
    }

    public static function all()
    {
    	$stmt = Database::connect()->prepare("SELECT * FROM config");
    	$stmt->execute();
    	$config = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $config;
    }

    public static function update($house_rent, $medical_allowance, $transport_allowance, $other_allowance, $marital_status, $holidays, $life_insurance_deductions)
    {
    	$stmt = Database::connect()->prepare("UPDATE config
				SET value = CASE WHEN title = 'house_rent' then :house_rent
                				 WHEN title = 'medical_allowance' then :medical_allowance
                				 WHEN title = 'transport_allowance' then :transport_allowance
                				 WHEN title = 'other_allowance' then :other_allowance
                				 WHEN title = 'marital_status' then :marital_status
                				 WHEN title = 'holidays' then :holidays
                				 WHEN title = 'life_insurance_deductions' then :life_insurance_deductions
                				  end");

        $stmt->bindparam(":house_rent",$house_rent);
        $stmt->bindparam(":medical_allowance",$medical_allowance);
        $stmt->bindparam(":transport_allowance",$transport_allowance);
        $stmt->bindparam(":other_allowance",$other_allowance);
        $stmt->bindparam(":marital_status",$marital_status);
        $stmt->bindparam(":holidays",$holidays);
        $stmt->bindparam(":life_insurance_deductions",$life_insurance_deductions);
        $stmt->execute();
    }
}