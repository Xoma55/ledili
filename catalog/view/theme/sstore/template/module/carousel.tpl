<div class="brands-slider">				
		<div id="slider<?php echo $module; ?>" class="owl-carousel owl-theme stage-width" style="display: block; opacity: 1;">
                            <?php foreach ($banners as $banner) { ?>
                                <div class="item">
                                    <div class="image">
                                        <a href="<?php echo $banner['link']; ?>">
                                            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" >
                                        </a>
                                    </div>    
								</div>
                            <?php } ?>
				
						</div>	
                        
						<div class="clearfix"></div>
						<script type="text/javascript">
						<!--//<![CDATA[
						$(document).ready(function(){
							  $('#slider<?php echo $module; ?>').owlCarousel({
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
                                        items:2,
                                        nav:true
                                    },
                                    600:{
                                        items:4,
                                        nav:true
                                    },
                                    1000:{
                                        items:7,
                                        nav:true
                                    }
                                }
							  });
							});
						//]]>-->
						</script>
</div>

