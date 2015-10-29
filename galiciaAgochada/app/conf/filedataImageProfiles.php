<?php
/*
 * Perfiles que se pueden aplicar a imágenes cargadas en filedata
 *
 *
 * Formatos de PERFIL
 *
 *   'profileName' => array(
 *     'width' => {pxWidth},
 *     'height' => {pxWeight}
 *   )
 *   Parámetros opcionales:
 *     'cut' - default: true
 *     'enlarge' - default: true
 *     'saveFormat' - ['JPEG','PNG'] default: Original format
 *     'saveName' - default: Filedata 'name'
 *     'saveQuality'
 *
 *
 * Formatos de URL
 *
 * Para cargar la imagen según un perfil:
 *
 *   /cgmlImg/{filedataId}/{profileName}/{fileName}.{fileExt}
 *   /cgmlImg/{filedataId}/{profileName}/{filedataId}.{fileExt}
 *   /cgmlImg/{filedataId}/{profileName}[/.*] (realiza un redirect al caso 1)
 *
 * Para cargar la imagen original sin procesar:
 *
 *   /cgmlImg/{filedataId}/{fileName}.{fileExt}
 *   /cgmlImg/{filedataId}/{filedataId}.{fileExt}
 *   /cgmlImg/{filedataId}[/.*] (realiza un redirect al caso 1)
 */

global $IMAGE_PROFILES;

$IMAGE_PROFILES = array(
  'ancho' => array( 'width' => 400, 'height' => 200 ),
  'alto' => array( 'width' => 200, 'height' => 400 ),
  'fast' => array( 'width' => 400, 'height' => 300, 'cut' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 50 ),
  'exp1' => array( 'width' => 200, 'height' => 150 ),
  'rec1' => array( 'width' => 400, 'height' => 300, 'saveName' => 'rec1.png', 'saveFormat' => 'PNG' )
);



/* identify -list format
   Format  Module    Mode  Description
-------------------------------------------------------------------------------
      BMP* BMP       rw-   Microsoft Windows bitmap image
     BMP2* BMP       -w-   Microsoft Windows bitmap image (V2)
     BMP3* BMP       -w-   Microsoft Windows bitmap image (V3)

      GIF* GIF       rw+   CompuServe graphics interchange format
    GIF87* GIF       rw-   CompuServe graphics interchange format (version 87a)

     JPEG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)
      JPG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)
    PJPEG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)

      PNG* PNG       rw-   Portable Network Graphics (libpng 1.2.50) http://www.libpng.org/ PNG format.
    PNG24* PNG       rw-   opaque 24-bit RGB (zlib 1.2.8)
    PNG32* PNG       rw-   opaque or transparent 32-bit RGBA
     PNG8* PNG       rw-   8-bit indexed with optional binary transparency
      JNG* PNG       rw-   JPEG Network Graphics
      MNG* PNG       rw+   Multiple-image Network Graphics (libpng 1.2.50)

      SVG  SVG       rw+   Scalable Vector Graphics (RSVG 2.40.1)
     SVGZ  SVG       rw+   Compressed Scalable Vector Graphics (RSVG 2.40.1)
     MSVG  SVG       rw+   ImageMagick's own SVG internal renderer

   GROUP4* TIFF      rw-   Raw CCITT Group4
     PTIF* TIFF      rw+   Pyramid encoded TIFF
     TIFF* TIFF      rw+   Tagged Image File Format (LIBTIFF, Version 4.0.3)
   TIFF64* TIFF      rw-   Tagged Image File Format (64-bit) (LIBTIFF, Version 4.0.3)
*/