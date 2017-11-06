<?php

function renderCategory($category, $child_id,  $showToggle = false){ ?>
    <li class="active has-sub">
    <div class="design-line"></div>
        <p class="category-name-a"><?= $category['name'] ?></p>
        <?php if ($category['children']) { ?>
            <ul>
                <?php foreach ($category['children'] as $child) {
                    $class = '';
                    if ($child['category_id'] == $child_id) $class .= ' active';
                    if ($child['children2']) $class .= ' has-sub';
                    ?>
                    <li class="<?= $class ?>">
                        <a href="<?= $child['href'] ?>" class="list-group-item active">
                            <?= $child['name'] ?>
                        </a><span class="glyphicon glyphicon-triangle-right"></span>

                        <?php
                        // крестик на 'показать дочерние категории'
                        if ($showToggle) { ?>
                            <a style="<?= empty($child['children2']) ? 'display: none' : '' ?>"
                               class="<?= !empty($child['children2']) ? 'toggle-a' : '' ?>"></a>
                        <?php } ?>

                        <ul>
                            <?php foreach ($child['children2'] as $child_lv3) { ?>
                                <li class="<?= $child_id == $child_lv3['category_id'] ? 'active' : '' ?>">
                                    <a href="<?= $child_lv3['href'] ?>">
                                        <?= $child_lv3['name'] ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </li>
<?php }

// первый блок по диизайну идет отдельно
$firstCategory = array();
$firstCategory[] = array_shift($categories);

?>

<div class="box">
    <div class="box-content" id="sstore-3-level">
        <div id=""class="categiry-icon">акция</div>
        <div id=""class="categiry-icon">лидер</div>
        <div id=""class="categiry-icon">новинка</div>
        <?php foreach ($firstCategory as $category) { ?>
            <ul>
                <?= renderCategory($category, $child_id) ?>
            </ul>
        <?php } ?>

        <?php foreach ($categories as $category) { ?>
            <ul>
                <?= renderCategory($category,$child_id) ?>
            </ul>
        <?php } ?>
    </div>
</div>