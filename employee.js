var employeeType = [
	{
		id : 1, employeeType : "Analyst", basicSalary : 50, HRA : 300, LTA : 500, overTime : 75
	},
	{
		id : 2, employeeType : "Lead Analyst", basicSalary : 100, HRA : 400, LTA : 500, overTime : 125
	},
	{
		id : 3, employeeType : "Manager", basicSalary : 200, HRA : 600, LTA : 1000, overTime : 250
	}
]

var employeeData = [
	{
		id : 1,	firstName : "Shishir",	lastName : "Bharadwaj",	age : 40,	type : 3, leaderId : null, technology : ".Net"
	},
	{
		id : 2,	firstName : "Veena",	lastName : "Nembhani",	age : 40 ,	type : 3 , leaderId : null, technology : "Java"
	},
	{
		id : 3,	firstName : "Ashish",	lastName : "Prabhu", age : 32, type : 2, leaderId : 2, technology : "Java"
	},
	{
		id : 4,	firstName : "Abhishek",	lastName : "Kapdoskar",	age : 32, type : 2 , leaderId : 1 , technology : ".Net"
	},
	{
		id : 5,	firstName : "Siddhiraj",	lastName : "Pantoji",	age : 30,	type : 1, leaderId : 3, technology : "Java"
	},
	{
		id : 6,	firstName : "Nikhil",	lastName : "Salvi",	age : 35,	type : 2, leaderId : 1, technology : ".Net"
	},
	{
		id : 7,	firstName : "Abish",	lastName : "Mathew",	age : 25,	type : 1, leaderId : 3, technology : "Java"
	},
	{
		id : 8,	firstName : "Aurora",	lastName : "Williams",	age : 22,	type : 1, leaderId : 6, technology : ".Net"
	},
	{
		id : 9,	firstName : "Harsh",	lastName : "Malik",	age : 30,	type : 1, leaderId : 4, technology :  ".Net"
	},
	{
		id : 10,	firstName : "Durvesh",	lastName : "Naik",	age : 33,	type : 2, leaderId : 1, technology : "Automation"
	},
	{
		id : 11,	firstName : "Divya",	lastName : "Mantri",	age : 32,	type : 1, leaderId : 10, technology : "Automation"
	}
	
]

var employeeAttendance = [
	{
		id : 1, employeeId : 4, Mon : 9, Tue : 9, Wed : 9, Thu : 9, Fri : 9, createdDate : "13-Aug-2018"
	}, 
	{
		id : 2, employeeId : 2, Mon : 7.5, Tue : 9, Wed : 5, Thu : 5, Fri : 5, createdDate : "13-Aug-2018"
	}, 
	{
		id : 3, employeeId : 7, Mon : 7.5, Tue : 7.5, Wed : 9, Thu : 9, Fri : 0, createdDate : "13-Aug-2018"
	}, 
	{
		id : 4, employeeId : 10, Mon : 8, Tue : 7.5, Wed : 8, Thu : 9, Fri : 8, createdDate : "13-Aug-2018"
	}, 
	{
		id : 5, employeeId : 11, Mon : 0, Tue : 0, Wed : 0, Thu : 12, Fri : 9, createdDate : "13-Aug-2018"
	}, 
	{
		id : 6, employeeId : 6, Mon : 4, Tue : 5, Wed : 4.5, Thu : 9, Fri : 12, createdDate : "13-Aug-2018"
	}, 
	{
		id : 7, employeeId : 3, Mon : 12, Tue : 12, Wed : 9, Thu : 7.5, Fri : 13, createdDate : "13-Aug-2018"
	}, 
	{
		id : 8, employeeId : 9, Mon : 8, Tue : 9, Wed : 8, Thu : 8, Fri : 8, createdDate : "13-Aug-2018"
	}, 
	{
		id : 9, employeeId : 8, Mon : 9, Tue : 8, Wed : 9, Thu : 9, Fri : 9, createdDate : "13-Aug-2018"
	}, 
	{
		id : 10, employeeId : 1, Mon : 10, Tue : 9, Wed : 6, Thu : 6, Fri : 8, createdDate : "13-Aug-2018"
	}, 
	{
		id : 11, employeeId : 5, Mon : 7.5, Tue : 2, Wed : 9, Thu : 10, Fri : 7.5, createdDate : "13-Aug-2018"
	}, 
]

var employee = prompt("Please Employee Id");

if (employee == null || employee == "") {
	console.log("Please add employee Id");
}else{
	var empData = getEmployeeData(employee);

	if(empData != null){
		var empType = getEmployeeType(empData.type);
		var empSalary = getEmployeeSalary(employee);
	
		actualHrs = 45;
		hrsCal =  empSalary;
		
		if(hrsCal > actualHrs){
			overTimeHrs = hrsCal - actualHrs;
			basicHrs = hrsCal - overTimeHrs;
		}else{
			overTimeHrs = 0;
			basicHrs = hrsCal - overTimeHrs;
		}

		overTimeAmt = overTimeHrs * empType.overTime;
		basicTotal = basicHrs * empType.basicSalary;
		totalPayOur = basicTotal + overTimeAmt + empType.HRA + empType.LTA;

		var empResult = `Employee Id: ${employee}
Employee Name: ${empData.firstName} ${empData.lastName} 
Employee Type: ${empType.employeeType}
Total Hours: ${empSalary}
Basic Salary: ${empType.basicSalary}
Basic Salary Payable: ${basicTotal}
Overtime Hours: ${overTimeHrs}
Overtime: ${empType.overTime}
Overtime Payable: ${overTimeAmt}
HRA: ${empType.HRA}
LTA: ${empType.LTA}
Total Salary Payable: ${totalPayOur}`;

		console.log(empResult);
}else{
	console.log("No such employee Id exist");
}

}


function getEmployeeData(empId){
	for(var i = 0; i < employeeData.length; i++) {
		if(employeeData[i].id == empId){
			return employeeData[i];
		}
	}
}


function getEmployeeType(emptId){
	for(var i = 0; i < employeeType.length; i++){
		if(employeeType[i].id == emptId){
			return employeeType[i];
		}
	}
}

function getEmployeeSalary(empId){
	for(var i = 0; i < employeeAttendance.length; i++){
		if(employeeAttendance[i].employeeId == empId){
			var hours = sumHrs(employeeAttendance[i].Mon, employeeAttendance[i].Tue, employeeAttendance[i].Wed, employeeAttendance[i].Thu, employeeAttendance[i].Fri);
			return hours;
		}
	}
}

function sumHrs(...numbers){
	var result = 0;
 	for(var i = 0; i < numbers.length; i++) {
  		result = result + numbers[i];
 	}
 	return result;
}
