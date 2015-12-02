<?php
global $BI_DEVICES;
global $BI_SITE_SECTIONS;
global $BI_METRICS_EXPLORER;
global $BI_METRICS_RESOURCE;

$BI_METRICS_EXPLORER = 'http://test.geozzy.itg.es:10163/observation/explorer';
$BI_METRICS_RESOURCE = 'http://test.geozzy.itg.es:10163/observation/resource';

$BI_SITE_SECTIONS = array(
  'landing-carrousel1' => array('name'=> 'Primer carrusel Landing'),
  'exp1-gallery' => array('name'=> 'Galería Explorador playas'),
  'exp1-map' => array('name'=> 'Mapa explorador playas')
);

$BI_DEVICES = array(
  'mob' => array('name'=> 'Smartphone'),
  'desk' => array('name'=> 'Sobremesa/portátil'),
  'tablet' => array('name'=> 'Tablet')
);
