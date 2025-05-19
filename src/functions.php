<?php

require_once('db.php');

class Functions
{

    /**
     * Sanitize input data to prevent SQL injection and XSS attacks
     *
     * @param string $input The input data to be sanitized
     * @param mysqli $con A database connection for escaping strings
     *
     * @return string The sanitized input data
     */

    public static function cleaninput($input, $con){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        $input = mysqli_real_escape_string($con, $input);
        return $input;
    }


    /**
     * Add a new user to the database
     *
     * @param mysqli $con A database connection
     *
     * @return void
     */
    public static function addnewuser($con){

        if(isset($_POST['name'])){

            $name   = $_POST['name'];
            $name   = self::cleaninput($name, $con);

        }else{
            $message    = 'name is required';
            $jsonoutput = json_encode($message);
            echo $jsonoutput;
            exit;
        }

        if(isset($_POST['email'])){

            $email = $_POST['email'];
            $email = self::cleaninput($email, $con);

        }else{
            $email = '';
        }

        if(isset($_POST['age'])){

            $age = $_POST['age'];
            $age = self::cleaninput($age, $con);

        }else{
            $age = '';
        }

        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');

        $inserquery = "INSERT INTO `users`( `name`, `email`, `age`, `created_at`, `updated_at`) 
                        VALUES ('$name','$email','$age','$created','$updated')";

        if (mysqli_query($con, $inserquery)) {
            $message = "New record created successfully";
            $jsonoutput = json_encode($message);
            echo $jsonoutput;
        } else {
            echo "Error: " . $inserquery . "<br>" . mysqli_error($con);
        }
    }


        /**
         * Get all users from the database
         *
         * @param mysqli $con A database connection
         *
         * @return void
         */
    public static function viewusers($con){

            $query  = "SELECT * FROM `users`";
            $result = mysqli_query($con, $query);
            $data   = array();
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            echo json_encode($data);
    }


    /**
     * Updates a user's information in the database.
     *
     * This function retrieves JSON input from the request, validates it, and updates
     * the specified user's details in the database using the provided fields. The user is identified
     * by their ID. If successful, a message indicating the successful update is returned.
     * 
     * @param mysqli $con The database connection object.
     *
     * @throws Exception If the input data is invalid or the user ID is not provided.
     * @throws Exception If the user is not found or if a database error occurs.
     *
     * Input JSON structure:
     * {
     *   "id": "User ID (required)",
     *   "name": "New name (optional)",
     *   "email": "New email (optional)",
     *   "age": "New age (optional)"
     * }
     * 
     * Output JSON structure on success:
     * {
     *   "fields updated": [Array of updated fields],
     *   "id": "User ID",
     *   "message": "User updated successfully",
     *   "response_code": 200
     * }
     */

