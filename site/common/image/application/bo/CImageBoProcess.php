<?php

class CImageBoProcess
{
    const IMG_DETAIL = 0;
    const IMG_SRC = 1;
    const IMG_LARGE_WIDTH = 800;
    const IMG_MEDIUM_WIDTH = 600;
    const IMG_SMALL_WIDTH = 300;
    const IMG_ORIG_WIDTH = 'origWidth';
    const IMG_ORIG_HEIGHT = 'origHeight';

    const IMG_SMALL = 1;
    const IMG_MEDIUM = 2;
    const IMG_LARGE = 3;
    const TYPE_LOCATION = 'l';
    const TYPE_TILE = 't';


    /**
     * Convert an existing mime-type to a valid image file extension
     * @param string $imageMimeType
     * @return string
     */
    private static function mimeTypeToFileExtension($imageMimeType) {
        $ext = "no_clue";
        switch ($imageMimeType) {
        	case 'image/jpeg':
        	case 'image/jpg':
        	    $ext = "jpg";
        	    break;
        	case 'image/gif':
        	    $ext = "gif";
        	    break;
        	case 'image/png':
        	    $ext = "png";
        	    break;
        	case 'image/bmp':
        	    $ext = "bmp";
        	    break;
        }
        return $ext;
    }

    /**
     * Generate a unique image name without extension.
     * @param unknown $imageEntityType
     * @param string $imageMimeType
     * @return string
     */
    private static function generateImageName($imageEntityType)
    {
        $userEmail = FWebVoRequest::getUserEmail();
        $uniqueId = uniqid('', true);
        $imageName = $imageEntityType . $uniqueId . ".jpg";
        
        return $imageName;
    }

    /**
     * Save image to disk
     * 
     * @param MCoreVoImage $data
     * @return image info array or null
     */
    public static function saveImage(MCoreVoImage $data)
    {               
//         if (!file_exists(Config::IMG_PATH . '/original')) {
//             mkdir(Config::IMG_PATH . '/original', 0777, true);
//         }
//         if (!file_exists(Config::IMG_PATH . '/large')) {
//             mkdir(Config::IMG_PATH . '/large', 0777, true);
//         }
//         if (!file_exists(Config::IMG_PATH . '/medium')) {
//             mkdir(Config::IMG_PATH . '/medium', 0777, true);
//         }
//         if (!file_exists(Config::IMG_PATH . '/small')) {
//             mkdir(Config::IMG_PATH . '/small', 0777, true);
//         }

        // get the raw image
        $imageRaw = imagecreatefromstring($data->getImageSrc());
        
        // rotate accordignly
        $imageRaw = imagerotate($imageRaw, -$data->getImageRotationDeg(), 1);
        
        // figure out if we need to reverse the width and the height
        $origImageWidth = 0;
        $origImageHeight = 0;
        if ($data->getImageRotationDeg() % 180 == 0) {
            // no reversal necessary
            $origImageWidth = $data->getImageOrigWidth();
            $origImageHeight = $data->getImageOrigHeight();
        } else {
            // reverse, bitch
            $origImageWidth = $data->getImageOrigHeight();
            $origImageHeight = $data->getImageOrigWidth();
        }
        
        if ($imageRaw)
        {
            // grab an image name (without its extension)
            $imageName = CImageBoProcess::generateImageName($data->getImageEntityType());
            
            // create the original image and grab the original dimensions
            $origImgPname = Config::IMG_PATH . "/original/$imageName";
            
            // remove transparency, where needed
            switch ($data->getImageType()) {
            	case 'image/jpeg':
            	case 'image/jpg':
        	    case 'image/bmp':
            	    $imgSaveOk = imagejpeg($imageRaw, $origImgPname, 100);
            	    break;
            	case 'image/gif':
            	case 'image/png':
            	    $oW = $origImageWidth;
            	    $oH = $origImageHeight;
            	    
            	    $newImg = imagecreatetruecolor($oW, $oH);
            	    $transparent = imagecolorallocate($newImg, 255, 255, 255);
            	    imagefilledrectangle($newImg, 0, 0, $oW, $oH, $transparent);
            	    imagecopyresampled($newImg, $imageRaw, 0, 0, 0, 0, $oW, $oH, $oW, $oH);
            	    $imgSaveOk = imagejpeg($newImg, $origImgPname, 100);
            	    imagedestroy($newImg);
            	    break;
            }
            
            // generate new image name
            $data->setImageName($imageName);
            
            // Now create the scaled images
            if ($imgSaveOk)
            {   
                // Save the Image and set the ImageId
                $imageId = CImageDaoProcess::insertImage($imageName, $origImageWidth, $origImageHeight);
                $data->setImageId($imageId);
                
                // Create a small scaled image
                if (!CImageBoProcess::saveScaledImage($origImgPname, $imageName, self::IMG_SMALL, $origImageWidth, $origImageHeight))
                {
                     error_log("Error creating small image for path: $origImgPname");
                }
                
                // Create a medium scaled image
                if (!CImageBoProcess::saveScaledImage($origImgPname, $imageName, self::IMG_MEDIUM, $origImageWidth, $origImageHeight))
                {
                    error_log("Error creating medium image for path: $origImgPname");
                }
                
                // Create a large scaled image
                if (!CImageBoProcess::saveScaledImage($origImgPname, $imageName, self::IMG_LARGE, $origImageWidth, $origImageHeight))
                {
                   error_log("Error creating large image for path: $origImgPname");
                }            
            }
            else 
            {
                error_log("Error creating image for path: $origImgPname");
            }
        }
        else
        {
            error_log("Could not create image from string. Oops.");
        }
        
        return $data;
    }

