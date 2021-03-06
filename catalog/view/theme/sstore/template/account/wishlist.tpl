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
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="content">
	<article id="content" class="account-content">
      <h2><i class="fa fa-heart"></i><?php echo $heading_title; ?></h2>
      <?php if ($products) { ?>
      <div class="table-div table-responsive wishlist-table">
      <table class="table table-hover">
        <thead>
          <tr class="wishlist-tr">
            <td colspan="3" class="text-left"><?php echo $column_name; ?></td>
            <td class="text-center"><?php echo $column_model; ?></td>
            <td class="text-center"><?php echo $column_stock; ?></td>
            <td class="text-center"><?php echo $column_price; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
          <tr class="wishlist-content-tr special">
              <td class="text-center"><button type="button" onclick="addToCart('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_cart; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
                  <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
              <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
              <?php } ?></td>
            <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
            <td class="text-center"><?php echo $product['model']; ?></td>
            <td class="text-center"><?php echo $product['stock']; ?></td>
            <td class="text-center"><?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
                <?php } ?>
              </div>
              <?php } ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
	      <div>
            <a href="/index.php?route=account/account" class="button"><?php echo $button_continue; ?></a>
	      </div>
	    </div>
      </article>
      </div>
      <div class="clearfix"></div>
  </div>
      <?php echo $content_bottom; ?>
<?php echo $footer; ?>