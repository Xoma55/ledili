<?php if ($modules) { ?>
<column id="column-left" class="col-xs-3 shadow display-column">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</column>
<div class="sidebar-box-left clicked"></div>
<?php } ?>