    /**
     * Save scaled image to disk
     * 
     * @param string $origImgPname
     *            path to original, unscaled file
     * @param string $imgName
     *            name of new image
     * @param int $imgSize
     *            one of IMG_SMALL, IMG_MEDIUM, IMG_LARGE
     * @param int $origWidth
     *            original image width
     * @param int $origHeight
     *            original image height
     * @return boolean true if succeeded, else false
     */
    private static function saveScaledImage($origImgPname, $imgName, $imgSize, $origWidth, $origHeight)
    {
        // figure out image sizing
        $imgMidPath = "";
        $newWidth = 0;
        
        switch ($imgSize)
        {
            case self::IMG_SMALL:
                $imgMidPath = "small";
                $newWidth = self::IMG_SMALL_WIDTH;
                break;
            case self::IMG_MEDIUM:
                $imgMidPath = "medium";
                $newWidth = self::IMG_MEDIUM_WIDTH;
                break;
            case self::IMG_LARGE:
                $imgMidPath = "large";
                $newWidth = self::IMG_LARGE_WIDTH;
                break;
        }
        $newHeight = ($newWidth * $origHeight) / $origWidth;
        
        // don't stretch images that are already smaller than the minimum
        if ($origWidth < $newWidth && $origHeight < $newHeight)
        {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }
        
        // figure out image path
        $newImgPname = Config::IMG_PATH . "/$imgMidPath/" . $imgName;
        
        // create the canvas and load up the original image
        $scaledImg = imagecreatetruecolor($newWidth, $newHeight);
        $origImg = imagecreatefromjpeg($origImgPname);
        
        // rescale the image (imagecopyresampled is preferred to imagecopyresized)
        $resampledOk = imagecopyresampled($scaledImg, $origImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // save the jpeg
        $jpegSaveOk = imagejpeg($scaledImg, $newImgPname);
        
        // free up memory
        imagedestroy($scaledImg);
        imagedestroy($origImg);
        
        // check for errors and bail
        if (!$resampledOk || !$jpegSaveOk)
        {
            error_log("Error creating image for path: $newImgPname");
            return false;
        }
        
        return true;
    }
}