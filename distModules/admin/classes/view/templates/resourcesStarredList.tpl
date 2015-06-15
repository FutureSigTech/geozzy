


<script type="text/template" id="resourcesStarredList">

  <div class="headSection clearfix">
    <div class="row">
      <div class="col-md-8 col-sm-12">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
        </button>
        <div class="headerTitleContainer">
          <h2>{t}Resources Starred in{/t} <%- name_{$langDefault} %> </h2>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 clearfix">
        <div class="headerActionsContainer">
          <button type="button" class="assignResourceStarred btn btn-default btn-outline"> {t}Assign to{/t} <%- name_{$langDefault} %></button>
          <span class="saveChanges">
            <button class="btn btn-danger cancel">{t}Cancel{/t}</button>
            <button class="btn btn-primary save">{t}Save{/t}</button>
          </span>
        </div>
      </div>
    </div>
    <!-- /.navbar-header -->
  </div><!-- /headSection -->


  <div class="contentSection clearfix">
    <div class="admin-cols-8-4">
      <div class="row">
        <div class="col-lg-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>
                <i class="fa fa-tag fa-fw"></i>
                {t}List of resources for{/t} ( <%- name_{$langDefault} %> )
              </strong>
            </div>
            <div class="panel-body">
              <div id="resourcesStarredListContainer" class="gzznestable dd">
                <ol class="listResources dd-list">
                </ol>
              </div>
            </div> <!-- end panel-body -->
          </div> <!-- end panel -->
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>

  </div><!-- /contentSection -->


  <div class="footerSection clearfix">
    <div class="headerActionsContainer">
      <button type="button" class="asignResourceStarred btn btn-default"> {t}Assign to{/t} <%- name_{$langDefault}  %></button>
      <span class="saveChanges">
        <button class="btn btn-danger cancel">{t}Cancel{/t}</button>
        <button class="btn btn-primary save">{t}Save{/t}</button>
      </span>
    </div>
  </div><!-- /footerSection -->

</script>


<script type="text/template" id="resourcesStarredItem">

  	<li class="dd-item" data-id="<%- resource.id %>">
      <div class="dd-item-container clearfix">

        <div class="dd-content">
          <div class="nestableActions">
  	        <button class="btnDelete btn btn-default btn-danger" data-id="<%- resource.id %>" ><i class="fa fa-trash"></i></button>
  	      </div>
    	  </div>

        <div class="dd-handle">
          <i class="fa fa-arrows icon-handle"></i>
          <%- resource.title_{$langDefault} %>
        </div>

      </div>
    </li>

</script>
