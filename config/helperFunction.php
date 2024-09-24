<?php
    require 'dbcon.php';

/**
 *  0.  debug()
 *  1.  validate()
 *  2.  emailCheck()
 *  3.  passwordCheck()
 *  4.  isBannedCheck()
 *  5.  insert()
 *  6.  updateWithImage()
 *  7.  insertWithImage()
 *  8.  deleteMultipleImage()
 *  9.  deleteSingleImageFromDirectory()
 *  10. deleteSingleImageFromDatabaseTable()
 *  11. multipleImageInsert()
 *  12. imageInsert()
 *  13. update()
 *  14. getAll()
 *  15. getById()
 *  16. getByColumn()
 *  17. getMultipleTableData()
 *  18. getPicturesAccordingToIds()
 *  19. delete()
 *  20. normalDelete()
 *  21. checkParamId()
 *  22. debug()
 *  23. fetchAllFromMultipleTable()
 *  24. orderItemQuery()
 *  25. getCount()
 *
 */

    function debug($value){
        echo '<pre>';
            print_r($value);
        echo '</pre>';
            var_dump($value);
    }

    function validate($inputData){
        global $conn;
        $validateData = mysqli_real_escape_string($conn, $inputData);
        return trim($validateData);
    }

    function validatee($inputData){
        global $conn;
        $validateData = mysqli_real_escape_string($conn, $inputData);
        return trim($validateData);
    }


    function emailCheck($tableName, $email){

        global $conn;
        $emailCheck = mysqli_query($conn, "SELECT * FROM $tableName WHERE email = '$email' LIMIT 1");

        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                $row = mysqli_fetch_array($emailCheck, MYSQLI_ASSOC);
                return [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record found'
                ];
            }else{
                return [
                    'status' => 404,
                    'message' => 'No record found'
                ];
            }
        }else{
            return [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        }
    }
    function passwordCheck($tableName, $email, $password){
        global $conn;
        $emailCheck =  emailCheck($tableName, $email);
        if ($emailCheck['status'] == 200) {
            $hashed_password = $emailCheck['data']['password'];

            if (password_verify($password, $hashed_password)) {
                return [
                    'status' => 200,
                    'message' => 'matched Password.'
                ];
            }else{
                return [
                    'status' => 404,
                    'message' => 'Invalid password!'
                ];
            }
        }else{
            return [
                'status' => 500,
                'message' => 'Something went wrong in email check'
            ];
        }
    }

    function isBannedCheck($tableName, $email)
    {
        global $conn;
        $emailCheck = emailCheck($tableName, $email);
        if ($emailCheck['status'] == 200) {
            $is_ban = $emailCheck['data']['is_ban'];
            if ($is_ban == 0) {
                return [
                    'status' => 200,
                    'message' => 'Not Banned.'
                ];
            } else {
                return [
                    'status' => 201,
                    'message' => 'Banned.'
                ];
            }
        } else {
            return [
                'status' => 404,
                'message' => 'No record found'
            ];
        }
    }

    function insert($tableName, $data, $images=null,$directory=null){

        global $conn;
        $table = validate($tableName);
        $columns = array_keys($data);
        $values = array_values($data);

        $findColumns = implode(',', $columns);
        $findValues = "'".implode("', '", $values)."'";

        $query = "INSERT INTO $table ($findColumns) VALUES($findValues)";
    //        $result = $conn->query($query);
        $result = mysqli_query($conn, $query);
        $lastInsertId = mysqli_insert_id($conn);

//        if(!empty($images)){
//            $imageData = [
//                'images' => $images,
//                'product_id' => $lastInsertId,
//                'product_name' => $data['name'],
//                'directory' => $directory,
//            ];
//            $imageInsertionResult = multipleImageInsert($imageData);
//            if (!$imageInsertionResult) {
//
//            }
//        }
        return $result;
    }

    function updateWithImage($updateId,$tableName, $data, $images = null, $directory = null,  $imageToDelete = null){
        global $conn;

        // Start a transaction
        mysqli_begin_transaction($conn);

        try {
            $result = update($tableName, $updateId, $data);
            if (!$result) {
                throw new Exception("Failed to insert product.");
            }
                if (!empty($images)) {
                    $imageData = [
                        'images' => $images,
                        'product_id' => $updateId,
                        'product_name' => $data['name'],  // Assuming 'name' exists in $data
                        'directory' => $directory,
                    ];
                    $imageInsertionResult = multipleImageInsert($imageData);
                    if (!$imageInsertionResult) {
                        throw new Exception("Failed to insert images.");
                    }
                }

                if (!empty($imageToDelete)) {
                    $deleteMultipleImage = deleteMultipleImage($imageToDelete, $directory);
                    if (!$deleteMultipleImage) {
                        throw new Exception("Failed to delete images.");
                    }
                }
                // Commit the transaction
                mysqli_commit($conn);

                return $result;

            } catch (Exception $e) {
            // Rollback the transaction
            mysqli_rollback($conn);
            return false; // You may also handle the exception or log the error
        }
    }

    function insertWithImage($tableName, $data, $images = null, $directory = null,  $imageToDelete = null) {

        global $conn;
        $table = validate($tableName);
        $columns = array_keys($data); // get Keys of the array
        $placeholders = implode(', ', array_fill(0, count($columns), '?')); // help from chat gpt

        $findColumns = implode(',', $columns);

        // Start a transaction
        mysqli_begin_transaction($conn);

        try {
            $query = "INSERT INTO $table ($findColumns) VALUES($placeholders)";
            $stmt = mysqli_prepare($conn, $query);

            // Create an array of references to pass to mysqli_stmt_bind_param
            $types = str_repeat('s', count($data));  // Assuming all values are strings; adjust types as necessary
            $values = array_values($data);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, $types, ...$values);

            // Execute the statement
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                throw new Exception("Failed to insert product.");
            }
            $lastInsertId = mysqli_insert_id($conn);

            if (!empty($images)) {
                $imageData = [
                    'images' => $images,
                    'product_id' => $lastInsertId,
                    'product_name' => $data['name'],  // Assuming 'name' exists in $data
                    'directory' => $directory,
                ];
                $imageInsertionResult = multipleImageInsert($imageData);
                if (!$imageInsertionResult) {
                    throw new Exception("Failed to insert images.");
                }
            }

            if(!empty($imageToDelete)){
                $deleteMultipleImage = deleteMultipleImage($imageToDelete, $directory);
                if (!$deleteMultipleImage) {
                    throw new Exception("Failed to delete images.");
                }
            }
            // Commit the transaction
            mysqli_commit($conn);

            return $result;

        } catch (Exception $e) {
            // Rollback the transaction
            mysqli_rollback($conn);
            return false; // You may also handle the exception or log the error
        }
    }

    
    function deleteMultipleImage($imageToDelete, $directory){
        global $conn;
        $allDeleted = true;  // Flag to check if all images were deleted successfully
        foreach($imageToDelete as $imagePath){
            // Delete image from directory
            $imageDeleteFromDirectory = deleteSingleImageFromDirectory($imagePath,$directory);
            if ($imageDeleteFromDirectory) {
                // Delete image record from database
                $imageDeleteFromDatabaseTable = deleteSingleImageFromDatabaseTable($imagePath, $directory, $conn);
                if (!$imageDeleteFromDatabaseTable) {
                    $allDeleted = false;
                }
            } else {
                $allDeleted = false;
            }
        }
        return $allDeleted;
    }

    
    function deleteSingleImageFromDirectory($imagePath,$directory){

        $image = '../assets/uploads/'.$directory.'/'.$imagePath;

        if(file_exists($image)){
            return unlink($image);
        }else{
            return false;
        }
    }


    
    function deleteSingleImageFromDatabaseTable($imagePath,$directory,$conn ){
    //    $deleteQuery = "DELETE FROM photos WHERE file = '$imagePath'";
    //    $result = mysqli_query($conn, $deleteQuery);

        // Using a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM photos WHERE file = ?");
        $stmt->bind_param("s", $imagePath);
        $result = $stmt->execute();

        if (!$result) {
            return false;
        }
        return $result;
    }

    
    function multipleImageInsert($data){

        $allImagesInserted = true;

        for( $i=0; $i<count($data['images']['name']); $i++){
                $image = [
                    'image_name' => $data['images']['name'][$i],
                    'tmp_name' => $data['images']['tmp_name'][$i],
                ];
              //  debug(" debug  from multipleImageInsert :".$data['images']['name'][$i] ."   and   ".$data['images']['tmp_name'][$i] );
            $image_path =   imageInsert($image, $data['directory'], $data['product_name'], $i);
            if($image_path){                                     // last change
                $imageData  = [
                    'product_id' => $data['product_id'],
                    'file' => $image_path,
//                'created_at' => date("Y-m-d H:i:s")
                ];
                $result = insert('photos', $imageData);
                if (!$result) {
                    $allImagesInserted = false;
                }
            }

        }


//        foreach($data['images'] as $image){
//
//            debug(" debug  from multipleImageInsert :".$image['name']);
//
//            $image_path =   imageInsert($image, $data['directory'], $data['product_name']);
//
//            $imageData  = [
//                'product_id' => $data['product_id'],
//                'file' => $image_path,
////                'created_at' => date("Y-m-d H:i:s")
//            ];
//            $result = insert('photos', $imageData);
//            if (!$result) {
//                $allImagesInserted = false;
//            }
//        }



        return $allImagesInserted;
    }
    /**
     *      insert an image in directory
     */
    function imageInsert($image, $directory, $name, $i){
        $i += 1;
     //   debug("image : ".$image['name']);
        $path               = '../assets/uploads/'.$directory;
        $image_extension    = pathinfo($image['image_name'], PATHINFO_EXTENSION);
        if(!$image_extension){
            return false;
        }
        $unique_string      = md5(time());
        $filename           =  $i.'-'.$unique_string.'.'.$image_extension;
        move_uploaded_file($image['tmp_name'], $path."/".$filename);
        $final_image        = "assets/uploads/".$directory."/".$filename;

        $i += 1;
        return $filename;
    }

    function update($tableName,$id, $data ){
        global $conn;
        $table = validate($tableName);
        $id = validate($id);

        $updateDataString = "";
        foreach ($data as $column => $value) {
            $updateDataString .= $column . '=' ."'$value',";
        }
        $finalUpdateData = substr(trim($updateDataString),0,-1);

        $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";

        $result = mysqli_query($conn, $query);
            if (!$result) {
                throw new Exception("Failed to update (update()).");
            }
        return $result;
    }

    function getAll($tableName, $status = NULL){
        global $conn;
        $table = validate($tableName);
        $status = validate($status);

        if($status == 'status'){
            $query = "SELECT * FROM $table WHERE status  = '0'";
        }else{
            $query = "SELECT * FROM $table" ;
        }

        return mysqli_query($conn, $query);
    }

    function getById($tableName, $id)
    {
        global $conn;
        $table = validate($tableName);
        $id = validate($id);
        $query = "SELECT * FROM $table WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //                $row = mysqli_fetch_assoc($result);  // direct fetch and make associative array
                $response = [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record found'
                ];
                return $response;
            }else{
                $response = [
                    'status' => 404,
                    'message' => 'No record found'
                ];
                return $response;
            }
        }else{
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
            return $response;
        }
    }

    function getByColumn($tableName, $columnName, $value)
{
    global $conn;
    $table = validate($tableName);
    $column = validate($columnName);
    $value = validate($value);

    $query = "SELECT * FROM $table WHERE $column = '$value' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //                $row = mysqli_fetch_assoc($result);  // direct fetch and make associative array
            $response = [
                'status' => 200,
                'data' => $row,
                'message' => 'Record found'
            ];
            return $response;
        }else{
            $response = [
                'status' => 404,
                'message' => 'No record found'
            ];
            return $response;
        }
    }else{
        $response = [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
        return $response;
    }
}

    function getMultipleTableData($childTable, $parentTable, $childColumn, $parentColumn, $orderByColumn, $column = null,$serchBy = null){
        global $conn;
        $c_table        = validate($childTable);
        $p_table        = validate($parentTable);
        $c_column       = validate($childColumn);
        $p_column       = validate($parentColumn);
        $order_ByColumn  = validate($orderByColumn);
        $aColumn         = validate($column);
        $searchByColumn        = validate($serchBy);

//        if(($serchBy != null && $serchBy != '') && ($aColumn != null && $aColumn != '')){...} and  if(!empty($searchByColumn) && !empty($aColumn)){...}

            if(!empty($searchByColumn) && !empty($aColumn)){

            $query = "SELECT $c_table.*, $p_table.*  FROM $c_table, $p_table WHERE $c_table.$c_column = $p_table.$p_column AND $aColumn = $searchByColumn  ORDER BY $p_table.$order_ByColumn";


        }else{
            $query = "SELECT $c_table.*, $p_table.*  FROM $c_table, $p_table WHERE $c_table.$c_column = $p_table.$p_column ORDER BY $p_table.$order_ByColumn";
        }

        $result = mysqli_query($conn, $query);

        if($result){
            if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $response = [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record found'
                ];
                return $response;
            }else{
                $response = [
                    'status' => 404,
                    'message' => 'No record found'
                ];
                return $response;
            }
        }else{
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
            return $response;
        }
    }

    function getPicturesAccordingToIds($id){
        global $conn;
        $id = validate($id);

        $query = "SELECT * FROM photos WHERE product_id = $id ";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Database query failed: " . mysqli_error($conn));
        }
        return $result;
    }

    function delete($tableName, $id, $directory = null){
        global $conn;
        $table  = validate($tableName);
        $id     = validate($id);
        // Start a transaction
        mysqli_begin_transaction($conn);
        try{
            // Fetch associated photos
            $findPhotos = getPicturesAccordingToIds($id);
            //  debug($findPhotos);
            if($findPhotos && mysqli_num_rows($findPhotos) > 0){

                $imageToDelete = [];
                while ($photo = mysqli_fetch_assoc($findPhotos)) {
                    $imageToDelete[] = $photo['file'];
                }
                // Delete photos from the directory and database
                $deleteMultipleImage = deleteMultipleImage($imageToDelete, $directory);
                if (!$deleteMultipleImage) {
                    throw new Exception("Failed to delete images.");
                }
            }
            // Delete the product from the table
            $query = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
            $result = mysqli_query($conn, $query);
           // debug($result);
            if (!$result) {
                throw new Exception("Database query failed: " . mysqli_error($conn));
            }
            // Commit the transaction
            mysqli_commit($conn);
            return true;
        }catch (Exception $e) {
            // Rollback the transaction
            mysqli_rollback($conn);
            $_SESSION['exception'] = $e->getMessage();
            return false; // You may also handle the exception or log the error
        }
        //return $result;
    }

    function normalDelete($tableName, $id){
        global $conn;
        $table  = validate($tableName);
        $id     = validate($id);
        // Delete the product from the table
        $query = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            return false;
        }
        return true;
    }

    function checkParamId($type){
        if(isset($_GET[$type])){
            if($_GET[$type] != ''){
                return $_GET[$type];
            }else{
                return '<h5>No ID Found</h5>';
            }
        }else{
            return '<h5>No ID Given</h5>';
        }
    }



