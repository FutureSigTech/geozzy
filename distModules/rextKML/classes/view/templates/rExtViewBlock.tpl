<!-- rExtViewBlock.tpl en rextKML module -->
<script>

  {if isset( $rExt.data.file )}
    var rextKMLFile = '{$cogumelo.publicConf.site_host}{$cogumelo.publicConf.mediaHost}cgmlformfilewd/{$res.ext.rextKML.data.file.id}-a{$res.ext.rextKML.data.file.aKey}/{$rExt.data.file.name}';
  {else}
    var rextKMLFile = false;
  {/if}

</script>

<!-- /rExtViewBlock.tpl en rextKML module -->
