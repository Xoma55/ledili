<?php echo $header; ?><?php echo $content_top; ?>
<div id="container">
  <div class="row">
    <div class="col-xs-12">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="content">
	  <article id="content" class="account-content">
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset class="col-md-6 col-md-offset-3">
          <div class="form-group required">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
          </div>
          <div class="clearfix"></div>
          <div class="form-group required">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
          </div>
          <div class="clearfix"></div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="col-md-4 col-md-offset-4"><a href="<?php echo $back; ?>" class="button-back"><?php echo $button_back; ?></a></div>
          <div class="col-md-4 col-md-offset-4">
            <input type="submit" value="<?php echo $button_continue; ?>" class="button btn-block" />
          </div>
        </div>
      </form>
      </article>
      </div>
      <div class="clearfix"></div>
</div>
      <?php echo $content_bottom; ?>
<?php echo $footer; ?>