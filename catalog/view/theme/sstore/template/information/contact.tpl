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
  <div class="content"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6 contact-main'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9 contact-main'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12 contact-main'; ?>
    <?php } ?>
    <article id="content" class="<?php echo $class; ?>">
     
     <div class="col-md-6 text-center">
	     
	     <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
          <h2><?php echo $text_contact; ?></h2>
          <div class="form-group required">
              <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
          </div>
          <div class="clearfix"></div>
          <div class="form-group required">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
          </div>
          <div class="clearfix"></div>
          <div class="form-group required">
              <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?php echo $entry_enquiry; ?>"><?php echo $enquiry; ?></textarea>
              <?php if ($error_enquiry) { ?>
              <div class="text-danger"><?php echo $error_enquiry; ?></div>
              <?php } ?>
          </div>
           
          <?php if (isset($captcha)) { ?>
		  <div class="form-group">
            <div class="col-sm-12"><?php echo $captcha; ?></div>
          </div>
		    <?php } ?>
		    
          <div class="form-group buttons">
          <div>
            <input class="button" type="submit" value="<?php echo $button_submit; ?>" />
          </div>
        </div>
        </fieldset>
        
      </form>

	     
	 </div>    
	 <div class="col-md-6">
		 <?php if ($storeset_yamap_texts !='') { echo "<div class=\"map-box\">" . $storeset_yamap_texts . "</div>"; } ?>
		  		 
	</div> 
	<div class="clearfix"></div>
	
	<?php if ($storeset_yamaps !='') { 
        echo "<div class=\"map-box\">" . $storeset_yamaps . "</div>";      
     } ?>
	</div>
     
      </article>
          <div class="clearfix">
      </div>
</div>
      <?php echo $content_bottom; ?>
<?php echo $footer; ?>
