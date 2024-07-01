<?php
$createTableUser = "create table if not exists User(
uid int unsigned auto_increment primary key,
name varchar (50) not null,
surname varchar (50) not null,
phone varchar(20) unique,
email varchar (50) unique,
password varchar (200) not null,
type enum('user','administrator')
)";

$createTableParkingLocation = "create table if not exists ParkingLocation(
pid varchar(20) primary key,
location varchar (100) unique,
description varchar (300) null,
parking_space int,
cost_per_hour int
)";

$createTableParking = "create table if not exists Parking(
uid int unsigned not null,
pid varchar(20) not null,
start_time datetime not null,
finish_time datetime,
primary key (uid,pid,start_time),
foreign key (uid) references User(uid),
foreign key (pid) references ParkingLocation(pid)
)";

$conn->query($createTableUser);
$conn->query($createTableParkingLocation);
$conn->query($createTableParking);


//Check whether ParkingLocation has records, if no records insert
$selectPL = "select * from ParkingLocation";
$result = $conn->query($selectPL);
if ($result->num_rows == 0) {
    $insertPL = "insert into ParkingLocation(pid, location, description, parking_space,cost_per_hour) values
    ('PL001', '101 Main St', 'Near the central shopping area', 50, 5),
    ('PL002', '102 Robinson St', 'Adjacent to the city park', 30, 4),
    ('PL003', '103 Green St', 'Covered parking close to offices', 100, 6),
    ('PL004', '104 Hospital St', 'Next to the sports complex', 2, 3),
    ('PL005', '105 Uni St', 'Underground parking with security', 120, 7)
    ";
    $conn->query($insertPL);
    // echo "data inserted";
}


?>