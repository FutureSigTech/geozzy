{extends file="admin///adminPanel.tpl"}

{block name="content"}

{if isset($prevContent)}
  {$prevContent}
{/if}

{if isset($formFieldsNames)}
  {foreach $formFieldsNames as $name}
    {$res.dataForm.formFieldsArray[$name]}
  {/foreach}
{/if}

{if isset($blockContent)}
  {$blockContent}
{/if}

{if isset($postContent)}
  {$postContent}
{/if}

{/block}{*/content*}
