{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$GLOBAL_LANG_AVAILABLE[$GLOBAL_C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="praiasExplorer explorerCommonBase">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}

{block name="footerContent"}

{/block}
