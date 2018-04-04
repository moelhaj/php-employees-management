<?php

class PayrollModel
{

    public static function count()
    {
        $stmt = Database::connect()->prepare("SELECT payroll_id FROM payroll");
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
    }


    public static function get($start,$perPage)
    {
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.last_name, j.job_title, p.salary, p.employee_id, p.house_rent, p.medical_allowance, p.transport_allowance, p.other_allowance, p.gross_salary, p.gross_payable, p.net_payable
            FROM payroll p
            INNER JOIN employees e
            ON p.employee_id = e.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
            LIMIT :start, :perpage ");
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $payroll = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $payroll;
    }

    public static function add($employee_id, $salary, $house_rent, $medical_allowance, $transport_allowance, $other_allowance, $gross_salary, $gross_payable, $life_insurance_deductions, $net_payable)
    {
        $stmt = Database::connect()->prepare("INSERT INTO payroll (employee_id, salary, house_rent, medical_allowance, transport_allowance, other_allowance, gross_salary, gross_payable, life_insurance_deductions, net_payable )VALUES(:employee_id, :salary, :house_rent, :medical_allowance, :transport_allowance, :other_allowance, :gross_salary, :gross_payable, :life_insurance_deductions, :net_payable)");


        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->bindparam(":salary",$salary);
        $stmt->bindparam(":house_rent",$house_rent);
        $stmt->bindparam(":medical_allowance",$medical_allowance);
        $stmt->bindparam(":transport_allowance",$transport_allowance);
        $stmt->bindparam(":other_allowance",$other_allowance);
        $stmt->bindparam(":gross_salary",$gross_salary);
        $stmt->bindparam(":gross_payable",$gross_payable);
        $stmt->bindparam(":life_insurance_deductions",$life_insurance_deductions);
        $stmt->bindparam(":net_payable",$net_payable);
        $stmt->execute();
    }

    
	public static function slip($employee_id)
	{
		$stmt = Database::connect()->prepare("SELECT p.salary, p.house_rent, p.medical_allowance, p.transport_allowance, p.other_allowance, p.gross_salary, p.gross_payable, p.life_insurance_deductions, p.net_payable, e.employee_id, e.first_name, e.last_name, j.job_title, d.department_name
            FROM payroll p 
            INNER JOIN employees e
            ON p.employee_id = e.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
            INNER JOIN departments d
            ON e.department_id = d.department_id
            WHERE p.employee_id = :employee_id ");
        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->execute();
        $slip = $stmt->fetch(PDO::FETCH_OBJ);
        return $slip;
	}


    public static function search($employee_name)
    {
        $stmt = Database::connect()->prepare("SELECT p.salary, p.house_rent, p.medical_allowance, p.transport_allowance, p.other_allowance, p.gross_salary, p.gross_payable, p.life_insurance_deductions, p.net_payable, e.employee_id, e.first_name, e.last_name, j.job_title, d.department_name
            FROM payroll p 
            INNER JOIN employees e
            ON p.employee_id = e.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
            INNER JOIN departments d
            ON e.department_id = d.department_id
        WHERE e.first_name LIKE :name OR e.last_name LIKE :name ");
        $stmt->bindValue(':name', '%'.$employee_name.'%', PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $employees;
    }


    public static function emp_dept($start,$perPage,$department)
    {
        $stmt = Database::connect()->prepare("SELECT p.salary, p.house_rent, p.medical_allowance, p.transport_allowance, p.other_allowance, p.gross_salary, p.gross_payable, p.life_insurance_deductions, p.net_payable, e.employee_id, e.first_name, e.last_name, j.job_title, d.department_name
            FROM payroll p 
            INNER JOIN employees e
            ON p.employee_id = e.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
            INNER JOIN departments d
            ON e.department_id = d.department_id
            WHERE e.department_id = :department
            LIMIT :start, :perpage ");
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':department', $department, PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $employees;
    }

    public static function count_emp_dept($department)
    {
        $stmt = Database::connect()->prepare("SELECT id FROM employees WHERE department_id = :department");
        $stmt->bindValue(':department', $department, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
    }


    public static function grossWages()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(gross_payable) as grossWages FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $grossWages = $result['grossWages'];
        return $grossWages;
    } 


    public static function netPayables()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(net_payable) as netPayables FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $netPayables = $result['netPayables'];
        return $netPayables;
    } 

    public static function deduction()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(life_insurance_deductions) as deduction FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $deduction = $result['deduction'];
        return $deduction;
    } 


    public static function health()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(medical_allowance) as health FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $health = $result['health'];
        return $health;
    } 

    public static function transport()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(transport_allowance) as transport FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $transport = $result['transport'];
        return $transport;
    } 


    public static function other_allowance()
    {
        $stmt = Database::connect()->prepare("SELECT SUM(other_allowance) as other_allowance FROM payroll");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $other_allowance = $result['other_allowance'];
        return $other_allowance;
    } 
}

