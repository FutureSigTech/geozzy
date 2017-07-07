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


// $IMAGE_PROFILES
cogumeloSetSetupValue( 'mod:filedata:profile:mdpi4', array( 'width' => 640, 'height' => 480, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:hdpi4', array( 'width' => 960, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:xhdpi4', array( 'width' => 1280, 'height' => 960, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:mdpi16', array( 'width' => 640, 'height' => 360, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:hdpi16', array( 'width' => 960, 'height' => 540, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:xhdpi16', array( 'width' => 1280, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );


// $IMAGE_PROFILES (web)
cogumeloSetSetupValue( 'mod:filedata:profile:wsdpi4', array( 'width' => 320, 'height' => 240, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:wmdpi4', array( 'width' => 640, 'height' => 480, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:whdpi4', array( 'width' => 960, 'height' => 720, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:wxhdpi4', array( 'width' => 1280, 'height' => 960, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:wsdpi16', array( 'width' => 320, 'height' => 180, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:wmdpi16', array( 'width' => 640, 'height' => 360, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:whdpi16', array( 'width' => 960, 'height' => 540, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:wxhdpi16', array( 'width' => 1280, 'height' => 720, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:none', array( 'width' => 2000, 'height' => 2000, 'cut' => false, 'enlarge' => false ) );


cogumeloSetSetupValue( 'mod:filedata:profile:modFormTn', array( 'width' => 240, 'height' => 180, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 60 ) );


cogumeloSetSetupValue( 'mod:filedata:profile:big', array(
  'width' => 2000, 'height' => 2000, 'cut' => false, 'enlarge' => false,
  'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:fast', array(
  'width' => 400, 'height' => 300, 'cut' => false,
  'rasterColor' => '#000000', 'backgroundColor' => '#FFFFFF',
  'saveFormat' => 'JPEG', 'saveQuality' => 50 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:fast_cut', array(
  'width' => 400, 'height' => 300, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 50 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:squareCut', array(
  'width' => 128, 'height' => 128, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:exp1', array(
  'width' => 200, 'height' => 150 ) );



cogumeloSetSetupValue( 'mod:filedata:profile:rec1', array(
  'width' => 400, 'height' => 300, 'saveName' => 'rec1.png', 'saveFormat' => 'PNG' ) );




cogumeloSetSetupValue( 'mod:filedata:profile:resourceCut', array(
  'width' => 270, 'height' => 153, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:resourceBigCut', array(
  'width' => 340, 'height' => 193, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:ponentesCut', array(
  'width' => 200, 'height' => 200, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );



cogumeloSetSetupValue( 'mod:filedata:profile:imgMultimediaGallery', array(
  'width' => 250, 'height' => 250, 'cut' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:educaListMenu', array(
  'width' => 400, 'height' => 230, 'cut' => true, 'saveName' => 'icon.jpg', 'saveFormat' => 'JPEG',
  'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:imgResourceIndivBlog', array(
  'width' => 553, 'height' => 311, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:profilePeople', array(
  'width' => 400, 'height' => 400, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:profileColaboradores', array(
  'width' => 200, 'height' => 200, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );





/*-----------------------------------------TEMP ----------------------------------------------------------------------*/
cogumeloSetSetupValue( 'mod:filedata:profile:typeIcon', array(
  'width' => 36, 'height' => 36,
  'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
  'padding' => 5, 'saveName' => 'icon.png', 'saveFormat' => 'PNG' ) );

cogumeloSetSetupValue( 'mod:filedata:profile:typeIconHover', array(
  'width' => 36, 'height' => 36,
  'backgroundImg' => '/app/classes/view/templates/img/chapas/chapaFilterHover36x36.png',
  'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
  'padding' => 5, 'saveName' => 'iconHover.png', 'saveFormat' => 'PNG' ) );

cogumeloSetSetupValue( 'mod:filedata:profile:typeIconSelected', array(
  'width' => 36, 'height' => 36,
  'backgroundImg' => '/app/classes/view/templates/img/chapas/chapaRest36x36.png',
  'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
  'padding' => 5, 'saveName' => 'iconSelected.png', 'saveFormat' => 'PNG' ) );


// cogumeloSetSetupValue( 'mod:filedata:profile:resourceLg', array( 'width' => 2000, 'height' => 700) );
// cogumeloSetSetupValue( 'mod:filedata:profile:resourceMd', array( 'width' => 1200, 'height' => 500) );
// cogumeloSetSetupValue( 'mod:filedata:profile:resourceSm', array( 'width' => 768, 'height' => 300) );

cogumeloSetSetupValue( 'mod:filedata:profile:resourceLg', array( 'width' => 2000, 'height' => 700, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:resourceMd', array( 'width' => 1600, 'height' => 600, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:resourceSm', array( 'width' => 1199, 'height' => 500, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:resourceXs', array( 'width' => 991, 'height' => 450, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );

cogumeloSetSetupValue( 'mod:filedata:profile:blogLg', array( 'width' => 2000, 'height' => 500, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:blogMd', array( 'width' => 1600, 'height' => 400, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:blogSm', array( 'width' => 1199, 'height' => 300, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:blogXs', array( 'width' => 991, 'height' => 248, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );


cogumeloSetSetupValue( 'mod:filedata:profile:ancho', array( 'width' => 400, 'height' => 200 ) );
cogumeloSetSetupValue( 'mod:filedata:profile:alto', array( 'width' => 200, 'height' => 400 ) );

/*
// TEST
cogumeloSetSetupValue( 'mod:filedata:profile:svgTest',
  array(
    'width' => 100, 'height' => 64,
    'cut' => false,
    'rasterColor' => '#00FF00',
    //'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
    'padding' => array( 0, 18, 0, 18 ),
    'backgroundColor' => '#F000F0',
    'saveFormat' => 'PNG', 'cache' => false
  )
);

// CHAPA TEST
cogumeloSetSetupValue( 'mod:filedata:profile:chapaTest',
  array(
    'width' => 24, 'height' => 24,
    'cut' => false,
    'rasterColor' => '#FFFFFF',
    'padding' => 5,
    //'padding' => array( 5, 5, 5, 5 ),
    'backgroundColor' => '#F000F0',
    'backgroundImg' => '/chapa_paisaxes.png',
    //'backgroundImg' => '/module/MODULENAME/chapa_paisaxes.png',
    //'backgroundImg' => '/app/classes/view/templates/img/chapa_paisaxes.png',
    'saveFormat' => 'PNG', 'cache' => false
  )
);
*/




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
