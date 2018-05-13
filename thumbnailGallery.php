<?php declare(strict_types=1);

/**
 * Global variables and constants will be defined in this page
 * These variables and constants may be used in multiple pages.
 * Below we start a database connection.
 * Since PHP in moving to PDO and MySQLi, we no longer use MySQL.
 * PHP version 7+
 *
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
 
/**
 * Generate thumbnail
 * 
 * @param string $source        Image Source
 * @param string $destination   Image destination
 * @param string $desired_width Image width desired
 * 
 * @return void
 */
function makeThumb($source, $destination, $desired_width)
{
    /* read source image */
    $source_image = imagecreatefromjpeg($source);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    
    /* Find desired height of image */
    $desired_height = floor($height * ($desired_width / $width));
    
    /* create virtual image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    
    /* copy source image at resized size */
    imagecopyresized($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    
    /* create the physical thumbnail image at its destination */
    imagejpeg($virtual_image, $destination);
}

/**
 * Purpose return files from directory
 * 
 * @param string $images_directory Image Directory
 * @param array  $extensions       Default accepted extensions
 * 
 * @return array
 */
function getFiles($images_directory, $extensions = array("jpg"))
{
    $files = array();
    if ($handle = opendir($images_directory)) {
        while (($file = readdir($handle)) !==  false) {
            $extension = strtolower(get_file_extension($file));
            if ($extension && in_array($extension, $extensions)) {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
    return $files;
}

/**
 * Grab file extension
 * 
 * @param string $file_name take in file
 * 
 * @return string
 */
function get_file_extension($file_name)
{
    return substr(strrchr($file_name, "."), 1);
}

/* settings */
$images_dir = 'image_gallery/';
$thumbs_dir = 'image_thumbs/';
$thumbs_width = 200;
$images_per_row = 3;

$image_files = getFiles($images_dir);
if (count($image_files)) {
    $index = 0;
    foreach ($image_files as $index => $file) {
        $index++;
        $thumbnail_image = $thumbs_dir . $file;
        if (!file_exists($thumbnail_image)) {
            $extension = get_file_extension($thumbnail_image);
            if ($extension) {
                makeThumb($images_dir . $file, $thumbnail_image, $thumbs_width);
            }
        }
        echo '<a href="' . $images_dir . $file . '" class="photo-link smoothbox" rel="gallery"><img src="' . $thumbnail_image . '"></a>';
        if ($index % $images_per_row == 0) {
            echo '<div class="clear"></div>';
        }
    }
} else {
    echo '<p>There are no images in this gallery.</p>';
}
?>