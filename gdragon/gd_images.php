<?php

/*
Name:    gdImages
Version: 1.0.0
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://wp.gdragon.info/

== Copyright ==

Copyright 2008 Milan Petrovic (email : milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('gdImages')) {
    class gdImages {
        /**
         * Returns extension of a file.
         *
         * @param string $input file path or file name
         * @return string file extension
         */
        function get_extension($input) {
            return end(explode(".", $input));
        }

        /**
         * Creates a thumbnail from input image.
         *
         * @param string $input_file file to create thumbnail from
         * @param int $new_width thumbnail width
         * @param int $new_height thumbnail height
         * @param string $output_folder where to save the thumbnail. if empty, saves in the input image folder
         */
        function create_thumbnail($input_file, $new_width, $new_height, $output_folder, $new_name = '%NAME%_tn.jpg', $output_type = 'jpg') {
            gdImages::resize_image($input_file, $new_width, $new_height, $output_folder, false, $new_name, $output_type);
        }

        /**
         * Resizes the image.
         *
         * @param string $input_file file to resize
         * @param int $new_width image width
         * @param int $new_height image height
         * @param string $output_folder where to save new image. if empty, saves in the input image folder
         * @param bool $delete_input should the input image be deleted
         * @param string $new_name created file name
         * @param string $new_extension created file extension
         */
        function resize_image($input_file, $new_width, $new_height, $output_folder = '', $delete_input = false, $new_name = '%NAME%.%EXT%', $output_type = '') {
            $ext = gdImages::get_extension($input_file);
            if ($output_folder == '') $output_folder = dirname($input_file);

            $file_name = basename($input_file, ".".$ext);
            $file_name = str_replace('%NAME%', $file_name, $new_name);
            $file_name = str_replace('%EXT%', $ext, $file_name);
            $output = $output_folder."/".$file_name;

            switch ($ext) {
                case "jpg":
                    $src_image = imagecreatefromjpeg($input_file);
                    break;
                case "png":
                    $src_image = imagecreatefrompng($input_file);
                    break;
                case "gif":
                    $src_image = imagecreatefromgif($input_file);
                    break;
            }

            $src_size = getimagesize($input_file);
            $xr = $src_size[0] / $new_width;
            $yr = $src_size[1] / $new_height;
            if ($xr < 1 && $yr < 1) {
                rename($input, $output);
            }
            else {
                if ($xr >= $yr) {
                    $xn = $new_width;
                    $yn = floor($src_size[1] / $xr);
                }
                else {
                    $yn = $new_height;
                    $xn = floor($src_size[0] / $yr);
                }
                $end_image = imagecreatetruecolor($xn, $yn);
                imagecopyresampled($end_image, $src_image, 0, 0, 0, 0, $xn, $yn, $src_size[0], $src_size[1]);
                imagedestroy($src_image);
                if ($delete_input) unlink($input);

                if ($output_type == '') $output_type = $ext;
                switch ($output_type) {
                    case "jpg":
                        imagejpeg($end_image, $output);
                        break;
                    case "png":
                        imagepng($end_image, $output);
                        break;
                    case "gif":
                        imagegif($end_image, $output);
                        break;
                }
        }
    }

        /**
         * Handles upload of image into
         *
         * @param array $upl_file uploaded file from the $_FILES
         * @param string $output_folder where to save the file
         * @param array $settings upload settings based on $img_default
         */
        function upload_image($upl_file, $output_folder, $new_name, $settings = array()) {
            $img_defaults = array(
                'limit_extensions' => true,
                'limit_size' => false,
                'limit_dimensions' => false,
                'allowed_extension' => 'gif|jpg|png',
                'maximum_size' => '100K',
                'maximum_width' => 0,
                'maximum_height' => 0,
                'minimum_width' => 0,
                'minimum_height' => 0,
                'auto_resize' => false
            );
            $options = gdFunctions::prefill_attributes($img_defaults, $settings);
            
            $upl_ext = gdImages::get_extension($upl_file["name"]);
            $result = array();
            $image_path = $output_folder.$new_name.".".$upl_ext;
            if (is_uploaded_file($upl_file["tmp_name"])) {
                $valid = true;
                if ($options["limit_extensions"]) {
                    $exts = explode("|", $options["allowed_extension"]);
                    if (!in_array($upl_ext, $exts)) $valid = false;
                }
                if ($valid) {
                    move_uploaded_file($upl_file["tmp_name"], $image_path);
                    $result["status"] = "ok";
                    $result["file_path"] = $image_path;
                    $result["content_type"] = $upl_file["type"];
                    $result["file_name"] = $upl_file["name"];
                }
            }
            else {
                $result["status"] = "error";
                $result["error"] = "File not uploaded.";
            }
            return $result;
        }

        function serve_image($path, $content_type, $ext) {
            Header("Content-type: ".$content_type);
            switch ($ext) {
                case "jpg":
                    $image = imagecreatefromjpeg($path);
                    imagejpeg($image);
                    imagedestroy($image);
                    break;
                case "png":
                    $image = imagecreatefrompng($path);
                    imagepng($image);
                    imagedestroy($image);
                    break;
                case "gif":
                    $image = imagecreatefromgif($path);
                    imagegif($image);
                    imagedestroy($image);
                    break;
            }
        }
    }
}
?>
