<?php

/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\CoreBundle\Helper;

/**
 * HtmlHelper
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class Html {

    /**
     *
     * @param Container $container            
     * @param array $arrLink            
     * @return string
     */
    public static function showActionButtonsInTable($container, $arrLink) {
        $html = '';
        if (isset($arrLink ['edit'])) {
            $html .= "<a class=\"btn btn-xs default btn-editable\" href=\"{$arrLink['edit']}\" title=\"" . $container->get('translator')->trans('Edit') . "\" >";
            $html .= "<i class=\"fa fa-edit\"></i> " . $container->get('translator')->trans('Edit') . "</a>";
        }
        if (isset($arrLink ['delete'])) {
            $html .= "<a class=\"btn btn-xs default btn-editable\" href=\"{$arrLink['delete']}\" title=\"" . $container->get('translator')->trans('Delete') . "\" >";
            $html .= "<i class=\"fa fa-trash\"></i> " . $container->get('translator')->trans('Delete') . "</a>";
        }
        
        return $html;
    }

    /**
     *
     * @param Container $container            
     * @param boolean $value            
     * @return string
     */
    public static function showStatusInTable($container, $value) {
        if ($value) {
            $html = "<span class=\"label label-sm label-info\">" . $container->get('translator')->trans('Publish') . "</span>";
        } else {
            $html = "<span class=\"label label-sm label-danger\">" . $container->get('translator')->trans('Un-publish') . "</span>";
        }
        
        return $html;
    }
    
    /**
     *
     * @param Container $container
     * @param boolean $value
     * @return string
     */
    public static function showStatusOrder($container, $value) {
        if ($value == 1) {
            $html = "<span class=\"label label-sm label-info\">" . $container->get('translator')->trans('Completed') . "</span>";
        } elseif ($value == 0) {
            $html = "<span class=\"label label-sm label-danger\">" . $container->get('translator')->trans('Closed') . "</span>";
        } else {
            $html = "<span class=\"label label-sm label-warning\">" . $container->get('translator')->trans('Pending') . "</span>";
        }
    
        return $html;
    }
    
    /**
     * @param Container $container
     * @param string $image
     * @return string
     */
    public static function showImage($container, $image = '') {
        if (!empty($image)) {
            $html = "<img height=\"60\" src=\"/uploads/" . $image."\" />";
        } else {
            $html = "<img src=\"http://www.placehold.it/100x60/EFEFEF/AAAAAA&text=no+image\" />";
        }
        return $html;
    }

    /**
     *
     * @param string $str            
     * @return string|mixed
     */
    static public function slugify($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());
        
        $defaults = array ( 
            'delimiter' => '-', 
            'limit' => null, 
            'lowercase' => true, 
            'replacements' => array (), 
            'transliterate' => true 
        );
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        $char_map = array ( 
            // Latin
            'À' => 'A', 
            'Á' => 'A', 
            'Â' => 'A', 
            'Ã' => 'A', 
            'Ä' => 'A', 
            'Å' => 'A', 
            'Æ' => 'AE', 
            'Ç' => 'C', 
            'È' => 'E', 
            'É' => 'E', 
            'Ê' => 'E', 
            'Ë' => 'E', 
            'Ì' => 'I', 
            'Í' => 'I', 
            'Î' => 'I', 
            'Ï' => 'I', 
            'Ð' => 'D', 
            'Ñ' => 'N', 
            'Ò' => 'O', 
            'Ó' => 'O', 
            'Ô' => 'O', 
            'Õ' => 'O', 
            'Ö' => 'O', 
            'Ő' => 'O', 
            'Ø' => 'O', 
            'Ù' => 'U', 
            'Ú' => 'U', 
            'Û' => 'U', 
            'Ü' => 'U', 
            'Ű' => 'U', 
            'Ý' => 'Y', 
            'Þ' => 'TH', 
            'ß' => 'ss', 
            'à' => 'a', 
            'á' => 'a', 
            'â' => 'a', 
            'ã' => 'a', 
            'ä' => 'a', 
            'å' => 'a', 
            'æ' => 'ae', 
            'ç' => 'c', 
            'è' => 'e', 
            'é' => 'e', 
            'ê' => 'e', 
            'ë' => 'e', 
            'ì' => 'i', 
            'í' => 'i', 
            'î' => 'i', 
            'ï' => 'i', 
            'ð' => 'd', 
            'ñ' => 'n', 
            'ò' => 'o', 
            'ó' => 'o', 
            'ô' => 'o', 
            'õ' => 'o', 
            'ö' => 'o', 
            'ő' => 'o', 
            'ø' => 'o', 
            'ù' => 'u', 
            'ú' => 'u', 
            'û' => 'u', 
            'ü' => 'u', 
            'ű' => 'u', 
            'ý' => 'y', 
            'þ' => 'th', 
            'ÿ' => 'y', 
            
            // Latin symbols
            '©' => '(c)',     
        );
        
        // Make custom replacements
        $str = preg_replace(array_keys($options ['replacements']), $options ['replacements'], $str);
        
        // Transliterate characters to ASCII
        if ($options ['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options ['delimiter'], $str);
        
        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options ['delimiter'], '/') . '){2,}/', '$1', $str);
        
        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options ['limit'] ? $options ['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        
        // Remove delimiter from ends
        $str = trim($str, $options ['delimiter']);
        
        return $options ['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
}

