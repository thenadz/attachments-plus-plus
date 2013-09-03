<?php

/**
 * Static library functions for Attachments++ plugin.
 */
class AttachmentsPPLib {

   /**
    * HTML comment header.
    */
   private static $comment_header =
       '<!-- Embedded Attachment powered by Attachments++. Install yours here: http://wordpress.org/plugins/attachments-plus-plus/ -->';

   /**
    * HTML template to print Google Drive Viewer.
    *
    * @link https://docs.google.com/viewer Google Drive Viewer
    */
   private static $google_viewer_template =
       '<iframe src="http://docs.google.com/viewer?url=%s&embedded=true" width="%d" height="%d" style="border: none;"></iframe>';

   /**
    * Array with all exts supported by Google Drive Viewer.
    *
    * @link http://wordpress.org/plugins/google-document-embedder/ Google Doc Embedder
    */
   private static $google_viewer_exts = array(
      // Image files
      'jpeg', 'png', 'gif', 'tiff', 'bmp',
      // Video files
      'webm', 'mpeg4', '3gpp', 'mov', 'avi', 'mpegps', 'wmv', 'flv',
      // Text files
      'txt',
      // Markup/Code
      'css', 'html', 'php', 'c', 'cpp', 'h', 'hpp', 'js',
      // Microsoft Word
      'doc', 'docx',
      // Microsoft Excel
      'xls', 'xlsx',
      // Microsoft PowerPoint
      'ppt', 'pptx',
      // Adobe Portable Document Format
      'pdf',
      // Apple Pages
      'pages',
      // Adobe Illustrator
      'ai',
      // Adobe Photoshop
      'psd',
      // Autodesk AutoCad
      'dxf',
      // Scalable Vector Graphics
      'svg',
      // PostScript
      'eps', 'ps',
      // TrueType
      'ttf',
      // XML Paper Specification
      'xps',
      // Archive file types
      'zip', 'rar'
   );

   /**
    * Returns HTML string to embed the attachment,
    * or empty string if cannot be embedded.
    *
    * @return string - HTML string to embed.
    */
   public static function get_embedded_attachment() {
      include_once ATTACHMENTS_PP_PATH . 'models/class-callable-function.php';

      $post = get_post();
      $url = wp_get_attachment_url($post->ID);
      $filetype = wp_check_filetype(basename($url));
      $ret = '';

      // loop through embedders until we find one that suppports filetype
      foreach(self::get_embedder_mapping() as $embedder) {
         try {
            // if the either function is not valid, skip embedder
            if(!$embedder['function']->is_valid()
                || !$embedder['exts']->is_valid()) {
               continue;
            }

            // get supported exts
            $exts = $embedder['exts']->execute();

            // if supported, get embedder and break loop
            if(in_array($filetype['ext'], $exts)) {
               $ret = self::$comment_header . PHP_EOL .
                   $embedder['function']->execute($url);
               break;
            }
         } catch(Exception $e) {
            $ret = '<p>An error occured while calling '.$embedder['function'].': '
               .'<em>'.$e->getMessage().'</em></p>';
         }
      }

      return $ret;
   }

   /**
    * Get mapping from ext to embedder. Returns a nested array, with internal
    * arrays each containing the human-readable name of the embedder, the
    * function to be called, and another function which returns an array of
    * all supported extensions (lowercase).
    *
    * Function must take a single variable containing the URL to the attachment
    * to be embedded, and exts must take no arguments.
    *
    * @filter array - Other plugins may filter 'app_embedder_mapping', adding to
    * or changing the order of existing embedders.
    * @global type $wp_version
    * @return array - nested array, each internal array representing one embed method.
    */
   public static function get_embedder_mapping() {
      include_once ATTACHMENTS_PP_PATH . 'models/class-callable-function.php';

      global $wp_version;
      $mapping = array();

      // WP >= 3.6 has support for embedding video/audio
      if(version_compare($wp_version, '3.6', '>=')) {
         // WP Core Video
         $mapping[] = array(
             'name'     => 'WordPress Video',
             'function' => new APPCallableFunction('wp_video_shortcode'),
             'exts'     => new APPCallableFunction('wp_get_video_extensions')
         );

         // WP Core Audio
         $mapping[] = array(
             'name'     => 'WordPress Audio',
             'function' => new APPCallableFunction('wp_audio_shortcode'),
             'exts'     => new APPCallableFunction('wp_get_audio_extensions')
         );
      }

      // Google Doc Embedder (Plugin)
      if(self::has_google_embedder()) {
         $mapping[] = array(
             'name'     => 'Google Doc Embedder (Plugin)',
             'function' => new APPCallableFunction('get_google_embedder', __CLASS__, __FILE__),
             'exts'     => new APPCallableFunction('get_google_embedder_exts', __CLASS__, __FILE__)
         );
      }

      // Google Drive Viewer
      $mapping[] = array(
          'name'     => 'Google Drive Viewer',
          'function' => new APPCallableFunction('get_google_viewer', __CLASS__, __FILE__),
          'exts'     => new APPCallableFunction('get_google_viewer_exts', __CLASS__, __FILE__)
      );

      return apply_filters('app_embedder_mapping', $mapping);
   }

   /**
    * Returns HTML to embed attachment with Google Doc Embedder plugin.
    *
    * @param string $src - URL for attachment to be embedded.
    * @return string - HTML string to embed.
    */
   public static function get_google_embedder($src) {
      return gde_do_shortcode(array('file' => $src));
   }

   /**
    * Returns array of all extensions supported by Google Doc Embedder plugin.
    * Should not be called before verifying has_google_embedder().
    *
    * @return array - all exts supported by Google Doc Embedder plugin.
    */
   public static function get_google_embedder_exts() {
      return array_keys(gde_supported_types());
   }

   /**
    * Returns HTML to embed attachment with Google Drive Viewer.
    *
    * @global int $content_width
    * @param string $src - URL for attachment to be embedded.
    * @return string - HTML string to embed.
    */
   public static function get_google_viewer($src) {
      global $content_width;
      $filetype = wp_check_filetype($src);

      $width = empty($content_width) ? 640 : $content_width;
      $height = ($filetype['ext'] == 'ppt' || $filetype['ext'] == 'pptx')
          ? (int)ceil($width * .55)
          : (int)ceil($width * .85);

      return sprintf(self::$google_viewer_template,
          urlencode($src), $width, $height);
   }

   /**
    * Returns array of all extensions supported by Google Drive Viewer.
    *
    * @return array - all exts supported by Google Drive Viewer.
    */
   public static function get_google_viewer_exts() {
      return self::$google_viewer_exts;
   }

   /**
    * Returns whether Google Doc Embedder is installed and active.
    *
    * @global string $gde_ver
    * @return boolean - whether Google Doc Embedder plugin is installed.
    */
   public static function has_google_embedder() {
      global $gde_ver;

      return !empty($gde_ver) && version_compare($gde_ver, '2.5.0.1', '>=');
   }
}

?>
