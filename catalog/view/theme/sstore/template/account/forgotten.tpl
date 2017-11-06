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
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
      <?php } ?>
      <div class="content">
        <article id="content" class="account-content">
          <h2><i class="fa fa-lock"></i><?php echo $heading_title; ?></h2>
          <p><?php echo $text_email; ?></p>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <fieldset class="col-md-6 col-md-offset-3">
              <div class="form-group required">
                  <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
            </fieldset>
            <div class="buttons clearfix">
              <div class="col-md-4 col-md-offset-4"><a href="<?php echo $back; ?>" class="button-back"><?php echo $button_back; ?></a></div>
              <div class="col-md-4 col-md-offset-4">
                <input type="submit" value="<?php echo $button_continue; ?>" class="button btn-block" />
              </div>
            </div>
          </form>
          </article>
          <div class="clearfix"></div>
    </div></div>
          <?php echo $content_bottom; ?>
<?php echo $footer; ?>