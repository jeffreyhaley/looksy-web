<?php

class CImageDaoProcess
{

    /**
     * Inserts the supplied image into the database
     * 
     * Take in the image name and size, inserts into the Image table
     * and returns the inserted id.
     * 
     * @param string $imageName            
     * @param int $origWidth            
     * @param int $origHeight            
     * @return int|false
     */
    public static function insertImage($imageName, $origWidth, $origHeight)
    {
        $pdo = FDatabaseBoSingleton::Instance();
        
        // Initialize the Database Result object
        $daoResult = new FDatabaseVoResult();
        
        $sql = <<<SQL
INSERT INTO Image
(
    ImageName,
    ImageOrigWidth,
    ImageOrigHeight
)
VALUES
(
	:imageName,
	:imageOrigWidth,
	:imageOrigHeight
);
SQL;
        
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute(array(
            ':imageName' => $imageName,
            ':imageOrigWidth' => $origWidth,
            ':imageOrigHeight' => $origHeight
        ));
          
        // Check the status and return the results.
        if ($status)
        {
            $result = $pdo->lastInsertId();          
        }
        else
        {
            error_log(print_r($stmt->errorInfo(), 1));
            $result = false;
        }
        
        return $result;
    }
    
    /**
     * Updates the supplied image into the database
     *
     * Take in the imageid and size, updates into the Image table
     * and returns success or failure.
     *
     * @param int $imageId
     * @param int $origWidth
     * @param int $origHeight
     * @return bool
     */
//     public static function updateImage($imageId, $origWidth, $origHeight)
//     {
//         $pdo = FDatabaseBoSingleton::Instance();
    
//         // Initialize the Database Result object
//         $daoResult = new FDatabaseVoResult();
    
//         $sql = <<<SQL
// UPDATE Image
// SET ImageName = :imageName
//     ImageOrigWidth = :imageOrigWidth
//     ImageOrigHeight = :imageOrigHeight
// WHERE ImageId = :imageId;
// SQL;
    
//         $stmt = $pdo->prepare($sql);
//         $status = $stmt->execute(array(
//             ':imageId' => $imageId,
//             ':imageName' => $imageName,
//             ':imageOrigWidth' => $origWidth,
//             ':imageOrigHeight' => $origHeight
//         ));
    
//         // Check the status and return the results.
//         if ($status)
//         {
//             $result = true;
//         }
//         else
//         {
//             error_log(print_r($stmt->errorInfo(), 1));
//             $result = false;
//         }
    
//         return $result;
//     }
    
    /**
     * Updates the supplied image into the database
     *
     * Take in the imageid and size, updates into the Image table
     * and returns success or failure.
     *
     * @param int $imageId
     * @param int $origWidth
     * @param int $origHeight
     * @return bool
     */
//     public static function readImageId($locationId, $origWidth, $origHeight)
//     {
//         $pdo = FDatabaseBoSingleton::Instance();
    
//         // Initialize the Database Result object
//         $daoResult = new FDatabaseVoResult();
    
//         $sql = <<<SQL
// UPDATE Image
// SET ImageOrigWidth = :imageOrigWidth
//     ImageOrigHeight = :imageOrigHeight
// WHERE ImageId = :imageId;
// SQL;
    
//         $stmt = $pdo->prepare($sql);
//         $status = $stmt->execute(array(
//             ':imageId' => $imageName,
//             ':imageOrigWidth' => $origWidth,
//             ':imageOrigHeight' => $origHeight
//         ));
    
//         // Check the status and return the results.
//         if ($status)
//         {
//             $result = true;
//         }
//         else
//         {
//             error_log(print_r($stmt->errorInfo(), 1));
//             $result = false;
//         }
    
//         return $result;
//     }
    
}