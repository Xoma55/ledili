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
  <div class="content row ">
    <article id="content" class="account-content col-xs-12">
      <div class="white-color" style="padding:15px">
          <h2><i class="fa fa-certificate"></i><?php echo $heading_title; ?></h2>
          <p><?php echo $text_description; ?></p>
          <fieldset class="col-xs-10 col-xs-offset-1">
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                  <div class="form-group required">
                      <input placeholder="<?php echo $entry_to_name; ?>" type="text" name="to_name" value="<?php echo $to_name; ?>" id="input-to-name" class="form-control" />
                      <?php if ($error_to_name) { ?>
                          <div class="text-danger"><?php echo $error_to_name; ?></div>
                      <?php } ?>
                  </div>
                  <div class="form-group required">
                      <input placeholder="<?php echo $entry_to_email; ?>" type="text" name="to_email" value="<?php echo $to_email; ?>" id="input-to-email" class="form-control" />
                      <?php if ($error_to_email) { ?>
                          <div class="text-danger"><?php echo $error_to_email; ?></div>
                      <?php } ?>
                  </div>
                  <div class="form-group required">
                      <input placeholder="<?php echo $entry_from_name; ?>" type="text" name="from_name" value="<?php echo $from_name; ?>" id="input-from-name" class="form-control" />
                      <?php if ($error_from_name) { ?>
                          <div class="text-danger"><?php echo $error_from_name; ?></div>
                      <?php } ?>
                  </div>
                  <div class="form-group required">
                      <input placeholder="<?php echo $entry_from_email; ?>" type="text" name="from_email" value="<?php echo $from_email; ?>" id="input-from-email" class="form-control" />
                      <?php if ($error_from_email) { ?>
                          <div class="text-danger"><?php echo $error_from_email; ?></div>
                      <?php } ?>
                  </div>
                  <div class="form-group required">
                      <select name="voucher_theme_id" class="form-control">
                          <?php foreach ($voucher_themes as $voucher_theme) { ?>
                              <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected>
                                      <?php echo $voucher_theme['name']; ?>
                                  </option>
                              <?php } else { ?>
                                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>">
                                      <?php echo $voucher_theme['name']; ?>
                                  </option>
                              <?php  } ?>
                          <?php  } ?>
                          <?php if ($error_reason) { ?>
                              <div class="text-danger"><?php echo $error_reason; ?></div>
                          <?php } ?>
                      </select>
                  </div>
                  <div class="form-group">
                          <textarea placeholder="<?php echo $entry_message; ?>" name="message" cols="40" rows="5" id="input-message" class="form-control"><?php echo $message; ?></textarea>
                  </div>
                  <div class="form-group">
                          <input placeholder="<?php echo $entry_amount; ?>" type="text" name="amount" value="<?php echo $amount; ?>" id="input-amount" class="form-control" size="5" />
                          <?php if ($error_amount) { ?>
                              <div class="text-danger"><?php echo $error_amount; ?></div>
                          <?php } ?>
                  </div>
                  <div class="buttons clearfix">
                      <div> <div class="reg-text"><?php echo $text_agree; ?>
                              <?php if ($agree) { ?>
                              <input type="checkbox" name="agree" value="1" checked="checked" /></div>
                          <?php } else { ?>
                          <input type="checkbox" name="agree" value="1" /></div>
                      <?php } ?>
                      &nbsp;
                      <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
                  </div>
      </div>
        </form>
          </fieldset>
      </div>
      </article>
      <div class="clearfix"></div>
  </div></div>
      <?php echo $content_bottom; ?>
<?php echo $footer; ?>