/**
 *  Multiple table join query
 *
 *      Query will look like this :
 *
 *      $Query = SELECT     orders.*,   customers.*,    products.*     FROM orders
 *
 *              INNER JOIN customers ON orders.customer_id = customers.id
 *              INNER JOIN products ON orders.product_id = products.id
 *
 *              WHERE orders.order_status = "completed" AND customers.phone IS NOT NULL
 *
 *              ORDER BY orders.order_date DESC
 *
 *              LIMIT 10;
 *
 *
 *   # to make this query  I have to pass
 *                                      -> $tables = []
 *                                      -> $joins  = []
 *                                      -> $conditions = []
 *                                      -> $orderBySpecificColumnName = ''
 *                                      -> $limitOfRows = number
 *
 *
 *      # $tables = []
 *          After SELECT operator, using implode() and give comma (,) as parameter for separate tables name. you also have to give '.*' by concat with table, means select all columns from a table
 *
 *      # $joins  = []
 *          Use foreach() or for() loop,  In for() or foreach() loop use INNER JOIN operator, then concat the $joins= [] arrays values.
 *
 *      # $conditions = []
 *          After WHERE operator, using implode() and give and operator ( AND ) as parameter for separate conditions
 *
 *      # $orderBySpecificColumnName = ''
 *          After ORDER BY operator, concat $orderBySpecificColumnName value
 *
 *      # $limitOfRows = number
 *          After LIMIT operator concat $limitOfRows value.
 *
 *
 *
 *  # provide table names in an array. e.g, $tables = ['orders', 'customers', 'products'];
 *
 *  #  provide join query which columns (value) is come from which foreign table column (value)
 *     (e.g,
 *          $joins = [
 *               'customers ON orders.customer_id = customers.id',
 *               'products ON orders.product_id = products.id'
 *           ];
 *      )
 *  #   provide condition
 *          (e.g,
 *              $conditions = [
 *                              'orders.order_status = "completed"',
 *                              'customers.phone IS NOT NULL'
 *                          ];
 *          );
 *
 *  # provide orderBy
 *              e.g, (
 *                  $orderBy = 'orders.order_date DESC';
 *              )
 * # provide limit
 *      e. g, (
 *          $limit = 10;
 *      )
 */
    function fetchAllFromMultipleTable($tables = [], $joins =[], $conditions = [], $orderBy = null, $limit = null){
        global $conn;
        $validatedTables        = array_map('validate', $tables);
        $validatedJoins         = is_array($joins) ? array_map('validate', $joins) : '';
        $validatedConditions    = array_map('validate', $conditions);
        $validatedOrderBy       = validate($orderBy);
        $validatedLimit         = validate($limit);
//
//        echo "<br>TABLE : ";
//        debug($tables);
//
//
//        echo "<br>Validated table : ";
//        debug($validatedTables);



        $query = '';

        if($validatedTables){
            if(count($validatedTables) > 1){
                $query .= "SELECT ". implode('.*, ', $validatedTables ). ".* FROM ". $validatedTables[0];
            }
        }

//        if($validatedTables){
//            if(count($validatedTables) > 1){
//                $selectColumns = [];
//                foreach ($validatedTables as $index => $table) {
//                    $alias = "t{$index}"; // Unique alias like t0, t1, t2...
//                    $selectColumns[] = "{$alias}.*";
//                    $validatedTables[$index] .= " AS {$alias}";
//                }
//                $query .= "SELECT " . implode(', ', $selectColumns) . " FROM " . $validatedTables[0];
//            } else {
//                $query .= "SELECT * FROM " . $validatedTables[0];
//            }
//        }

        if(!empty($validatedJoins)) {
            foreach ($validatedJoins as $join) {
                $query .= " INNER JOIN $join ";
            }
        }

        if($validatedConditions) {
            if(count($validatedConditions) > 1){
                $query .= " WHERE " . implode(' AND ', $validatedConditions);
            }else{
                $query .= " WHERE " . $validatedConditions[0];
            }


        }
        if($validatedOrderBy) {
            $query .= " ORDER BY ". $validatedOrderBy;
        }
        if($validatedLimit) {
            $query .= " LIMIT ". $validatedLimit;
        }

        echo "From fetchAllFromMultipleTable() : ";
            debug($query);

        $result = mysqli_query($conn, $query);

        if($result){
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $response = [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record found'
                ];
                return $response;
            }else{
                $response = [
                    'status' => 404,
                    'message' => 'No record found'
                ];
                return $response;
            }
        }else{
            $response = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
            return $response;
        }


        
    }




