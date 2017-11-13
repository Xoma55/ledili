<div class="panel panel-default fastorder-panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><?php echo $heading_cart_block; ?><?php if ($weight) { ?>&nbsp;(<?php echo $weight; ?>)<?php } ?></h4>
  </div>
  <div class="panel-collapse">
    <div class="panel-body">
      <?php if ($attention) { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
      <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
      <div class="table-responsive">
        <table id="cart-table" class="table">
          <thead>
          <tr>
            <td colspan="2" class="text-left"><?php echo $column_name; ?></td>
            <td class="text-left"><?php echo $column_model; ?></td>
            <td class="text-left"><?php echo $column_quantity; ?></td>
            <td class="text-right"><?php echo $column_price; ?></td>
            <td class="text-right"><?php echo $column_total; ?></td>
            <td></td>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($products as $product) { ?>
            <tr>

              <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                <?php } ?></td>
              <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php if (!$product['stock']) { ?>
                  <span class="text-danger">***</span>
                <?php } ?>
                <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
                <?php } ?>
                <?php if ($product['reward']) { ?>
                  <br />
                  <small><?php echo $product['reward']; ?></small>
                <?php } ?>
                <?php if ($product['recurring']) { ?>
                  <br />
                  <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                <?php } ?>

                <?php if($product['stock_status_id']=='5') { ?>
                <p style="color: #D3514F;"><?php echo $product['stock_status']; ?></p>
                <?php } elseif($product['stock_status_id']=='7') { ?>
                <p style="color: #00AA00;"><?php echo $product['stock_status']; ?></p>
                <?php } elseif($product['stock_status_id']=='6') { ?>
                <div style="color: #C96300; position: relative;"><?php echo $product['stock_status']; ?> <i style="color: grey;cursor:pointer;" class="fa fa-info-circle pr-show-tooltip"></i>
                  <div style="display: none;">
                            <p>Наличие этого товара уточняйте у менеджеров после оформления заказа.</p>
                            <p>Будьте внимательны, мы не гарантируем наличие этого товара.</p>
                  </div>
                </div>

                <?php } elseif($product['stock_status_id']=='8') { ?>
                <p style="color: #354dc9;"><?php echo $product['stock_status']; ?></p>
                <?php } ?>

              </td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-left">
                <input name="product_id_q" value="<?php echo $product['product_id']; ?>" style="display: none;" type="hidden" />
                <input name="product_id" value="<?php echo $product['key']; ?>" style="display: none;" type="hidden" />
                <div class="input-group btn-block">
		                            <span class="input-group-btn order-prev-span">
		                            <a onclick="$(this).parent().next().val(~~$(this).parent().next().val()-1); update(this, 'update');" class="btn btn-primary order-prev"><i class="fa fa-minus"></i></a>
		                            </span>
                  <input type="text" name="quantity" value="<?php echo $product['quantity']; ?>" size="1" class="form-control order-quantity" onchange="update_manual(this, '<?php echo $product['key']; ?>'); return validate(this);" keypress="update_manual(this, '<?php echo $product['key']; ?>'); return validate(this);" />
                  <span class="input-group-btn order-next-span">
		                            <a onclick="$(this).parent().prev().val(~~$(this).parent().prev().val()+1); update(this, 'update');" class="btn btn-primary order-next"><i class="fa fa-plus"></i></a>
		                            </span>
                </div>
              </td>
              <td class="text-right col-price"><?php echo $product['price']; ?></td>
              <td class="text-right col-price">
                <?php if($product['discount']!=0) { ?>
                  <p style="color: grey;"><s><?php echo $product['total']; ?></s></p>
                  <p style="color: black;"><?php echo $product['total_disc']; ?> грн.</p>
                  <p style="color: #ff5400;font-size: 12px;">Скидка <?php echo $product['discount']; ?>%</p>
                <?php } else { ?>
                  <?php echo $product['total']; ?>
                <?php } ?>
              </td>
              <td class="text-center"><a onclick="update(this, 'remove');"><span class="cart-remove"></span></a></td>
            </tr>
          <?php } ?>
          <?php foreach ($vouchers as $vouchers) { ?>
            <tr>
              <td></td>
              <td class="text-left"><?php echo $vouchers['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-left"><div class="input-group btn-block">
                  <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                  <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="remove_voucher('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
              <td class="text-right"><?php echo $vouchers['amount']; ?></td>
              <td class="text-right"><?php echo $vouchers['amount']; ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      <?php if ($coupon || $voucher || $reward) { ?>
        <h3><?php echo $text_next; ?></h3>
        <p><?php echo $text_next_choice; ?></p>
        <div class="panel-group fastorder-panel-group" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?></div>
      <?php } ?>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-total">
            <?php
            // по дизайну выводится только один блок с конечной суммой
            //$totals = array(end($totals));
            foreach ($totals as $total) { ?>
              <tr>
                <!-- <td class="text-right"><?php echo $text_total_title; ?></td> -->
                <td class="text-right"><?php echo $total['title']; ?>
                <td class="text-right"><?php echo $total['text']; ?></td>
              </tr>
            <?php } ?>
          </table>
        </div>
      </div>
    </div>
    <button id="do-checkout">Оформить заказ</button>
  </div>
</div>

<div class="panel panel-default js-show-info-stock" style="display: <?php echo $show_warning==1?'block':'none'; ?>;">

  <div class="panel-heading">
    <span style="color: #ff5400;font-size: 14px;"><strong>ВНИМАНИЕ! Ваш Заказ содержит товары с неточным наличием!</strong></span>
  </div>
  <div class="panel-body">
    <p>Наличие этих товаров мы сможем уточнить после оформлениия Заказа в течении 1-2 дней.</p>
    <p>Будьте внимательны, мы не гарантируем доставку этого товара.</p>
  </div>
</div>

<?php echo isset($gift)?$gift:''; ?>

<script>
    $(document).ready(function () {

        $('.pr-show-tooltip').hover(function () {
            var element = $(this);
            element.toggleClass('active');
            setTimeout(function(){
                if(element.hasClass('active')){
                    element.next('div').fadeIn('fast');
                } else {
                    element.next('div').fadeOut('fast');
                }
            },200);

        });


    });
</script>

<style>
  .table-responsive{
    overflow-x: visible;
  }
  .pr-show-tooltip + div {
    display: none;
    position: absolute;
    top: 140%;
    left: -75%;
    box-shadow: 0px 3px 9px rgba(0, 0, 0, 0.21);
    border-radius: 5px;
    padding: 15px 10px;
    z-index: 100;
    width: 200%;
    background: rgb(255, 255, 255) none repeat scroll 0% 0%;
  }

  .pr-show-tooltip + div > p {
    text-indent: 15px;
  }



</style>
