<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php

    $createInstructorTable = "create table if not exists Instructor (
       id varchar(8) primary key,
       firstname varchar(30) not null,
       lastname varchar(30) not null,
       facility enum('FCNorth','FCSouth')   
    )";


    $createClassTable = "create table if not exists Class (
       id varchar(8) primary key,
       title varchar(50) not null,
       facility enum('FCNorth','FCSouth') not null,
       type  enum('group','individual') not null,
       iid varchar(8), 
       foreign key (iid) references Instructor(id)
    )";


    $createMemberTable = "create table if not exists Member (
       id varchar(8) primary key,
       firstname varchar(30) not null,
       lastname varchar(30) not null,
       password varchar(100) not null    
    )";

    $createEWTable = "create table if not exists EnrolWithdraw (
    cid varchar(8),
    mid varchar(8) ,
    primary key (cid,mid),
    foreign key (cid) references Class(id),
    foreign key (mid) references Member(id)   
    )";


    // check whether Class table has data
    $countClass = "select count(*) from Class";
    // check whether instructor table has data
    
    try {
        // create Instructor table
        $conn->query($createInstructorTable);
        // check whether Instructor table has data
        $countInstructor = "select count(*) from Instructor";
        $instructorResult = $conn->query($countInstructor);
        $instructorRow = $instructorResult->fetch_array();
        // if no data, insert data
        if ($instructorRow[0] == 0) {
            $insertInstructor = "INSERT INTO Instructor (id,firstname,lastname,facility) VALUES";
            $instructors = [

                ['John', 'Smith', 'FCNorth'],
                ['Jane', 'Doe', 'FCSouth'],
                ['Emily', 'Johnson', 'FCNorth'],
                ['Michael', 'Brown', 'FCSouth'],
                ['Linda', 'White', 'FCNorth'],
                ['Daniel', 'Harris', 'FCSouth'],
                ['Maria', 'Martin', 'FCNorth'],
                ['James', 'Thompson', 'FCSouth'],
                ['Lisa', 'Garcia', 'FCNorth'],
                ['Robert', 'Wilson', 'FCSouth'],
                ['Patricia', 'Anderson', 'FCNorth'],
                ['Charles', 'Taylor', 'FCSouth'],
                ['Laura', 'Moore', 'FCNorth'],
                ['Thomas', 'Lee', 'FCSouth'],
                ['Sarah', 'Jones', 'FCNorth'],
                ['Christopher', 'Young', 'FCSouth'],
                ['Karen', 'Hernandez', 'FCNorth'],
                ['Brian', 'Martinez', 'FCSouth'],
                ['Nancy', 'Davis', 'FCNorth'],
                ['Gary', 'Rodriguez', 'FCSouth']
            ];
            $values = [];
            foreach ($instructors as $instructor) {
                //generate id
                $instructorId = generateUniqueId("I");
                //get values
                $values[] = "('$instructorId','{$instructor[0]}','{$instructor[1]}','{$instructor[2]}')";
            }
            $insertInstructor .= implode(",", $values);
            // insert values
            $conn->query($insertInstructor);
        }
        // get all the instructor ids
        $selectiid = "select id from Instructor";
        $result = $conn->query($selectiid);
        while ($row = $result->fetch_assoc()) {
            // put all the instructor id in an array
            $instructorIds[] = $row['id'];
        }
        //create Class table
        $conn->query($createClassTable);
        $classResult = $conn->query($countClass);
        $classRow = $classResult->fetch_array();
        // if Class table is empty, insert value
        if ($classRow[0] == 0 && count($instructorIds) > 0) {
            $insertClass = "INSERT INTO Class (id, title, facility, type, iid) VALUES ";

            $classes = [
                ['Yoga', 'FCNorth', 'group'],
                ['Boxing', 'FCNorth', 'individual'],
                ['Pilates', 'FCSouth', 'group'],
                ['Zumba', 'FCNorth', 'group'],
                ['Crossfit', 'FCSouth', 'individual'],
                ['Spin', 'FCNorth', 'group'],
                ['Kickboxing', 'FCSouth', 'individual'],
                ['Aerobics', 'FCNorth', 'group'],
                ['HIIT', 'FCSouth', 'individual'],
                ['Dance', 'FCNorth', 'group'],
                ['Power Lifting', 'FCSouth', 'individual'],
                ['Circuit Training', 'FCNorth', 'group'],
                ['Bodybuilding', 'FCSouth', 'individual'],
                ['Meditation', 'FCNorth', 'group'],
                ['Cardio Fitness', 'FCSouth', 'group'],
                ['Water Aerobics', 'FCNorth', 'group'],
                ['Stretching', 'FCSouth', 'individual'],
                ['Core Training', 'FCNorth', 'group'],
                ['TRX', 'FCSouth', 'individual'],
                ['Ballet Fitness', 'FCNorth', 'group']
            ];
            $values = [];
            foreach ($classes as $class) {
                $classId = generateUniqueId("C");
                // randomly choose an instructor
                $randomInstructorId = $instructorIds[array_rand($instructorIds)];
                //get values
                $values[] = "('$classId','{$class[0]}','{$class[1]}','{$class[2]}','$randomInstructorId')";
            }
            $insertClass .= implode(',', $values);
            $conn->query($insertClass);

        }




        //create Member table
        $conn->query($createMemberTable);
        //create enroll withdraw table
        $conn->query($createEWTable);

        // echo "table created";
    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }
    ?>
</body>

</html>