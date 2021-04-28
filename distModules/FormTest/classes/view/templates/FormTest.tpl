<!DOCTYPE html><!-- FormTest.tpl INI -->
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
    <title>FormTest</title>
    {block name="headClientIncludes"}
      {$main_client_includes}
      <script type="text/javascript"> $( document ).ready(function(){ feature.testAll(); }); </script>
      {$client_includes}
    {/block}
  </head>

  <body class="FormTest">
    <main>
      <section class="bodyContent">

<div style="width:50%;text-align:center;margin-left:25%;background-color:pink;padding:20px;">

{$formTestOpen}

{foreach $formTestFields as $key=>$formField}
{$formField}
{/foreach}

{*
  {$formTestFields.cgIntFrmId}

  {$formTestFields.testNome}

  {$formTestFields.testFoto}

  {$formTestFields.submit}
*}

{$formTestClose}

{$formTestValidations}

</div>

      </section>
    </main>
  </body>
</html>
<!-- FormTest.tpl FIN -->
