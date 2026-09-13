<?php
class imageGrid

{



    private $realWidth;

    private $realHeight;

    private $gridWidth;

    private $gridHeight;

    private $image;



    public function __construct($realWidth, $realHeight, $gridWidth, $gridHeight)

    {

        $this->realWidth = $realWidth;

        $this->realHeight = $realHeight;

        $this->gridWidth = $gridWidth;

        $this->gridHeight = $gridHeight;



        // create destination image

        $this->image = imagecreatetruecolor($realWidth, $realHeight);



        // set image default background

        $white = imagecolorallocate($this->image, 0, 0, 0);

        imagefill($this->image, 0, 0, $white);

    }



    public function __destruct()

    {

        imagedestroy($this->image);

    }



    public function display($filename)

    {

        header("Content-type: image/jpeg");

       imagejpeg($this->image,$filename);

       // imagejpeg($this->image);

    }

    

   

   

    

public function demoGrid()

{

    $black = imagecolorallocate($this->image, 0, 0, 0);

    imagesetthickness($this->image, 3);

    $cellWidth = ($this->realWidth - 1) / $this->gridWidth;   // note: -1 to avoid writting

    $cellHeight = ($this->realHeight - 1) / $this->gridHeight; // a pixel outside the image

    for ($x = 0; ($x <= $this->gridWidth); $x++)

    {

        for ($y = 0; ($y <= $this->gridHeight); $y++)

        {

            imageline($this->image, ($x * $cellWidth), 0, ($x * $cellWidth), $this->realHeight, $black);

            imageline($this->image, 0, ($y * $cellHeight), $this->realWidth, ($y * $cellHeight), $black);

        }

    }

}

public function demoPutSquare($sizeW, $sizeH, $posX, $posY)

{

    // Cell width

    $cellWidth = $this->realWidth / $this->gridWidth;

    $cellHeight = $this->realHeight / $this->gridHeight;



    // Conversion of our virtual sizes/positions to real ones

    $realSizeW = ($cellWidth * $sizeW);

    $realSizeH = ($cellHeight * $sizeH);

    $realPosX = ($cellWidth * $posX);

    $realPosY = ($cellHeight * $posY);



    // Getting top left and bottom right of our rectangle

    $topLeftX = $realPosX;

    $topLeftY = $realPosY;

    $bottomRightX = $realPosX + $realSizeW;

    $bottomRightY = $realPosY + $realSizeH;



    // Displaying rectangle

    $red = imagecolorallocate($this->image, 100, 0, 0);

    imagefilledrectangle($this->image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY, $red);

}

public function putImage($src,$img, $sizeW, $sizeH, $posX, $posY){

    // Cell width

list($widthCC, $heightCC, $typeCC, $attrCC) = getimagesize($src);	



	  $cellWidth = $this->realWidth / $this->gridWidth;

    $cellHeight = $this->realHeight / 1;

  

   // Conversion of our virtual sizes/positions to real ones

	  $realSizeW = ceil($cellWidth * $sizeW);

    $realSizeH = ceil($cellHeight * $sizeH);

  

    $realPosX = ($cellWidth * $posX);

    $realPosY = ($cellHeight * $posY);



    // Copying the image

	 $img = $this->resizePreservingAspectRatio($img, $realSizeW, $realSizeH);


if($widthCC*1.3<$heightCC){
	imagecopyresampled($this->image, $img, $realPosX, $realPosY, 0, 0, 500, 320, imagesx($img), imagesy($img));
}else{
	imagecopyresampled($this->image, $img, $realPosX, $realPosY, 0, 0, 500, 320, imagesx($img), imagesy($img));
}


    

}

public function resizePreservingAspectRatio($img, $targetWidth, $targetHeight)

{

    $srcWidth = imagesx($img);

    $srcHeight = imagesy($img);



    $srcRatio = $srcWidth / $srcHeight;

    $targetRatio = $targetWidth / $targetHeight;

    if (($srcWidth <= $targetWidth) && ($srcHeight <= $targetHeight))

    {

        $imgTargetWidth = $srcWidth;

        $imgTargetHeight = $srcHeight;

    }

    else if ($targetRatio > $srcRatio)

    {

        $imgTargetWidth = (int) ($targetHeight * $srcRatio);

        $imgTargetHeight = $targetHeight;

    }

    else

    {

        $imgTargetWidth = $targetWidth;

        $imgTargetHeight = (int) ($targetWidth / $srcRatio);

    }



    $targetImg = imagecreatetruecolor($targetWidth, $targetHeight);



    imagecopyresampled(

       $targetImg,

       $img,

       ($targetWidth - $imgTargetWidth) / 2, // centered

       ($targetHeight - $imgTargetHeight) / 2, // centered

       0,

       0,

       $imgTargetWidth,

       $imgTargetHeight,

       $srcWidth,

       $srcHeight

    );



    return $targetImg;

}

}

function addlogo_2($inputimagepath,$marge_right,$marge_bottom,$watermark){
// Load the stamp and the photo to apply the watermark to
$stamp = imagecreatefrompng($watermark);
$im = imagecreatefromjpeg($inputimagepath);

// Set the margins for the stamp and get the height/width of the stamp image

$sx = imagesx($stamp);
$sy = imagesy($stamp);

// Copy the stamp image onto our photo using the margin offsets and the photo 
// width to calculate positioning of the stamp. 
imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

// Output and free memory
imagejpeg($im,$inputimagepath,75);
imagedestroy($im);
}

function make_thumb($src, $dest, $desired_width) {

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    
    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));
    
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    
    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    
    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}
 ?>