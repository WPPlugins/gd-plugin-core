<?php
/*
Name: GD Plugin Core: Xtras
Description: Help functions
*/

class GDCoreXtra {
    var $img_default = array(
        'limit_extension' => 'gif|jpg|png',
        'limit_width' => 0,
        'limit_height' => 0,
        'auto_resize' => false
    );

    /**
     * Renders into string items for select html tag.
     *
     * @param array $options options to render
     * @param string $selected selected option value
     * @return string rendered options for select tag
     */
    function get_select_options($options, $selected = "") {
        $output = "";
        for ($i = 0; $i < count($options); $i++) {
            $o = $options[$i];
            if (is_array($o)) {
                $value = $o["value"];
                $text = $o["text"];
            }
            else {
                $value = $i + 1;
                $text = $o;
            }
            if ($selected == $value) $current = ' selected="selected"';
            else $current = '';
            $output.= "<option value='".$value."'".$current.">".$text."</option>\r\n";
        }
        return $output;
    }
    
    /**
     * Renders and echoes string items for select html tag.
     *
     * @param array $options options to render
     * @param string $selected selected option value
     */
    function render_select_options($options, $selected = "") {
        echo get_render_select_options($options, $selected);
    }

    /**
     * Resizes the image.
     *
     * @param string $input file path to input file
     * @param <type> $output file path to output file
     * @param <type> $ext file extension
     * @param <type> $xs new width
     * @param <type> $ys new hieght
     */
    function resize_image($input, $output, $ext, $xs, $ys) {
        switch ($ext)
        {
            case "jpg":
                $src_image = imagecreatefromjpeg($input);
                break;
            case "png":
                $src_image = imagecreatefrompng($input);
                break;
            case "gif":
                $src_image = imagecreatefromgif($input);
                break;
        }

        $src_size = getimagesize($input);
        $xr = $src_size[0] / $xs;
        $yr = $src_size[1] / $ys;
        if ($xr < 1 && $yr < 1) {
            rename($input, $output);
        }
        else {
            if ($xr >= $yr) {
                $xn = $xs;
                $yn = floor($src_size[1] / $xr);
            }
            else {
                $yn = $ys;
                $xn = floor($src_size[0] / $yr);
            }
            $end_image = imagecreatetruecolor($xn, $yn);
            imagecopyresampled($end_image, $src_image, 0, 0, 0, 0, $xn, $yn, $src_size[0], $src_size[1]);
            imagedestroy($src_image);
            unlink($input);

            switch ($ext)
            {
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

    function import_image($upl_file, $output_folder, $settings = array()) {
        $result = array();
        $image_path = "";
        if (is_uploaded_file($upl_file["tmp_name"])) {
            $exts = explode("|", $options["logo_ext"]);
            $upl_ext = strtolower(substr($upl_file["name"], -3, 3));
            if (in_array($upl_ext, $exts)) {

            }
        }
    }
}

?>