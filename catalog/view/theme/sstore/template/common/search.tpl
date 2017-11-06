<div id="search" class="hider">
    <div id="sb-search" class="sb-search sb-search-open">
    		<input class="sb-search-input" type="text" name="search" id="story" value="<?php echo $search; ?>" placeholder="<?php //echo $text_search; ?>" >
    		<input class="sb-search-submit" type="submit" value="">
    		<span class="sb-icon-search"><i class="fa fa-search"></i></span>
    		<div class="clearfix"></div>
    </div>  
</div>
<script>
	new UISearch( document.getElementById( 'sb-search' ) );
</script>