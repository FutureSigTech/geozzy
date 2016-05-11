<div class="basicCollectionView accordion" id="accordion2">

  {$eventsByDate = array()}
  {foreach $rExt.data.events as $key=>$res}
    {$eventsByDate[$res.event.formatedDate.initDate]['id'] = $res.event.resource}
    {$eventsByDate[$res.event.formatedDate.initDate]['formatedDate'] = $res.event.formatedDate}
    {$eventsByDate[$res.event.formatedDate.initDate]['data'][] = $res}
  {/foreach}


  {foreach $eventsByDate as $k=>$eventDate}
    <div class="event accordion-group">
      <div class="accordion-heading row">
        <a class="date accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href=".ele{$eventDate.id}">
          <i class="fa fa-caret-right" aria-hidden="true"></i>
          {$eventDate.formatedDate.l}, {$eventDate.formatedDate.j} de {$eventDate.formatedDate.F}
        </a>
      </div><hr/>
      <div class="ele{$eventDate.id} accordion-body collapse {if $eventDate@first}in{/if}">
        {foreach $eventDate.data as $i => $elm}
        <div class="extendedData accordion-inner">

          <div class="eventTime col-md-1">
            <p><b>{$elm.event.formatedDate.time} h.</b></p>
          </div>
          <div class="eventTitle col-md-11">
            <p><b>{$elm.resource.title}</b></p>
          </div>

          <div class="eventDescription col-md-7">
            <p>{$elm.resource.mediumDescription}</p>
          </div>
          <img class="col-md-5" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$elm.resource.image}/basicEvent/{$elm.resource.image}.jpg"/>

        </div><hr/>
        {/foreach}
      </div>
    </div>
  {/foreach}

</div>