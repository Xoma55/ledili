<?php echo $header; ?><?php echo $column_left; ?>


<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-price_discount" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-price_discount" class="form-horizontal">

                    <div class="row">
                        <div class="col-sm-12 ">
                            <div class="pull-right ">
                                <a class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add; ?>" id="pd_btn_add"><i class="fa fa-plus-square-o"></i></a>
                                <a class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_delete; ?>" onclick="confirm('<?php echo $text_confirm; ?>') ? pd_del() : false;"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_discount_list; ?></label>
                        <div class="col-sm-10">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td><?php echo $title_threshold; ?></td>
                                <td><?php echo $title_discount; ?></td>
                                <td><?php echo $title_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($discount_list) { ?>
                            <?php foreach ($discount_list as $discount) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($discount['row_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $discount['row_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $discount['row_id']; ?>" />
                                    <?php } ?></td>
                                <td class="text-center"><?php echo $discount['threshold']; ?></td>
                                <td class="text-center"><?php echo $discount['discount']; ?></td>
                                <td class="text-center"><a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_edit; ?>" onclick="pd_edit('<?php echo $discount['row_id']; ?>','<?php echo $discount['threshold']; ?>','<?php echo $discount['discount']; ?>');"><i class="fa fa-edit"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-group"><span data-toggle="tooltip" title="<?php echo $help_pd_group; ?>"><?php echo $entry_group; ?></span></label>
                        <div class="col-sm-10">
                            <select name="price_discount_group_id" id="input-group" class="form-control">
                                <option value="0"><?php echo $text_none; ?></option>
                                <?php foreach ($groups as $group) { ?>
                                <?php if ($group['customer_group_id'] == $price_discount_group_id) { ?>
                                <option value="<?php echo $group['customer_group_id']; ?>" selected="selected"><?php echo $group['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $group['customer_group_id']; ?>"><?php echo $group['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                        <div class="col-sm-10">
                            <select name="price_discount_tax_class_id" id="input-tax-class" class="form-control">
                                <option value="0"><?php echo $text_none; ?></option>
                                <?php foreach ($tax_classes as $tax_class) { ?>
                                <?php if ($tax_class['tax_class_id'] == $price_discount_tax_class_id) { ?>
                                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="price_discount_status" id="input-status" class="form-control">
                                <?php if ($price_discount_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="price_discount_sort_order" value="<?php echo $price_discount_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prct">
    <div class="modal-dialog" role="document" style="top: 150px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title_modal; ?></h4>
                <button type="button" class="close modal-top-title" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="min-height: 170px;">

                <form id="pd_form">
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label" for="input-modal-threshold"><?php echo $entry_modal_threshold; ?></label>
                        <div class="col-sm-6">
                            <input type="text" name="price_discount_threshold" value="<?php echo $price_discount_threshold; ?>" placeholder="<?php echo $entry_modal_threshold; ?>" id="input-threshold" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group modal-last-field clearfix " >
                        <label class="col-sm-2 control-label" for="input-modal-discount"><?php echo $entry_modal_discount; ?></label>
                        <div class="col-sm-6">
                            <input type="text" name="price_discount_modal_discount" value="<?php echo $price_discount_modal_discount; ?>" placeholder="<?php echo $entry_modal_discount; ?>" id="input-modal-discount" class="form-control" />
                        </div>
                    </div>
                    <input name="row_id" value="" hidden>
                    <input name="pd_mode" value="" hidden>
                    <span id="js-modal-err" style="font-weight: bold;color: #ff6441">&nbsp;</span>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="pd_modal_btn_save"><?php echo $button_save; ?></button>
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>


<style>

    body .modal .modal-last-field {
        border-top: none;
    }

    body .close.modal-top-title{
        margin-top: -22px;
        font-size: 24px;
    }

</style>


<script>

    $(document).ready(function () {

        $('input[name="price_discount_threshold"]').mask('99999999');
        $('input[name="price_discount_modal_discount"]').mask('999');

        $('#pd_btn_add').click(function () {
            $('#prct .modal-title').text('Добавить новый порог');
            $('input[name="pd_mode"]').val('add');
            $('#prct').modal('show');
        });

        $('#pd_modal_btn_save').click(function () {

            var postData=$('#pd_form').serialize();

            $.post(
                'index.php?route=total/price_discount/action&token=<?php echo $token ?>',
                postData,
                function (res) {
                    if(res.error=='') {
                        $('#prct').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        },300);
                    } else {
                        $('#js-modal-err').text(res.error);
                    }
                },
                'json'
            );
        });

        $('#input-threshold,#input-modal-discount').focus(function () {
            $('#js-modal-err').html('&nbsp;');
        });

    });

    function pd_edit(row,t,d) {
        $('#prct .modal-title').text('Редактирование');
        $('input[name="pd_mode"]').val('edit');
        $('input[name="price_discount_threshold"]').val(t);
        $('input[name="price_discount_modal_discount"]').val(d);
        $('input[name="row_id"]').val(row);
        $('#prct').modal('show');
    }
    
    function pd_del() {
        var postData=$('#form-price_discount').serialize();

        $.post(
            'index.php?route=total/price_discount/delete&token=<?php echo $token ?>',
            postData,
            function (res) {
                location.reload();
            },
            'json'
        );

    }

</script>

<?php echo $footer; ?>