//
//function getMultipleTableData($tables, $joins, $orderByColumn = null, $conditions = null) {
//    global $conn;
//
//    // Validate and initialize variables
//    $validatedTables = array_map('validate', $tables);
//    $validatedJoins = array_map('validate', $joins);
//    $order_ByColumn = $orderByColumn ? validate($orderByColumn) : null;
//    $whereClause = '';
//
//    // Construct the base query with the first table
//    $query = "SELECT " . implode(".*, ", $validatedTables) . ".* FROM " . $validatedTables[0];
//
//    // Construct the JOIN clauses
//    for ($i = 1; $i < count($validatedTables); $i++) {
//        $query .= " INNER JOIN " . $validatedTables[$i] . " ON " . $validatedJoins[$i-1];
//    }
//
//    // Add WHERE conditions if provided
//    if ($conditions && is_array($conditions)) {
//        $validatedConditions = array_map('validate', $conditions);
//        $whereClause = " WHERE " . implode(" AND ", $validatedConditions);
//        $query .= $whereClause;
//    }
//
//    // Add ORDER BY clause if provided
//    if ($order_ByColumn) {
//        $query .= " ORDER BY $order_ByColumn";
//    }
//
//    // Execute the query
//    $result = mysqli_query($conn, $query);
//
//    // Handle the result
//    if ($result) {
//        if (mysqli_num_rows($result) > 0) {
//            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
//            $response = [
//                'status' => 200,
//                'data' => $rows,
//                'message' => 'Records found'
//            ];
//        } else {
//            $response = [
//                'status' => 404,
//                'message' => 'No records found'
//            ];
//        }
//    } else {
//        $response = [
//            'status' => 500,
//            'message' => 'Something went wrong with the query'
//        ];
//    }
//
//    return $response;
//}
//
///**
// *  How to Use This Function:
// *
// *  Example 1: Joining Two Tables
// *
// *  $tables = ['orders', 'customers'];
// *  $joins = ['orders.customer_id = customers.id'];
// *  $orderByColumn = 'orders.id';
// *  $conditions = ['customers.status = "active"'];
// *
// *  $result = getMultipleTableData($tables, $joins, $orderByColumn, $conditions);
// *
// *
// *
// *
// *
// *  Example 2: Joining Three Tables
// *
// *  $tables = ['orders', 'customers', 'products'];
// *  $joins = [
// *      'orders.customer_id = customers.id',
// *      'orders.product_id = products.id'
// *  ];
// *  $orderByColumn = 'orders.id';
// *  $conditions = ['customers.status = "active"', 'products.available = 1'];
// *
// * $result = getMultipleTableData($tables, $joins, $orderByColumn, $conditions);
// *
//
// *
// */
//
//    function getMultipleTableData($tables, $joins = [], $columns = ['*'], $conditions = [], $orderBy = null, $limit = null) {
//        global $conn;
//
//        // Validate and sanitize input
//        $validatedTables = array_map('validate', $tables);
//        $validatedColumns = array_map('validate', $columns);
//        $validatedJoins = array_map('validate', $joins);
//        $validatedConditions = array_map('validate', $conditions);
//        $orderByClause = $orderBy ? " ORDER BY " . validate($orderBy) : '';
//        $limitClause = $limit ? " LIMIT " . intval($limit) : '';
//
//        // Construct SELECT clause
//        $selectColumns = implode(', ', $validatedColumns);
//        $query = "SELECT $selectColumns FROM " . $validatedTables[0];
//
//        // Construct JOIN clauses
//        foreach ($validatedJoins as $join) {
//            $query .= " INNER JOIN $join";
//        }
//
//        // Add WHERE conditions if provided
//        if (!empty($validatedConditions)) {
//            $query .= " WHERE " . implode(" AND ", $validatedConditions);
//        }
//
//        // Add ORDER BY and LIMIT clauses if provided
//        $query .= $orderByClause . $limitClause;
//
//        // Execute the query
//        $result = mysqli_query($conn, $query);
//
//        // Handle the result
//        if ($result) {
//            if (mysqli_num_rows($result) > 0) {
//                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
//                return [
//                    'status' => 200,
//                    'data' => $rows,
//                    'message' => 'Records found'
//                ];
//            } else {
//                return [
//                    'status' => 404,
//                    'message' => 'No records found'
//                ];
//            }
//        } else {
//            return [
//                'status' => 500,
//                'message' => 'Something went wrong with the query'
//            ];
//        }
//    }
//
//
///**
// *
//     * $tables = ['orders', 'customers', 'products'];
//     * $joins = [
//     *              'customers ON orders.customer_id = customers.id',
//     *              'products ON orders.product_id = products.id'
//     *          ];
//     * $columns = ['orders.id', 'customers.name', 'products.price'];
//     * $conditions = [
//     *                  'orders.order_status = "completed"',
//     *                  'customers.phone IS NOT NULL'
//     *              ];
//     * $orderBy = 'orders.order_date DESC';
//     * $limit = 10;
//     *
//     * $result = getMultipleTableData($tables, $joins, $columns, $conditions, $orderBy, $limit);
// */
//
//
//

    function orderItemQuery($query){
        global $conn;
        $result = mysqli_query($conn, $query);
        if($result){
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record found'
                ];
            }else{
                return [
                    'status' => 404,
                    'message' => 'No record found'
                ];
            }
        }else{
            return [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        }
    }




    function getCount($tableName){
        global $conn;

        $table  = validate($tableName);

        $query = "SELECT * FROM $table";

        $result = mysqli_query($conn, $query);

        if($result){
            if (mysqli_num_rows($result) > 0) {
                $total_count = mysqli_num_rows($result);
                return $total_count;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
?>
