<style>
    body .bestsellers {
        height: auto;
    }
    body .category-slide {
        max-height: none;
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

</style>

<script>
    $(document).ready(function () {

        $('.pr-show-tooltip4').hover(function () {
            var element4 = $(this);
            element4.toggleClass('active');
            setTimeout(function(){
                if(element4.hasClass('active')){
                    var parentEl = element4.closest('.item');
                    var elarray = parentEl.closest('.js-wrapper').find('.item');
                    var ind = elarray.index(parentEl);
                    if (ind == 0 || ind == 4 || ind == 8 || ind == 12 || ind == 16){
                        element4.next('div').css('left', '0px');
                    } else if (ind == 3 || ind == 7 || ind == 11 || ind == 15 || ind == 19){
                        element4.next('div').css('left', '-100%');
                    }

                    if ((elarray.length - ind) < 5){
                        element4.next('div').css('top', '140px');
                    }

                    element4.next('div').fadeIn('fast');
                } else {
                    element4.next('div').fadeOut('fast');
                    element4.next('div').css('top', '105%');
                    element4.next('div').css('left', '-45%');
                }
            },200);

        });
    });
</script>

<?php if($position == "column_left" OR $position == "column_right") { ?>

<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-content module-left">
        <div id="owl-best-column" class="owl-carousel owl-theme">
             
            <?php 
                $col = 0;
                $all = 1;
                $allproducts = count($products);
                foreach ($products as $product) {   
                $col++;   
              if($col == 1) echo "<div class=\"item\">";
            ?>
                <div class="product-box-item">
                <?php if ($product['product_stickers']) { ?>
									        <div class="sticker-box-cat">
									          <?php foreach ($product['product_stickers'] as $product_sticker) { ?>
									            <span class="stickers-cat" style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;">
									              <?php echo $product_sticker['text']; ?>
									            </span>
									          <?php } ?>
									        </div>
									        <?php } ?>    
                    <div class="swiper-slide">
                        <?php if ($product['thumb']) { ?>
                            <a class="img-box" href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                        <?php } ?>
                        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                        <?php if ($product['price']) { ?>
                        
                          <?php if (!$product['special']) { ?>
                           <span class="price-new"><?php echo $product['price']; ?></span>
                          <?php } else { ?>
                          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                          <?php } ?>
                        
                        <?php } ?>
                       <?php if ($product['rating']) { ?>
				        <div class="rating">
				          <?php for ($i = 1; $i <= 5; $i++) { ?>
				          <?php if ($product['rating'] < $i) { ?>
				          <span><i class="fa fa-star-o stars-rev"></i></span>
				          <?php } else { ?>
				          <span><i class="fa fa-star stars-rev"></i></span>
				          <?php } ?>
				          <?php } ?>
				        </div>
				        <?php } ?>
                    </div>
               </div> 
             <?php 
             if($col == 2) echo "</div>";
             if($allproducts == $all && $col == 1) echo  "</div>";
             if($col == 2) $col = 0;
             $all++;
             } ?> 
      	  </div>		
    </div>
</div> 
      
    <script>
    $(document).ready(function() {
      $('#owl-best-column').owlCarousel({
        loop:true,
        autoplay:<?= !IS_DEV ? 1 : 0 ?>,
        autoplaySpeed:1500,
        autoplayHoverPause:true,
        nav:true,
        dots:false,
        navText: ['<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>', '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>'],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:1,
                nav:false
            },
            1000:{
                items:1,
                nav:true,
                loop:true
            }
        }
    });
    });




    </script>


<?php } else { ?>

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
                                         <?php if ($product['product_stickers']) { ?>
									        <div class="sticker-box-cat">
									          <?php foreach ($product['product_stickers'] as $product_sticker) { ?>
									            <span class="stickers-cat" style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;">
									              <?php echo $product_sticker['text']; ?>
									            </span>
									          <?php } ?>
									        </div>
									        <?php } ?>

                                            <div class="image">
                                                <a href="<?php echo $product['href']; ?>">
                                                    <?php if (!empty($product['thumb'])) { ?>
                                                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                                                    <?php } else { ?>
                                                        <img src="/image/no-thumb.png" alt="<?php echo $product['name']; ?>" />
                                                    <?php } ?>
                                                </a>
                                            </div>

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

                                            <div class="clearfix"></div>
                                            <div class="name">
                                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                            </div>
                                            <?php /* <div class="product-code">
                                                Код товара : <?= $product['product_code'] ?>
                                            </div> */?>
                                            <?php if ($product['price']) { ?>
                                            <div class="price">
                                                <?php if (!$product['special']) { ?>
                                                    <?php echo $product['price']; ?>
                                                <?php } else { ?>
                                                    <span class="price-old"><?php echo $product['price']; ?></span> <?php echo $product['special']; ?>
                                                <?php } ?>
                                            </div>
                                          <?php } ?>
                                         <?php if ($product['rating']) { ?>
								        <div class="rating">
								          <?php /*for ($i = 1; $i <= 5; $i++) { ?>
								          <?php if ($product['rating'] < $i) { ?>
								          <span><i class="fa fa-star-o stars-rev"></i></span>
								          <?php } else { ?>
								          <span><i class="fa fa-star stars-rev"></i></span>
								          <?php } ?>
								          <?php } */?>
								        </div>
								        <?php } ?>

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

                                        </div>

                                    <?php } ?>

                                    <?php $product_count++; ?>

                                    <?php } ?>



                                </div>
                            
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <?php echo isset($preorder_data)?$preorder_data:''; ?>


                        <?php /*
                        <script>
                        $(document).ready(function() {
                          $('#cat-bestseller').owlCarousel({
                            loop:true,
                            autoplay:<?= !IS_DEV ? 1 : 0 ?>,
                            autoplaySpeed:1500,
                            autoplayHoverPause:true,
                            nav:true,
                            dots:false,
                            navText: ['<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>', '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>'],
                            responsiveClass:true,
                            responsive:{
                                0:{
                                    items:1,
                                    nav:true
                                },
                                600:{
                                    items:2,
                                    nav:false
                                },
                                1000:{
                                    items:4,
                                    nav:true,
                                    loop:false
                                }
                            }
                        });
                        });
                        
                        </script>
                        */?>
                        </div>
<?php } ?>


