<style>
    body .bestsellers {
        height: auto;
    }
    body .category-slide {
        max-height: none;

    }
    body .category-slide .item {
        margin-bottom: 50px;
    }

    body a.out-of-stock-button {
        position: absolute;
        top: 100px;
    }

    .pr-show-tooltip4 + div {
        display: none;
        position: absolute;
        top: 105%;
        left: -45%;
        box-shadow: 0px 3px 9px rgba(0, 0, 0, 0.21);
        border-radius: 5px;
        padding: 15px 10px;
        z-index: 100;
        width: 200%;
        background: rgb(255, 255, 255) none repeat scroll 0% 0%;
    }

    .pr-show-tooltip4 + div > p {
        text-indent: 15px;
    }

    body .box-main p {
        margin-bottom: 0px;
    }

    body .box-main .category-slide {
        opacity: 1;
    }

    a.gift-button {
        background: #00AA00;
        color: white;
        padding: 7px 13px;
        border-radius: 0;
        line-height: 16px;
        position: relative;
        display: block;
        top: -20px;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: 50%;
        text-transform: uppercase;
        font-size: 11px;
    }


    body .gift-button {
        position: absolute;
        top: 177px;
    }
    .gift-button:hover {
        text-decoration: none;
        cursor: pointer;
    }

</style>


<div class="box-main bestsellers js-wrapper">

    <div class="news-set center">
        <p class="h4 inner"><span><?php echo $heading_title; ?></span></p>
    </div>

    <div class="category-slide">

        <div id="cat-bestseller" style="display: block; opacity: 1;">

            <div class="row">

                <?php
                $product_count = 1;
                foreach ($products as $product) { ?>

                <?php if ($product_count < 9) { ?>

                    <div class="item without-padding col-lg-3 col-md-4 col-sm-3 col-xs-12">
                        <div class="image">
                            <a href="<?php echo $product['href']; ?>">

                                <?php if (!empty($product['thumb'])) { ?>
                                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                                <?php } else { ?>
                                    <img src="/image/no-thumb.png" alt="<?php echo $product['name']; ?>" />
                                <?php } ?>
                            </a>
                        </div>

                        <!--
                        <?php if($product['stock_status_id']=='5') { ?>
                        <a class="out-of-stock-button">
                            Закончился
                        </a>
                        <?php } elseif($product['stock_status_id']=='6' || $product['stock_status_id']=='7') { ?>
                        <div class="cart" id="">
                            <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button-cart" title="<?php echo $button_cart; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <a onclick="addToWishList('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_wishlist; ?>" class="wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                            <a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>" class="compare"><i class="fa fa-exchange" aria-hidden="true"></i></a>
                        </div>
                        <?php } elseif($product['stock_status_id']=='8') { ?>
                        <div class="cart" id="">
                            <a onclick="preorder('<?php echo $product['product_id']; ?>','<?php echo $product['price']; ?>');" class="button-cart" title="<?php echo $button_preorder; ?>"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
                            <a onclick="addToWishList('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_wishlist; ?>" class="wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                            <a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>" class="compare"><i class="fa fa-exchange" aria-hidden="true"></i></a>
                        </div>
                        <?php } ?>
                        -->

                        <div class="clearfix"></div>
                        <div class="name">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        </div>


                        <!--
                        <?php if ($product['price']) { ?>
                            <div class="price">
                                <?php if (!$product['special']) { ?>
                                    <?php echo $product['price']; ?>
                                <?php } else { ?>
                                    <span class="price-old"><?php echo $product['price']; ?></span> <?php echo $product['special']; ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        -->

                        <!--
                        <?php if($product['stock_status_id']=='5') { ?>
                        <p style="color: #D3514F;"><?php echo $product['stock_status']; ?></p>
                        <?php } elseif($product['stock_status_id']=='7') { ?>
                        <p style="color: #00AA00;"><?php echo $product['stock_status']; ?></p>
                        <?php } elseif($product['stock_status_id']=='6') { ?>
                        <div style="color: #C96300;"><?php echo $product['stock_status']; ?>
                            <i style="color: grey;cursor:pointer;" class="fa fa-info-circle pr-show-tooltip4"></i>
                            <div style="display: none;">
                                <p>Наличие этого товара уточняйте у менеджеров после оформления заказа.</p>
                                <p>Будьте внимательны, мы не гарантируем наличие этого товара.</p>
                            </div>
                        </div>
                        <?php } elseif($product['stock_status_id']=='8') { ?>
                        <p style="color: #354dc9;"><?php echo $product['stock_status']; ?></p>
                        <?php } ?>
                        -->

                        <a class="gift-button" onclick="addGift();">
                            <i style="font-size: 14px;" class="fa fa-gift" aria-hidden="true"></i>
                            Добавить
                        </a>

            </div>

            <?php } ?>

            <?php $product_count++; ?>

            <?php } ?>



        </div>

    </div>
</div>
<div class="clearfix"></div>



