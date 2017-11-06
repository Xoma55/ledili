<style>

    .pr-show-tooltip2 + div {
        display: none;
        position: absolute;
        top: 54%;
        left: 0%;
        box-shadow: 0px 3px 9px rgba(0, 0, 0, 0.21);
        border-radius: 5px;
        padding: 15px 10px;
        z-index: 100;
        width: 200%;
        background: rgb(255, 255, 255) none repeat scroll 0% 0%;
    }

    .pr-show-tooltip2 + div > p {
        text-indent: 15px;
    }

    body .box-main p {
        margin-bottom: 0px;
    }

</style>


<script>
    $(document).ready(function () {

        $('.pr-show-tooltip2').hover(function () {


            var element2 = $(this);
            element2.toggleClass('active');
            setTimeout(function(){
                if(element2.hasClass('active')){
                    var parentEl = element2.closest('.item');
                    var elarray = parentEl.closest('.js-wrapper').find('.item');
                    var ind = elarray.index(parentEl);
                    if (ind == 0 || ind == 4 || ind == 8 || ind == 12 || ind == 16){
                        element2.next('div').css('left', '0px');
                    } else if (ind == 3 || ind == 7 || ind == 11 || ind == 15 || ind == 19){
                        element2.next('div').css('left', '0%');
                    }

                    if ((elarray.length - ind) < 5){
                        element2.next('div').css('top', '54%');
                    }

                    element2.next('div').fadeIn('fast');
                } else {
                    element2.next('div').fadeOut('fast');
                    element2.next('div').css('top', '54%');
                    element2.next('div').css('left', '0%');
                }
            },200);

        });
    });

</script>

<?php if($position == "column_left" OR $position == "column_right") { ?>

<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-content module-left">
        <div id="owl-viewed-column" class="owl-carousel owl-theme">
             
            <?php 
                $col = 0;
                $all = 1;
                $allproducts = count($products);
                foreach ($products as $product) {   
                $col++;   
              if($col == 1) echo "<div class=\"item\">";
            ?>
                <div class="product-box-item">
                    <div class="swiper-slide">
                     <?php if ($product['product_stickers']) { ?>
				        <div class="sticker-box-cat">
				          <?php foreach ($product['product_stickers'] as $product_sticker) { ?>
				            <span class="stickers-cat" style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;">
				              <?php echo $product_sticker['text']; ?>
				            </span>
				          <?php } ?>
				        </div>
				        <?php } ?>   
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
      $('#owl-viewed-column').owlCarousel({
        loop:true,
        autoplay:<?= !IS_DEV ? 1 : 0 ?>,
        autoplaySpeed:1500,
        autoplayHoverPause:true,
        nav:true,
        dots:false,
        navText:['<span class="glyphicon glyphicon-menu-left"></span>','<span class="glyphicon glyphicon-menu-right"></span>'],
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




<?php } else { ?>

                    <div class="box-main js-wrapper">
						<div class="news-set center">
						<h4 class="inner"><span><?php echo $heading_title; ?></span></h4>
						</div>
					<div class="category-slide">
                        
                            <div id="cat-viewed" style="display: block; opacity: 1;">

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
                                            
                                            <div class="cart">

                                                <?php if($product['stock_status_id']=="8") { ?>
                                                    <a onclick="preorder('<?php echo $product['product_id']; ?>','<?php echo $product['price']; ?>');" class="button-cart" title="<?php echo $button_preorder; ?>"><i class="fa fa-clock-o" aria-hidden="true"></i></a>

                                                <?php } elseif($product['stock_status_id']=="6" || $product['stock_status_id']=="7") { ?>
                                                    <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button-cart" title="<?php echo $button_cart; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                                <?php } elseif($product['stock_status_id']=="5") { ?>
                                                <a class="out-of-stock-button">
                                                    Закончился
                                                </a>
                                                <?php } ?>


                                                 <!-- <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button-cart" title="<?php echo $button_cart; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a> -->
                                                 <a onclick="addToWishList('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_wishlist; ?>" class="wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                                 <a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>" class="compare"><i class="fa fa-exchange" aria-hidden="true"></i></a>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="name">
                                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                            </div>
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
									          <?php for ($i = 1; $i <= 5; $i++) { ?>
									          <?php if ($product['rating'] < $i) { ?>
									          <span><i class="fa fa-star-o stars-rev"></i></span>
									          <?php } else { ?>
									          <span><i class="fa fa-star stars-rev"></i></span>
									          <?php } ?>
									          <?php } ?>
									        </div>
									        <?php } ?>

                                            <?php if($product['stock_status_id']=='5') { ?>
                                            <p style="color: #D3514F;"><?php echo $product['stock_status']; ?></p>
                                            <?php } elseif($product['stock_status_id']=='7') { ?>
                                            <p style="color: #00AA00;"><?php echo $product['stock_status']; ?></p>
                                            <?php } elseif($product['stock_status_id']=='6') { ?>
                                            <div style="color: #C96300;"><?php echo $product['stock_status']; ?>
                                                <i style="color: grey;cursor:pointer;" class="fa fa-info-circle pr-show-tooltip2"></i>
                                                <div style="display: none;">
                                                    <p>Наличие этого товара уточняйте у менеджеров после оформления заказа.</p>
                                                    <p>Будьте внимательны, мы не гарантируем наличие этого товара.</p>
                                                </div>
                                            </div>
                                            <?php } elseif($product['stock_status_id']=='8') { ?>
                                            <p style="color: #354dc9;"><?php echo $product['stock_status']; ?></p>
                                            <?php } ?>


                                            <!--
                                            <?php if($product['stock_status_id']=='5') { ?>
                                            <p style="color: #D3514F;"><?php echo $product['stock_status']; ?></p>
                                            <?php } elseif($product['stock_status_id']=='7') { ?>
                                            <p style="color: #00AA00;"><?php echo $product['stock_status']; ?></p>
                                            <?php } elseif($product['stock_status_id']=='6') { ?>
                                            <p style="color: #C96300;"><?php echo $product['stock_status']; ?></p>
                                            <?php } elseif($product['stock_status_id']=='8') { ?>
                                            <p style="color: #354dc9;"><?php echo $product['stock_status']; ?></p>
                                            <?php } ?>
                                            -->

                                        </div>

                                    <?php } ?>

                                    <?php $product_count++; ?>

                                <?php } ?>

                                </div>
                            
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <?php echo isset($preorder_data)?$preorder_data:''; ?>

                        <?php /*<script>
                        $(document).ready(function() {
                          $('#cat-viewed').owlCarousel({
                            loop:true,
                            autoplay:<?= !IS_DEV ? 1 : 0 ?>,
                            autoplaySpeed:1500,
                            autoplayHoverPause:true,
                            nav:true,
                            dots:false,
                            navText:['<span class="glyphicon glyphicon-menu-left"></span>','<span class="glyphicon glyphicon-menu-right"></span>'],
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
                                    items:5,
                                    nav:true,
                                    loop:false
                                }
                            }
                        });
                        });
                        
                        </script>*/?>
                        </div>
                       
<?php } ?>

