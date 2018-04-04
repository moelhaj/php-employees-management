<?php


class Employees extends View
{

    public static function index()
    {
        View::redirect('employees/manage');
    } 

    public static function new()
    {

        $id = EmployeesModel::last();
        $last = $id['employee_id'];
        $last = (!$last) ? 10001 : (int)$last + 1;

        $departments = DepartmentsModel::get();
        $managers = EmployeesModel::manager();
        $jobs = JobsModel::get();

        View::render('employees/new.html',[
            'employee_id' => $last,
            'departments'=>$departments,
            'jobs' =>$jobs,
            'managers' => $managers
        ]);
    }
 
 	public static function manage()
	{
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $perPage = 8 ;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0 ;


        $employees = EmployeesModel::get($start, $perPage);
        $count = EmployeesModel::count();
        $departments = DepartmentsModel::get();

        $pages = ceil($count / $perPage) ;
        $pages = ($pages == 0) ? $pages = 1 : $pages;

        View::render('employees/manage.html', [
            'page' => $page,
            'employees' => $employees,
            'pages' => $pages,
            'departments' => $departments
        ]);
	}

     public static function profile($id)
    {   
        $employee = EmployeesModel::employee($id);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;

        View::render('employees/profile.html', [
            'employee'    => $employee,
            'page' => $page
        ]);
    }


    public static function edit($id)
    {
        $employee = EmployeesModel::employee($id);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;

        View::render('employees/edit.html', [
            'employee'    => $employee,
            'page' => $page
        ]);
    }


    public static function update()
    {
        $employee_id = (int)$_POST['employee_id'];

        $first_name = $_POST['first_name'];
        $middle_name = ($_POST['middle_name']) ? $_POST['middle_name'] : NULL;
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $marital_status = $_POST['marital_status'];
        $gender = $_POST['gender'];

        $birth_parts = $_POST['birth_date'];
        $birth_date=date("Y-m-d",strtotime( $birth_parts));

        $age = date_diff(date_create($birth_date), date_create('today'))->y;

        $nationality = $_POST['nationality'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = ($_POST['state']) ? $_POST['state'] : NULL;
        $country = $_POST['country'];

        EmployeesModel::update($employee_id, $first_name, $middle_name, $last_name, $email, $phone, $age, $marital_status, $gender, $birth_date, $nationality);

        AddressModel::update($city, $state, $country, $address, $employee_id);

        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1 ;
        View::redirect('employees/manage',$page);  
    }

    public static function search()
    {
        $employee_name = $_POST['employee_name'];

        $employees = EmployeesModel::search($employee_name);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;

        View::render('employees/search.html', [
            'employees'    => $employees,
            'page' => $page
        ]);  
        
    }

    public static function filter()
    {

        $department = (int)$_POST['departments'];

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $perPage = 8 ;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0 ; 

        $employees = EmployeesModel::emp_dept($start,$perPage,$department);
        $count = EmployeesModel::count_emp_dept($department);
        $department_name = DepartmentsModel::department($department);

        $pages = ceil($count / $perPage) ;
        $pages = ($pages == 0) ? $pages = 1 : $pages;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;


        View::render('employees/filter.html', [
            'count'    => $count,   // maybe later delete this one
            'page' => $page,
            'employees' => $employees,
            'pages' => $pages,
            'department' => $department_name
        ]);
    }

    public static function create()
    {

        $employee_id = (int)$_POST['employee_id'];

        $first_name = $_POST['first_name'];
        $middle_name = ($_POST['middle_name']) ? $_POST['middle_name'] : NULL;
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $marital_status = $_POST['marital_status'];
        $gender = $_POST['gender'];
        $contract_type = $_POST['contract_type'];

        $birth_parts = $_POST['birth_date'];
        $birth_date=date("Y-m-d",strtotime( $birth_parts));

        $age = date_diff(date_create($birth_date), date_create('today'))->y;

        $nationality = $_POST['nationality'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = ($_POST['state']) ? $_POST['state'] : NULL;
        $country = $_POST['country'];
        $department = $_POST['department'];
        $job_id = $_POST['job_id'];
        $manager_id = ($_POST['manager']) ? $_POST['manager'] : NULL;
        $salary = $_POST['salary'];

        $hire_parts = $_POST['hire_date'];
        $hire_date=date("Y-m-d",strtotime( $hire_parts));

        if ($_POST['end_date']) {
            $end_parts = $_POST['end_date'];
            $end_date=date("Y-m-d",strtotime( $end_parts));
        } else {
            $end_date  = NULL;
        }


        
        EmployeesModel::add($employee_id, $first_name, $middle_name, $last_name, $email, $phone, $age, $marital_status, $gender, $birth_date, $nationality, $department, $job_id, $manager_id, $contract_type, $salary, $hire_date, $end_date);

        AddressModel::add($city, $state, $country, $address, $employee_id);

        Payroll::add($employee_id, $salary, $job_id);

        View::redirect('employees/manage');
    }

}