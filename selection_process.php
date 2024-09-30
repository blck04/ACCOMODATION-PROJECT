<?php 
    include_once  "connect.php";
?>

<!DOCTYPE html><html>

<head>
    <title></title>
</head>

<body>
    <?php

        $sql = "SELECT * FROM signup";
        $result = mysqli_query($conn, $sql);

        $student_array = array();

        //This method stores all the rows from the database in an array
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $student_array[] = $row;
            }
        }
        
        $student_number_array = array();
        $index = 0;

        //Taking out all the student numbers and storing them in an array
        foreach($student_array as $row){
            //This loop adds all the student numbers from the raw db array and adds them to the student_number array
            while($index<count($student_array)){
                $student_number_array[$index] = $row['studentNumber'];
                $index++;
            }   
        }

        //This function randomizes the applicant's array
        function array_random($array){
            shuffle($array);
            return $array;}
        
        
        //Variable that stores the randomized array
        $shuffled_array = array_random($student_number_array);

        foreach($shuffled_array as $element){
            echo $element;
        }
        
        $is_found = false;

        //This loop checks if the applicant is found in the database
        foreach($student_array as $row){
            //This loop does the linear search.
            for($i=0; $i<count($student_array); $i++){
                if($row['studentNumber'] == $shuffled_array[$i]){
                    $is_found = true;
                }
            };
            $found_student = $row['studentNumber'];
            if($is_found){
                echo "<p> The student $found_student has been selected. S/He is the chosen one!</p>";
                //echo "<h2>$txt1</h2>";
            }
            
        }
    ?>
</body>

</html>