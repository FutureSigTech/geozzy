<!-- resourceFormBlockBase.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
  .cgmMForm-wrap { margin-bottom: 10px; }
</style>
<script type="text/javascript" src="/media/module/admin/js/adminResource.js"></script>

<script>
  var langAvailable = {$JsLangAvailable};
  var langDefault = {$JsLangDefault};
</script>


{$formOpen}

{foreach $formFieldsArray as $formField}
  {$formField}
{/foreach}

{$formClose}

{$formValidations}


<!-- /resourceFormBlockBase.tpl en admin module -->