    public static function updateuser($con){

        $rawData    = file_get_contents("php://input");
        $inputData  = json_decode($rawData, true);

        if (!$inputData) {
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }

        if (empty($inputData['id'])) {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $id     = self::cleaninput($inputData['id'], $con);

        $query  = "SELECT * FROM `users` WHERE `id`='$id'";
        $result = mysqli_query($con, $query);

        if (!$result || mysqli_num_rows($result) == 0) {
            echo json_encode(['error' => 'User not found']);
            exit;
        }

        $fieldsToUpdate = [];
        $updated = date('Y-m-d H:i:s');

        if (!empty($inputData['name'])) {
            $name = self::cleaninput($inputData['name'], $con);
            $fieldsToUpdate[] = "`name` = '$name'";
        }else {
            $message = "name is required";
            $message = json_encode($message);
            echo $message;
            exit;
        }

        if (!empty($inputData['email'])) {
            $email = self::cleaninput($inputData['email'], $con);
            $fieldsToUpdate[] = "`email` = '$email'";
        }else {
            $message = "email is required";
            $message = json_encode($message);
            echo $message;
            exit;
        }

        if (!empty($inputData['age'])) {
            $age = self::cleaninput($inputData['age'], $con);
            $fieldsToUpdate[] = "`age` = '$age'";
        }else {
            $message = "age is required";
            $message = json_encode($message);
            echo $message;
            exit;
        }

        if (!empty($fieldsToUpdate)) {
            $fieldsToUpdate[]   = "`updated_at` = '$updated'";
            $updateQuery        = "UPDATE `users` SET " . implode(', ', $fieldsToUpdate) . " WHERE `id` = '$id'";

            if (mysqli_query($con, $updateQuery)) {

                $message = [
                    'fields updated' => $fieldsToUpdate,
                    'id'             => $id,
                    'message'        => 'User updated successfully',
                    'response_code'  => 200
                ];

                echo json_encode($message);
                exit;
            } else {
                echo json_encode(['error' => mysqli_error($con)]);
            }
        } else {
            echo json_encode(['message' => 'No fields provided to update']);
        }

    }


    /**
     * Delete a user from the database
     *
     * @param mysqli $con A database connection
     *
     * @return void
     */
    public static function removeuser($con){

        $rawData    = file_get_contents("php://input");
        $inputData  = json_decode($rawData, true);

        if (!$inputData) {
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }

        if (empty($inputData['id'])) {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }
        if (!empty($inputData['id'])) {
            $id = self::cleaninput($inputData['id'], $con);

            $checkuserid = "SELECT * FROM `users` WHERE `id`='$id'";

            $result = mysqli_query($con, $checkuserid);
            if (mysqli_num_rows($result) > 0) {

                $query = "DELETE FROM `users` WHERE `id`='$id'";
                if (mysqli_query($con, $query)) {
                    echo json_encode(['message' => 'User deleted successfully']);
                } else {
                    echo json_encode(['error' => mysqli_error($con)]);
                }

            }else {
                echo json_encode(['message'=> 'User not found']);
            }
        } else {
            echo json_encode(['error' => 'ID is required']);
        }

    }


    /**
     * Updates the details of a user based on the provided input data.
     *
     * This function reads JSON input data, validates the presence of an ID, 
     * and checks if the user exists in the database. It updates the user's 
     * details (name, email, and/or age) if provided, along with the updated 
     * timestamp. If no fields are provided to update, it returns a message 
     * indicating so. Outputs success or error messages in JSON format.
     *
     * @param mysqli $con The MySQLi connection object.
     */

    public static function updateuserdetails($con){

        // Read raw input (JSON)
        $rawData = file_get_contents("php://input");
        $inputData = json_decode($rawData, true);

        if (!$inputData) {
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }

        // Validate ID
        if (empty($inputData['id'])) {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $id = self::cleaninput($inputData['id'], $con);

        // Check if user exists
        $query = "SELECT * FROM `users` WHERE `id`='$id'";
        $result = mysqli_query($con, $query);

        if (!$result || mysqli_num_rows($result) === 0) {
            echo json_encode(['error' => 'User not found']);
            exit;
        }

        // Fields to update
        $fieldsToUpdate = [];
        $updated = date('Y-m-d H:i:s');

        if (!empty($inputData['name'])) {
            $name = self::cleaninput($inputData['name'], $con);
            $fieldsToUpdate[] = "`name` = '$name'";
        }

        if (!empty($inputData['email'])) {
            $email = self::cleaninput($inputData['email'], $con);
            $fieldsToUpdate[] = "`email` = '$email'";
        }

        if (!empty($inputData['age'])) {
            $age = self::cleaninput($inputData['age'], $con);
            $fieldsToUpdate[] = "`age` = '$age'";
        }

        // If any fields to update
        if (!empty($fieldsToUpdate)) {
            $fieldsToUpdate[] = "`updated_at` = '$updated'";
            $updateQuery = "UPDATE `users` SET " . implode(', ', $fieldsToUpdate) . " WHERE `id` = '$id'";

            if (mysqli_query($con, $updateQuery)) {
                echo json_encode(['message' => 'User updated successfully (PATCH)']);
            } else {
                echo json_encode(['error' => mysqli_error($con)]);
            }
        } else {
            echo json_encode(['message' => 'No fields provided to update']);
        }



    }






}



?>