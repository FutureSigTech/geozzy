<?php

Cogumelo::setSetupValue( 'mod:mediaserver:publicConf:javascript:vars:admin:adminMap',
  array(
    'marker' => Cogumelo::getSetupValue('publicConf:vars:media') . '/img/formularioMarker.png',
    'defaultLat' => '42.927',
    'defaultLon' => '-8.160',
    'defaultZoom' => '10'
  )
);
