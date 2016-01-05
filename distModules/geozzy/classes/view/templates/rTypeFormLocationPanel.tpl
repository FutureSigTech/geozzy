{extends file="admin///adminPanel.tpl"}


{block name="content"}

<div class="row location">
    <div class="col-lg-12 mapContainer">
      <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
    </div>
    <div class="col-lg-12 locationData">
      <div class="row">
        <div class="col-md-3">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-3">{$res.dataForm.formFieldsArray['locLon']}</div>
        <div class="col-md-3">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
        <div class="col-md-3"><div class="automaticBtn btn btn-primary">{t}Automatic Location{/t}</div></div></div>
    </div>
    <div class="col-lg-12 locationDirections">
      {foreach $directions as $dir}
        {$res.dataForm.formFieldsArray[$dir]}
      {/foreach}
    </div>
  </div>

{/block}{*/content*}