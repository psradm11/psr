<?php echo $header; ?><?php echo $column_left; ?>
	<div id="content">
		<div class="page-header">
			<div class="container-fluid">
				<div class="pull-right">
					<button type="submit" form="form-courier" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
				</div>
				<div class="panel-body">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-courier" class="form-horizontal">
						<div class="form-group required">
							<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
							<div class="col-sm-10">
								<?php foreach ($languages as $language) { ?>
									<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
										<input type="text" name="courier_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($name[$language['language_id']]) ? $name[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
									</div>
									<?php if (isset($error_name[$language['language_id']])) { ?>
										<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-location"><?php echo $entry_phone; ?></label>
							<div class="col-sm-10">
								<input id="phone" type="text" name="courier_phone" value="<?php echo isset($phone) ? $phone : ''; ?>" placeholder="<?php echo $entry_phone; ?>" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-location"><?php echo $entry_price; ?></label>
							<div class="col-sm-10">
								<input type="text" name="courier_price" value="<?php echo isset($price) ? $price : ''; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-location"><?php echo $entry_comment; ?></label>
							<div class="col-sm-10">
								<?php foreach ($languages as $language) { ?>
									<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
										<input type="text" name="courier_description[<?php echo $language['language_id']; ?>][comment]" value="<?php echo isset($comment[$language['language_id']]) ? $comment[$language['language_id']]['comment'] : ''; ?>" placeholder="<?php echo $entry_comment; ?>" class="form-control" />
									</div>
									<?php if (isset($error_name[$language['language_id']])) { ?>
										<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
							<div class="col-sm-10">
								<select name="courier_status" id="input-status" class="form-control">
									<?php if ($status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>






					</form>
				</div>
			</div>
		</div>
		<script src="view/javascript/mask.js"></script>
		<script>
			$(document).ready(function () {
				$('#phone').mask('0 (000) 000-00-00');
			});

		</script>
		<script type="text/javascript"><!--
			$('select[name=\'type\']').on('change', function() {
				if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox') {
					$('#custom-field-value').show();
					$('#display-value').hide();
				} else {
					$('#custom-field-value').hide();
					$('#display-value').show();
				}

				if (this.value == 'date') {
					$('#display-value > div').html('<div class="input-group date"><input type="text" name="value" value="' + $('#input-value').val() + '" placeholder="<?php echo $entry_value; ?>" data-date-format="YYYY-MM-DD" id="input-value" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>');
				} else if (this.value == 'time') {
					$('#display-value > div').html('<div class="input-group time"><input type="text" name="value" value="' + $('#input-value').val() + '" placeholder="<?php echo $entry_value; ?>" data-date-format="HH:mm" id="input-value" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>');
				} else if (this.value == 'datetime') {
					$('#display-value > div').html('<div class="input-group datetime"><input type="text" name="value" value="' + $('#input-value').val() + '" placeholder="<?php echo $entry_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>');
				} else if (this.value == 'textarea') {
					$('#display-value > div').html('<textarea name="value" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control">' + $('#input-value').val() + '</textarea>');
				} else {
					$('#display-value > div').html('<input type="text" name="value" value="' + $('#input-value').val() + '" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />');
				}

				$('.date').datetimepicker({
					pickTime: false
				});

				$('.time').datetimepicker({
					pickDate: false
				});

				$('.datetime').datetimepicker({
					pickDate: true,
					pickTime: true
				});
			});

			$('select[name=\'type\']').trigger('change');

			var custom_field_value_row = <?php echo $custom_field_value_row; ?>;

			function addCustomFieldValue() {
				html  = '<tr id="custom-field-value-row' + custom_field_value_row + '">';
				html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_id]" value="" />';
				<?php foreach ($languages as $language) { ?>
				html += '    <div class="input-group">';
				html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_custom_value; ?>" class="form-control" />';
				html += '    </div>';
				<?php } ?>
				html += '  </td>';
				html += '  <td class="text-right"><input type="text" name="custom_field_value[' + custom_field_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
				html += '  <td class="text-left"><button type="button" onclick="$(\'#custom-field-value-row' + custom_field_value_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
				html += '</tr>';

				$('#custom-field-value tbody').append(html);

				custom_field_value_row++;
			}
			//--></script></div>
<?php echo $footer; ?>