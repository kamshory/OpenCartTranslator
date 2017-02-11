<?php echo $header;?>

<div id="content">

    <ul class="breadcrumb">

        <?php foreach ($breadcrumbs as $breadcrumb) { ?>

            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

        <?php } ?>

    </ul>

    <?php if ($error_warning) { ?><div class="alert alert-danger" > <i class="icon-exclamation-sign"></i>&nbsp;<?php echo $error_warning; ?></div><?php } ?>

    <?php if (!empty($this->session->data['success'])) { ?>

        <div class="alert alert-success autoSlideUp"> <i class="fa fa-info"></i>&nbsp;<?php echo $this->session->data['success']; ?> </div>

        <script> $('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>

    <?php $this->session->data['success'] = null; } ?>

    <div class="panel panel-default">

        <div class="panel-heading">

            <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;<span style="vertical-align:middle;font-weight:bold;"><?php echo $heading_title; ?></span></h3>

            <div class="storeSwitcherWidget">

            	<div class="form-group">

                	<!-- <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pushpin"></span>&nbsp;<?php echo $store['name']; if($store['store_id'] == 0) echo " <strong>(".$text_default.")</strong>"; ?>&nbsp;<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button> -->

                	<ul class="dropdown-menu" role="menu">

                    	<?php foreach ($stores  as $st) { ?>

                    		<li><a href="index.php?route=module/currencyrates&store_id=<?php echo $st['store_id'];?>&token=<?php echo $this->session->data['token']; ?>"><?php echo $st['name']; ?></a></li>

                    	<?php } ?> 

                	</ul>

            	</div>

            </div>

        </div>

        <div class="panel-body">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 

                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />

                <div class="tabbable">

                    <div class="tab-navigation form-inline">

                        <ul class="nav nav-tabs mainMenuTabs" id="mainTabs">

                            <li><a href="#control_panel" data-toggle="tab"><i class="fa fa-power-off"></i>&nbsp;Control Panel</a></li>
                           <!--  <li><a href="#support" data-toggle="tab"><i class="fa fa-power-off"></i>&nbsp;Support</a></li> -->

                        </ul>

                        <div class="tab-buttons">

                            <button type="submit" class="btn btn-success save-changes"><i class="fa fa-check"></i>&nbsp;<?php echo $save_changes?></button>

                            <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><?php echo $button_cancel?></a>

                        </div> 

                    </div><!-- /.tab-navigation --> 

                    <div class="tab-content">

                        <div id="control_panel" class="tab-pane fade"><?php require_once(DIR_APPLICATION.'view/template/module/currencyrates/tab_controlpanel.php'); ?></div>
                       <!-- <div id="support" class="tab-pane fade"><?php require_once(DIR_APPLICATION.'view/template/module/currencyrates/tab_support.php'); ?></div> -->

                    </div> <!-- /.tab-content --> 

                </div><!-- /.tabbable -->

            </form>

        </div> 

    </div>

</div>

<?php echo $footer; ?>

<script type="text/javascript">

$(function() {
  var json, tabsState;
  $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    var href, json, parentId, tabsState;
    tabsState = localStorage.getItem("tabs-state");
    json = JSON.parse(tabsState || "{}");
    parentId = $(e.target).parents("ul.nav.nav-pills, ul.nav.nav-tabs").attr("id");
    href = $(e.target).attr('href');
    json[parentId] = href;
    return localStorage.setItem("tabs-state", JSON.stringify(json));
  });
  tabsState = localStorage.getItem("tabs-state");
  json = JSON.parse(tabsState || "{}");
  $.each(json, function(containerId, href) {
    var a_el = $("#" + containerId + " a[href=" + href + "]");
    $(a_el).parent().addClass("active");
    $(href).addClass("active in");
    return $(a_el).tab('show');
  });
  $("ul.nav.nav-pills, ul.nav.nav-tabs").each(function() {
    var $this = $(this);
    if (!json[$this.attr("id")]) {
      var a_el = $this.find("a[data-toggle=tab]:first, a[data-toggle=pill]:first"),
          href = $(a_el).attr('href');
      $(a_el).parent().addClass("active");
      $(href).addClass("active in");
      return $(a_el).tab("show");
    }
  });
});

</script>