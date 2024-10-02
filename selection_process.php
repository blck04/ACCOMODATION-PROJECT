<?php 
    require("connect.php");
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
        $student_number_array[] = $row['studentNumber'];
        }

        function getRandomElements($originalArray, $newSize) {
            $newArray = [];
            $usedIndices = []; // To keep track of already used indices
            
            // Ensure the new size does not exceed the original array size
            $newSize = min($newSize, count($originalArray));
        
            while (count($newArray) < $newSize) {
                $randomIndex = rand(0, count($originalArray) - 1);
                    
                // Check if the index has already been used
                if (!in_array($randomIndex, $usedIndices)) {
                    $newArray[] = $originalArray[$randomIndex];
                    $usedIndices[] = $randomIndex; // Track used index
                    }
                    
                // If all elements have been used, break to avoid infinite loop
                if (count($usedIndices) === count($originalArray)) {
                    break;
            }
                }
            
            return $newArray;
            }    
        //Variable that stores the randomized array
        $accepted_array = getRandomElements($student_number_array, 40);

        $element = 0;
        $counter = 0;
        
        //This loop checks if the applicant is found in the database
        foreach ($accepted_array as $accepted_student_number) {

            foreach ($student_array as $student) {

                if($student['studentNumber'] == $accepted_student_number){ 
                    $query = "SELECT * FROM accepted WHERE student_number = '$accepted_student_number'";
                    $result = mysqli_query($conn, $query);

                    if(mysqli_num_rows($result) == 0) {

                        $accepted_name = $student['fullName'];
                        $accepter_id_number = $student['idNumber'];
                        $query = "INSERT INTO accepted(full_name,student_number,id_number) VALUES ('$accepted_name', '$accepted_student_number','$accepter_id_number')";
                        
                        if(mysqli_query($conn, $query)){
                            $counter++;
                        } else {
                            echo "Error inserting student: " . mysqli_error($conn);
                        }
                    }
                }
            }
        }
        echo "The size of the array with accepted applicants is: " , count($accepted_array);
    ?>
</body>

</html>