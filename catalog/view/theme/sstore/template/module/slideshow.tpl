
    <div class="slideshow-box">
        <div id="slideshow<?php echo $module; ?>" class="owl-carousel" style="opacity: 1;">
          <?php foreach ($banners as $banner) { ?>
          <div class="item">
            <?php if ($banner['link']) { ?>
            <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
            <?php } ?>
          </div>
          <?php } ?>
        </div>
    </div>

<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?>').owlCarousel({
	items: 1,
	autoplay:<?= !IS_DEV ? 1 : 0 ?>,
    autoplaySpeed:1500,
    autoplayHoverPause:true,
    loop: true,
	singleItem: true,
	nav: true,
    navText:['<span class="glyphicon glyphicon-menu-left"></span>','<span class="glyphicon glyphicon-menu-right"></span>'],
	dots: true
});
--></script>