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
      <h2><i class="fa fa-reply"></i><?php echo $heading_title; ?></h2>
      <?php if ($returns) { ?>
      <div class="table-div wishlist-table">
	      <table class="table table-hover">
	        <thead>
	          <tr class="wishlist-tr">
	            <td class="text-center"><?php echo $column_return_id; ?></td>
	            <td class="text-center"><?php echo $column_status; ?></td>
	            <td class="text-center"><?php echo $column_date_added; ?></td>
	            <td class="text-center"><?php echo $column_order_id; ?></td>
	            <td class="text-center"><?php echo $column_customer; ?></td>
	            <td></td>
	          </tr>
	        </thead>
	        <tbody>
	          <?php foreach ($returns as $return) { ?>
	          <tr class="wishlist-content-tr order">
	            <td class="text-center">#<?php echo $return['return_id']; ?></td>
	            <td class="text-center"><?php echo $return['status']; ?></td>
	            <td class="text-center"><?php echo $return['date_added']; ?></td>
	            <td class="text-center"><?php echo $return['order_id']; ?></td>
	            <td class="text-center"><?php echo $return['name']; ?></td>
	            <td><a href="<?php echo $return['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
	          </tr>
	          <?php } ?>
	        </tbody>
	      </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
      </div>
      </article>
      </div>
      <div class="clearfix"></div>
  </div>
      <?php echo $content_bottom; ?>
<?php echo $footer; ?>