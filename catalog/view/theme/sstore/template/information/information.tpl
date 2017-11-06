<?php echo $header; ?>
<?php echo $content_top; ?>
    <div id="container">
        <div class="clearfix"></div>
        <div class="information-content row">
            <?php echo $column_left; ?><?php echo $column_right; ?>
            <?php if ($column_left && $column_right) { ?>
                <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
                <?php $class = 'col-sm-12 col-md-9'; ?>
            <?php } else { ?>
                <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <article id="content" class="<?php echo $class; ?>">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="content info-page">
                    <?php /* <h1><?php echo $heading_title; ?></h1> ABV*/?>
                    <?php echo $description; ?>
                </div>
                <div class="clearfix"></div>
                <?php echo $content_bottom; ?>
            </article>

            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div>
<?php echo $footer; ?>