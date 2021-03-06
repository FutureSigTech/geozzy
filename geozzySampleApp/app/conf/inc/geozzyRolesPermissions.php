<?php
/*
'permissions' => array(
  'category:list',
  'category:create',
  'category:edit',
  'category:delete',

  'resource:mylist',
  'resource:create',
  'resource:edit',
  'resource:delete',
  'resource:publish',

  'topic:list',
  'topic:assign',

  'page:list',

  'setting:list',

  'starred:list',
  'starred:assign',

  'user:all',

  'admin:access',
  'admin:full',

  'filedata:privateAccess'
)
*/
Cogumelo::setSetupValue( 'user:roles:administrador',
  array(
    'name' => 'administrador',
    'description' => 'Role Administrador',
    'permissions' => array(
      'admin:full',
      'admin:access',
      'filedata:privateAccess'
    )
  )
);

Cogumelo::setSetupValue( 'user:roles:gestor',
  array(
    'name' => 'gestor',
    'description' => 'Role Gestor',
    'permissions' => array(
      'admin:access',
      'topic:list',
      'resource:edit',
      'filedata:privateAccess'
    )
  )
);
