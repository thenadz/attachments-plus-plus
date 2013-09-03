<?php

/*
  Plugin Name: Attachments++
  Description: Plussify your attachments! Attachments++ allows auto-embedding of most document, video and audio files. No need to download that MS Word doc to read.
  Version: 0.6
  Author: Dan Rossiter
  Author URI: http://danrossiter.org/
  License: GPLv2
 */

define('ATTACHMENTS_PP_URL', plugin_dir_url(__FILE__));
define('ATTACHMENTS_PP_PATH', dirname(__FILE__).'/');

$att_pp_ver = '0.6';

/**
 *
 * @param string $content
 * @return string
 */
function plusify_content( $content ) {
   include_once ATTACHMENTS_PP_PATH . 'class-library.php';

   // only handle attachments that aren't already embedded (namely, not images)
   if (is_attachment() && !wp_attachment_is_image()) {
      $embed = AttachmentsPPLib::get_embedded_attachment();

      if(!empty($embed)) {
         // remove "attachment" class from $content -- it will be
         // included in the outer div around the embedder & existing content
         $regex = '#(.*)<(p|div)\s*class=[\'"]([-_A-Za-z ]*)attachment([-_A-Za-z ]*)[\'"]>(.*)</\2>(.*)#is';
         if(preg_match($regex, $content, $matches)) {
            $matches[3] = trim($matches[3]);
            $matches[4] = trim($matches[4]);

            $classes = '';
            if(!empty($matches[3]) || !empty($matches[4])) {
               if(!empty($matches[3]) && !empty($matches[4])) {
                  $classes = ' class="' . $matches[3] . ' ' . $matches[4] . '"';
               } else {
                  $classes = ' class="' . $matches[3] . $matches[4] . '"';
               }
            }

            $content = $matches[1] . '<' . $matches[2] . $classes . '>'
                . $matches[5] . '</' . $matches[2] . '>' . $matches[6];
         }

         $content = '<div class="attachment">' . $embed . $content . '</div>';
      }
   }

    // Returns the content.
    return $content;
}
add_filter( 'the_content', 'plusify_content' );

?>
