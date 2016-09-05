

update geozzy_resource
  set idName='home',
    title_en='Home',
    title_es='Portada',
    title_gl='Portada',
    shortDescription_en='',
    shortDescription_es='',
    shortDescription_gl=''
where id=5406;

update geozzy_resource
  set idName='xantaresExplorer',
    title_en='Tasty meals',
    title_es='Sabrosas comidas',
    title_gl='Sabrosos xantares',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5407;

update geozzy_resource
  set idName='aloxamentosExplorer',
    title_en='Charming accommodations',
    title_es='Alojamientos con encanto',
    title_gl='Aloxamentos con encanto',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5408;

update geozzy_resource
  set idName='rinconsExplorer',
    title_en='Charming spots',
    title_es='Rincones con encanto',
    title_gl='Rincons con encanto',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5409;

update geozzy_resource
  set idName='praiasExplorer',
    title_en='Dreamlike beaches',
    title_es='Playas de ensueno',
    title_gl='Praias de ensono',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5410;

update geozzy_resource
  set idName='paisaxesExplorer',
    title_en='Spectacular landscapes',
    title_es='Paisajes Espectaculares',
    title_gl='Paisaxes Espectaculares',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5411;

update geozzy_resource
  set idName='segredosExplorer',
    title_en='Discover them all togheter',
    title_es='Descubrelos todos juntos',
    title_gl='Descubreos todos xuntos',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5412;


update geozzy_resource
  set idName='festasExplorer',
    title_en='Parties',
    title_es='Fiestas',
    title_gl='Festas',
    shortDescription_en='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_es='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.',
    shortDescription_gl='Nam in mauris nisi. Duis dictum auctor sapien in aliquam. Cras laoreet sem tortor, quis rutrum.'
where id=5623;





CREATE TABLE geozzy_resource_rext_participation (
  `id` INT NOT NULL auto_increment,
  `resource` INT,
  `participation` BIT DEFAULT 0,
  `observation` VARCHAR(200),
  PRIMARY KEY USING BTREE (`id`),
  INDEX (`resource`)
) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci COMMENT = 'Generated by Cogumelo devel, ref:/var/www/vhosts/test2.geozzy.com/framework/geozzy/distModules/rextParticipation/classes/model/ParticipationModel.php';



