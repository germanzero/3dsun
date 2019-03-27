<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Image {

    private $cnx = null;
    private $util = null;
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }

  
    /**
     * 
     */
    function no_ext(){
        return reset(explode('.', $img));
    }

    /**
     * 
     * Generate Thumbnail using Imagick class
     *  
     * @param string $img
     * @param string $width
     * @param string $height
     * @param int $quality
     * @return boolean on true
     * @throws Exception
     * @throws ImagickException
     */
    function generateThumbnail($img, $width, $height, $quality = 90)
    {
        if (!extension_loaded('imagick')){
            if (is_file($img)) {
                $imagick = new Imagick(realpath($img));
                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
                $imagick->setImageCompressionQuality($quality);
                $imagick->thumbnailImage($width, $height, false, false);
                $filename_no_ext = $this->no_ext($img);//reset(explode('.', $img));
                if (file_put_contents($filename_no_ext . '_' . $width . 'x' . $height . '.jpg', $imagick) === false) {
                    throw new Exception("Could not put contents.");
                }
                return true;
            }
            else {
                throw new Exception("No valid image provided with {$img}.");
            }
        }else {
            throw new Exception("Missing Imagick....");
        }
    
       
    }

    /*
     * 
     */
    function makeThumbnails($updir, $img, $id)
    {
        $thumbnail_width = 134;
        $thumbnail_height = 189;
        $thumb_beforeword = "thumb";
        $arr_image_details = getimagesize("$updir" . $id . '_' . "$img"); // pass id to thumb name
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);
        if ($arr_image_details[2] == IMAGETYPE_GIF) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == IMAGETYPE_JPEG) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == IMAGETYPE_PNG) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom("$updir" . $id . '_' . "$img");
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, "$updir" . $id . '_' . "$thumb_beforeword" . "$img");
        }
    }

}