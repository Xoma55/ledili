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
  <div class="content white-color" style="padding: 15px">
    <article id="content" class="account-content">
      <h2><i class="fa fa-reply"></i><?php echo $heading_title; ?></h2>
      <p><?php echo $text_description; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset class="col-xs-12 col-md-6">
          <legend><?php echo $text_order; ?></legend>
          <div class="form-group required">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
          </div>
          <div class="form-group required">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
          </div>
          <div class="form-group required">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
          </div>
          <div class="form-group required">
              <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
          </div>
          <div class="form-group required">
              <input type="text" name="order_id" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              <?php if ($error_order_id) { ?>
              <div class="text-danger"><?php echo $error_order_id; ?></div>
              <?php } ?>
          </div>
          <div class="form-group">
              <div class="input-group date"><input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" placeholder="<?php echo $entry_date_ordered; ?>" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control" /><span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
          </div>
        </fieldset>
        <fieldset class="col-xs-12 col-md-6">
          <legend><?php echo $text_product; ?></legend>
          <div class="form-group required">
              <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              <?php if ($error_product) { ?>
              <div class="text-danger"><?php echo $error_product; ?></div>
              <?php } ?>
          </div>
          <div class="form-group required">
              <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              <?php if ($error_model) { ?>
              <div class="text-danger"><?php echo $error_model; ?></div>
              <?php } ?>
          </div>
          <div class="form-group">
              <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
          </div>
          <div class="form-group required">
              <select name="return_reason_id" class="form-control">
              <?php foreach ($return_reasons as $return_reason) { ?>
              <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
                    <option value="<?php echo $return_reason['return_reason_id']; ?>" selected>
                      <?php echo $return_reason['name']; ?>
                    </option>
              <?php } else { ?>
                      <option value="<?php echo $return_reason['return_reason_id']; ?>">
                          <?php echo $return_reason['name']; ?>
                      </option>
              <?php  } ?>
              <?php  } ?>
              <?php if ($error_reason) { ?>
              <div class="text-danger"><?php echo $error_reason; ?></div>
              <?php } ?>
              </select>
          </div>

            <div class="form-group required">
                <select name="opened" class="form-control">
                    <option value="1" <?= $opened ? 'selected' : '' ?>>Распакован</option>
                    <option value="0" <?= $opened ?  '' : 'selected' ?>>Не распакован</option>
                </select>
            </div>


          <div class="form-group">
              <textarea name="comment" rows="10" placeholder="<?php echo $entry_fault_detail; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
          </div>
          
              <?php if (isset($captcha)) { ?>
		    <?php echo $captcha; ?>
		    <?php } ?>
		   
           <?php if (isset($site_key)) { ?>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-9">
                <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                <?php if ($error_captcha) { ?>
                  <div class="text-danger"><?php echo $error_captcha; ?></div>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        </fieldset>
        <?php if ($text_agree) { ?>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-danger"><?php echo $button_back; ?></a></div>
          <div class="pull-right"><?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            <input type="submit" value="<?php echo $button_submit; ?>" class="button" />
          </div>
        </div>
        <?php } else { ?>
        <div class="buttons clearfix">
          <div><a href="<?php echo $back; ?>" class="button-back"><?php echo $button_back; ?></a></div>
          <div>
            <input type="submit" value="<?php echo $button_submit; ?>" class="button" />
          </div>
        </div>
        <?php } ?>
      </form>
      </article>
      </div>
      <div class="clearfix"></div>
  </div>
      <?php echo $content_bottom; ?>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<?php echo $footer; ?>
