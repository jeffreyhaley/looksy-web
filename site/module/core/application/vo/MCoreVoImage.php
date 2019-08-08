<?php
/**
 * Table Image
 **/
class MCoreVoImage extends FDatabaseVoTable
{    
    /* var string */
    protected $imageId;
    
    /* var string */
    protected $imageName;
    
    /* var string */
    protected $imageType;
    
    /* var integer */
    protected $imageOrigWidth;
    
    /* var integer */
    protected $imageOrigHeight;
    
    /* var object */
    protected $image;

    /* var string */
    protected $imageDetail;
    
    /* var string */
    protected $imageEntityType;
    
    /* var string */
    protected $imageSrc;
    
    /* var string */
    protected $imageRotationDeg;

    
    // Constants used for Validation and reference.
    const IMAGEID = 'ImageId';
    const IMAGENAME = 'ImageName';
    const IMAGEORGWIDTH = 'ImageOrgWidth';
    const IMAGEORGHEIGHT = 'ImageOrgHeight';
    const IMAGE = 'Image';
    const IMAGESRC = 'ImageSrc';
    const IMAGEDETAIL = 'ImageDetail';
    const IMAGEENTITYTYPE = 'ImageEntityType';
    
    const IMG_DETAIL = 0;
    const IMG_SRC = 1;
   
    
    /**
     * @return string
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * 
     * @param string $imageId
     */
    public function setImageId($imageId)
    {
        if (is_string($imageId) && !empty($imageId))
        {
            $this->setValidationById(self::IMAGEID, 'ImageId is valid', FWebVoValidation::SUCCESS);
            $this->imageId = $imageId;
        }
        else
        {
            $this->setValidationById(self::IMAGEID, 'ImageId is not valid', FWebVoValidation::ERROR);
            error_log('ImageId is not valid');
        }
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * 
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        if (is_string($imageName) && !empty($imageName))
        {
            $this->setValidationById(self::IMAGENAME, 'Image Name is valid', FWebVoValidation::SUCCESS);
            $this->imageName = $imageName;
        }
        else
        {
            $this->setValidationById(self::IMAGENAME, 'Image Name is not valid', FWebVoValidation::ERROR);
            error_log('Image Name is not valid');
        }        
    }

    /**
     * @return int
     */
    public function getImageOrigWidth()
    {
        return $this->imageOrigWidth;
    }

    /**
     * 
     * @param int $imageOrigWidth
     */
    public function setImageOrigWidth($imageOrigWidth)
    {
        if (is_int($imageOrigWidth) && !empty($imageOrigWidth))
        {
            $this->setValidationById(self::IMAGEID, 'Image Width is valid', FWebVoValidation::SUCCESS);
            $this->imageOrigWidth = $imageOrigWidth;
        }
        else
        {
            $this->setValidationById(self::IMAGEID, 'Image Width is not valid', FWebVoValidation::ERROR);
            error_log('Image Width is not valid');
        }
    }

    /**
     * @return int
     */
    public function getImageOrigHeight()
    {
        return $this->imageOrigHeight;
    }

    /**
     * 
     * @param int $imageOrigHeight
     */
    public function setImageOrigHeight($imageOrigHeight)
    {
        if (is_int($imageOrigHeight) && !empty($imageOrigHeight))
        {
            $this->setValidationById(self::IMAGEID, 'Image Height is valid', FWebVoValidation::SUCCESS);
            $this->imageOrigHeight = $imageOrigHeight;
        }
        else
        {
            $this->setValidationById(self::IMAGEID, 'Image Height is not valid', FWebVoValidation::ERROR);
            error_log('Image Height is not valid');
        }
    }
    
    /**
     * @return int
     */
    public function getImageRotationDeg()
    {
        return $this->imageRotationDeg;
    }
    
    /**
     *
     * @param int $imageOrigHeight
     */
    public function setImageRotationDeg($deg)
    {
        if (is_int($deg) && !empty($deg))
        {
            $this->setValidationById(self::IMAGEID, '', FWebVoValidation::SUCCESS);
            $this->imageRotationDeg = $deg;
        }
        else
        {
            $this->setValidationById(self::IMAGEID, '', FWebVoValidation::ERROR);
            error_log('Rotation degrees is not valid: ' . $deg);
        }
    }
    
    /**
     *
     * @param object $imageSrc
     */
    public function setImage($image)
    {
        if (is_object($image) && !empty($image))
        {
            // Get the image properties
            $imagepair = explode(',', $image->src);
            $imageDetail = $imagepair[self::IMG_DETAIL];
            $imageSrc = base64_decode($imagepair[self::IMG_SRC]);
            list($width, $height, $type, $attr) = getimagesizefromstring($imageSrc);         

            // Set the properties
            $this->setImageSrc($imageSrc);
            $this->setImageDetail($imageDetail);
            $this->setImageOrigHeight($height);
            $this->setImageOrigWidth($width);
            $this->imageType = image_type_to_mime_type($type);
            
            $this->setValidationById(self::IMAGE, 'Image Object is valid', FWebVoValidation::SUCCESS);
            $this->image = $image;
        }
        else
        {
            $this->setValidationById(self::IMAGE, 'Image Object is not valid', FWebVoValidation::ERROR);
            error_log('Image Object is not valid');
        }
    }
    
    /**
     * @return object
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     *
     * @param string $imageSrc
     */
    public function setImageSrc($imageSrc)
    {    
        if (is_string($imageSrc) && !empty($imageSrc))
        {         
            $this->setValidationById(self::IMAGESRC, 'Image Source is valid', FWebVoValidation::SUCCESS);
            $this->imageSrc = $imageSrc;
        }
        else
        {
            $this->setValidationById(self::IMAGESRC, 'Image Source is not valid', FWebVoValidation::ERROR);
            error_log('Image Source is not valid');
        }
    }
    
    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->imageSrc;
    }
    
    /**
     *
     * @param string $imageDetail
     */
    public function setImageDetail($imageDetail)
    {
        if (is_string($imageDetail) && !empty($imageDetail))
        {
            $this->setValidationById(self::IMAGEDETAIL, 'Image Detail is valid', FWebVoValidation::SUCCESS);
            $this->imageDetail = $imageDetail;
        }
        else
        {
            $this->setValidationById(self::IMAGEDETAIL, 'Image Detail is not valid', FWebVoValidation::ERROR);
            error_log('Image Detail is not valid');
        }
    }
    
    /**
     * @return string
     */
    public function getImageDetail()
    {
        return $this->imageDetail;
    }    
    
    /**
     *
     * @param string $imageEntityType
     */
    public function setImageEntityType($imageEntityType)
    {
        if (is_string($imageEntityType) && !empty($imageEntityType))
        {
            $this->setValidationById(self::IMAGEENTITYTYPE, 'Image Entity Type is valid', FWebVoValidation::SUCCESS);
            $this->imageEntityType = $imageEntityType;
        }
        else
        {
            $this->setValidationById(self::IMAGEENTITYTYPE, 'Image Entity Type is not valid', FWebVoValidation::ERROR);
            error_log('Image Entity Type is not valid');
        }
    }
    
    /**
     * @return string
     */
    public function getImageEntityType()
    {
        return $this->imageEntityType;
    }    
    
    public function getImageType() {
        return $this->imageType;
    }
    
}