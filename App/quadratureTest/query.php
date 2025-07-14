<?php
include '../../Classes/DBM.php';
	include '../../Classes/Quadrature.php';
$db = new DBM();
$myYear =1871;
while($myYear <= 2020){
     $db->write("ALTER TABLE sest$myYear ADD ord VARCHAR(255);");
     $myYear++;
    
}
    
//
//CREATE TABLE Employee(
//Employee_Id CHAR(12)NOT NULL PRIMARY KEY,
//First_name CHAR(30),
//Last_name CHAR(30),
//Address VARCHAR(50),
//City CHAR,
//State CHAR,
//Salary INT,
//Gender CHAR,
//Age INT
//);
//
//DROP TABLE Job;
//
//CREATE TABLE job(
//Exempt_Non_Exempt_Status tinyint(1) NOT NULL PRIMARY KEY,
//Job_title CHAR,
//Job_description CHAR
//); 