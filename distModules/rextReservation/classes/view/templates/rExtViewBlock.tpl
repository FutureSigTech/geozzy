{$client_includes}
<!-- rExtViewBlock.tpl en rExtReservation module -->
<div class="rExtReservation formBlock">
  {if isset($rExt.dataForm)}
    {foreach from=$rExt.dataForm.formFieldsArray item=field}
      {$field}
    {/foreach}
  {/if}
</div>
<!-- /rExtViewBlock.tpl en rExtReservation module -->
