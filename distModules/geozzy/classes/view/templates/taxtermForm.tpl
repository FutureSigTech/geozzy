<!-- newRecursoFormBlock.tpl en geozzy module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>

<script>
  var langAvailable = {$JsLangAvailable};
  var langDefault = {$JsLangDefault};
</script>

{$taxtermFormOpen}
  {foreach from=$taxtermFormFieldsArray item=field}
    {$field}
  {/foreach}
{$taxtermFormClose}
{$taxtermFormValidations}
