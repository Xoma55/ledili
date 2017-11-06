<?php if ($reviews) {
    $monthes = array(
        "1" => "января",
        "2" => "февраля",
        "3" => "марта",
        "4" => "апреля",
        "5" => "мая",
        "6" => "июня",
        "7" => "июля",
        "8" => "август",
        "9" => "сентября",
        "10" => "октября",
        "11" => "ноября",
        "12" => "декабря"
    );
    ?>
<?php foreach ($reviews as $review) { ?>
<div class="review-list col-md-6 col-xs-12">
  <div class="author">
      <div><?= $review['author'] ?></div>
      <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($review['rating'] < $i) { ?>
                  <span><i class="fa fa-star-o stars-rev"></i></span>
              <?php } else { ?>
                  <span><i class="fa fa-star stars-rev"></i></span>
              <?php } ?>
          <?php } ?>
      </div>
  </div>
  <div class="date"><?= date('d ', strtotime($review['date_added'])) . $monthes[date('n')] . date(' Y', strtotime($review['date_added'])) ?></div>
  <div class="clearfix"></div>

  <?php if (isset($oct_product_reviews_data['status']) && $oct_product_reviews_data['status']) { ?>
  <div class="reputation-buttons">
  	<?php if (isset($review['plus_reputation']) && false) { ?>
    <button class="plus-reputation" type="button" onclick="review_reputation('<?php echo $review['review_id']; ?>', '1');"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>
    <span id="plus-reputation-<?php echo $review['review_id']; ?>"><?php echo $review['plus_reputation']; ?></span>
    <?php } ?>
    <?php if (isset($review['minus_reputation']) && false) { ?>
    <button class="minus-reputation"  type="button" onclick="review_reputation('<?php echo $review['review_id']; ?>', '2');"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></button>
    <span id="minus-reputation-<?php echo $review['review_id']; ?>"><?php echo $review['minus_reputation']; ?></span>
    <?php } ?>
  </div>
  
    <?php if (isset($review['where_bought']) && false) { ?>
    <p><b><?php echo $text_where_bought; ?></b> <?php echo $review['where_bought']; ?></p>
    <?php } ?>

    <?php if (isset($review['positive_text']) && $review['positive_text'] && false) { ?>
    <p class="positive_text"><i class="fa fa-plus" aria-hidden="true"></i> <b><?php echo $entry_positive_text; ?></b></p>
    <p><?php echo $review['positive_text']; ?></p>
    <?php } ?>
    
    <?php if (isset($review['negative_text']) && $review['negative_text'] && false) { ?>
    <p class="negative_text"><i class="fa fa-minus" aria-hidden="true"></i> <b><?php echo $entry_negative_text; ?></b></p>
    <p><?php echo $review['negative_text']; ?></p>
    <?php } ?>
  
  	
  	<div class="text"><?php echo $review['text']; ?></div>

      <?php if (isset($review['admin_answer']) && $review['admin_answer']) { ?>
          <div class="admin_answer">
              <p class="admin_answer_text"><?php echo $text_admin_answer; ?></p>
              <p><?php echo $review['admin_answer']; ?></p>
          </div>
      <?php } ?>
  <?php } ?>
  	
</div>
<?php } ?>
<div class="text-right pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_reviews; ?></div>
<?php } ?>


