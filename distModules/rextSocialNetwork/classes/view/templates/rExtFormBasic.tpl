<!-- rExtFormBasic.tpl en rExtContact module -->
<div class="rExtSocialNetwork formBlock formBasic">
  <div>
  {foreach $textFb as $text}
    {$rExt.dataForm.formFieldsArray[$text]}
  {/foreach}
  </div>
  <div class="network facebook">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeFb']}
    <div class="defaultBox">
      <div class="intro">

      </div>
    </div>
  </div>
  <div class="network twitter">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeTwitter']}
    {foreach $textTwitter as $text}
      {$rExt.dataForm.formFieldsArray[$text]}
    {/foreach}
    <div class="defaultBox">
      <p>{t}Si no se especifica ningún texto, se utilizará por defecto el texto en el recuadro.{/t} </p>
      <p>{t}Para mostrar el nombre del recurso se debe usar #TITLE# y para compartir la url de la página se debe poner #URL#:{/t}</p>
    </div>
  </div>
  <div class="network linkedin">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeLinkedin']}
    {*foreach $textLinkedin as $text}
      {$rExt.dataForm.formFieldsArray[$text]}
    {/foreach*}
    <div class="defaultBox">
      {*<p>{t}Si no se especifica ningún texto, se utilizará por defecto el texto en el recuadro.{/t} </p>*}
    </div>
  </div>
</div>

<!-- /rExtFormBasic.tpl en rExtContact module -->
