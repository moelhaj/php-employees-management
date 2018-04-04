<?php

class Payroll extends View
{

    public static function index()
    {
        $count = PayrollModel::count();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $perPage = 8 ;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0 ; 
        $pages = ceil($count / $perPage) ;
        $payrolls = PayrollModel::get($start,$perPage);

        $pages = ($pages == 0) ? $pages = 1 : $pages;

        $departments = DepartmentsModel::get();
   
        View::render('payroll/payroll.html',[
            'page' => $page,
            'payrolls' => $payrolls,
            'pages' => $pages,
            'departments' => $departments
        ]);
    }

    public static function add($employee_id, $salary, $job_id)
    {

        $config = ConfigModel::get();

        $house_rent_config = $config['house_rent'];
        $medical_allowance = $config['medical_allowance'];
        $transport_allowance_config = $config['transport_allowance'];
        $other_allowance_config = $config['other_allowance'];
        $holidays = $config['holidays'];
        $life_insurance_deductions_config = $config['life_insurance_deductions'];

        $attendance = AttendanceModel::get($employee_id);

        $days_worked = $attendance['days_worked'];
        $overtime = $attendance['overtime'];

        $house_rent = $salary * $house_rent_config;
        $transport_allowance = $salary * $transport_allowance_config;
        $other_allowance = $salary *  $other_allowance_config;

        $life_insurance_deductions = $salary * $life_insurance_deductions_config;
       
       
        $job_level = JobsModel::job_level($job_id);

        if ($job_level == 'LE01') {
            $gross_salary = $salary + $house_rent + $medical_allowance +  $transport_allowance + $other_allowance;
        } else if($job_level == 'LE02'){
            $gross_salary = $salary + $medical_allowance +  $transport_allowance + $other_allowance;
        } else {
            $gross_salary = $salary + $medical_allowance +  $transport_allowance;
        }

        $gross_payable = ($gross_salary/$days_worked * ($days_worked + $holidays)) + (($gross_salary/($days_worked*8))*2*$overtime);

        $net_payable =  $gross_salary - $life_insurance_deductions;


        PayrollModel::add($employee_id, $salary, $house_rent, $medical_allowance, $transport_allowance, $other_allowance, $gross_salary, $gross_payable, $life_insurance_deductions, $net_payable);              

    }

    public static function slip($employee_id)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;

        $slip = PayrollModel::slip($employee_id);

        $date = date('d') . ' ' . date('M') . ' ' .date('Y');

        View::render('payroll/slip.html', [
            'slip' => $slip,
            'page' => $page,
            'date' => $date
        ]);
    }

    public static function search()
    {
        $employee_name = $_POST['employee_name'];
        $payrolls = PayrollModel::search($employee_name);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;

        View::render('payroll/search.html', [
            'payrolls'    => $payrolls,
            'page' => $page
        ]);  
        
    }

    public static function filter()
    {

        $department = (int)$_POST['departments'];

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $perPage = 8 ;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0 ; 

        $payrolls = PayrollModel::emp_dept($start,$perPage,$department);
        $count = EmployeesModel::count_emp_dept($department);
        $department_name = DepartmentsModel::department($department);

        $pages = ceil($count / $perPage) ;
        $pages = ($pages == 0) ? $pages = 1 : $pages;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;


        View::render('payroll/filter.html', [
            'page' => $page,
            'payrolls' => $payrolls,
            'pages' => $pages,
            'department' => $department_name
        ]);
    }
}

