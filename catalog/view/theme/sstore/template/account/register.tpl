<?php echo $header; ?><?php echo $content_top; ?>

	<div id="container">
		<div id="registration-form">
			<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
			<?php } ?>
			<div class="content row">
				<article id="content" class="account-content col-md-10 col-md-offset-1">
					<h1><?php echo $heading_title; ?></h1>
					<p class="col-md-12"><?php echo $text_account_already; ?></p>
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
						<fieldset id="account" class="col-md-4">
							<legend><?php echo $text_your_details; ?></legend>
							<?php /*
							<div class="form-group required" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
								<label class="col-sm-3 control-label"><?php echo $entry_customer_group; ?></label>
									<?php foreach ($customer_groups as $customer_group) { ?>
										<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
											<div class="radio">
												<label>
													<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
													<?php echo $customer_group['name']; ?></label>
											</div>
										<?php } else { ?>
											<div class="radio">
												<label>
													<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
													<?php echo $customer_group['name']; ?></label>
											</div>
										<?php } ?>
									<?php } ?>
							</div> */?>
							<div class="form-group required">
									<input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
									<?php if ($error_firstname) { ?>
										<div class="text-danger"><?php echo $error_firstname; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
									<?php if ($error_lastname) { ?>
										<div class="text-danger"><?php echo $error_lastname; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
									<?php if ($error_email) { ?>
										<div class="text-danger"><?php echo $error_email; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
									<?php if ($error_telephone) { ?>
										<div class="text-danger"><?php echo $error_telephone; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
									<input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
							</div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="datepicker" placeholder="<?=$entry_birthday ?>" name="birthday" value="<?=$birthday_d?>">
                                <?php if ($error_birthday) { ?>
                                    <div class="text-danger"><?=$error_birthday ?></div>
                                <?php } ?>
                                <script>
                                    $( function() {

                                        $( "#datepicker" ).datepicker({
                                            dateFormat: 'yy-mm-dd',
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "1950:2016",
                                            monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                                            dayNamesMin: ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'ВС'],
                                            defaultDate: '1950-01-01'
                                            //minDate: '-84y'
                                            //maxDate: '-5y',
                                        });

                                    } );
                                </script>
                            </div>

							<?php foreach ($custom_fields as $custom_field) { ?>
								<?php if ($custom_field['location'] == 'account') { ?>
									<?php if ($custom_field['type'] == 'select') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
													<option value=""><?php echo $text_select; ?></option>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $register_custom_field[$custom_field['custom_field_id']]) { ?>
															<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'radio') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<div class="radio">
															<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $register_custom_field[$custom_field['custom_field_id']]) { ?>
																<label>
																	<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } else { ?>
																<label>
																	<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'checkbox') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<div class="checkbox">
															<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $register_custom_field[$custom_field['custom_field_id']])) { ?>
																<label>
																	<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } else { ?>
																<label>
																	<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'text') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'textarea') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'file') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
												<input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'date') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group date">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'time') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group time">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'datetime') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group datetime">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</fieldset>
						<fieldset id="address" class="col-md-4">
							<legend><?php echo $text_your_address; ?></legend>
							<div class="form-group required">
									<input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
									<?php if ($error_address_1) { ?>
										<div class="text-danger"><?php echo $error_address_1; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
									<?php if ($error_city) { ?>
										<div class="text-danger"><?php echo $error_city; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group fuck">
									<input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
									<?php if ($error_postcode) { ?>
										<div class="text-danger"><?php echo $error_postcode; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<select name="country_id" id="input-country" class="form-control">
										<option value=""><?php echo $text_select_country; ?></option>
											<?php foreach ($countries as $country) { ?>
											<?php if ($country['country_id'] == $country_id) { ?>
												<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<?php if ($error_country) { ?>
										<div class="text-danger"><?php echo $error_country; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<select name="zone_id" id="input-zone" class="form-control">
									</select>
									<?php if ($error_zone) { ?>
										<div class="text-danger"><?php echo $error_zone; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<?php foreach ($custom_fields as $custom_field) { ?>
								<?php if ($custom_field['location'] == 'address') { ?>
									<?php if ($custom_field['type'] == 'select') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
													<option value=""><?php echo $text_select; ?></option>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $register_custom_field[$custom_field['custom_field_id']]) { ?>
															<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'radio') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<div class="radio">
															<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $register_custom_field[$custom_field['custom_field_id']]) { ?>
																<label>
																	<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } else { ?>
																<label>
																	<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'checkbox') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div>
													<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
														<div class="checkbox">
															<?php if (isset($register_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $register_custom_field[$custom_field['custom_field_id']])) { ?>
																<label>
																	<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } else { ?>
																<label>
																	<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
																	<?php echo $custom_field_value['name']; ?></label>
															<?php } ?>
														</div>
													<?php } ?>
												</div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'text') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'textarea') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'file') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
												<input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'date') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group date">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'time') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group time">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($custom_field['type'] == 'datetime') { ?>
										<div id="custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
											<label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
											<div class="col-sm-9">
												<div class="input-group datetime">
													<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($register_custom_field[$custom_field['custom_field_id']]) ? $register_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
													<span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
												<?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
													<div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</fieldset>
						<fieldset id="password" class="col-md-4">
							<legend><?php echo $text_your_password; ?></legend>
							<div class="form-group required">
									<input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
									<?php if ($error_password) { ?>
										<div class="text-danger"><?php echo $error_password; ?></div>
									<?php } ?>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
									<input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
									<?php if ($error_confirm) { ?>
										<div class="text-danger"><?php echo $error_confirm; ?></div>
									<?php } ?>
							</div>
							<div id="newsletter">
								<div class="form-group">
									<label class="col-xs-3 control-label"><?php echo $entry_newsletter; ?></label>
									<div class="col-xs-9 radio">
										<?php if ($newsletter) { ?>
											<input class="register-radio" id="id600" type="radio" name="newsletter" value="1" checked="checked" />
											<label for="id600" class="radio-inline">
												<?php echo $text_yes; ?></label>
											<input class="register-radio" id="id601" type="radio" name="newsletter" value="0" />
											<label for="id601" class="radio-inline">
												<?php echo $text_no; ?></label>
										<?php } else { ?>
											<input class="register-radio" id="id600" type="radio" name="newsletter" value="1" />
											<label for="id600" class="radio-inline">
												<?php echo $text_yes; ?></label>
											<input class="register-radio" id="id601" type="radio" name="newsletter" value="0" checked="checked" />
											<label for="id601" class="radio-inline">
												<?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>

						<?php echo $captcha; ?>
						<?php if ($text_agree) { ?>
						<div class="buttons col-md-12">
							<div class="reg-text">
								<?php if ($agree) { ?>
									<input type="checkbox" id="check-agree" name="agree" value="1" checked="checked" />
									<label for="check-agree"></label>
								<?php } else { ?>
									<input type="checkbox" id="check-agree" name="agree" value="1" />
									<label for="check-agree"></label>
								<?php } ?>
								<span><?php echo str_replace("class=\"agree\"", "target=\"_blank\" class=\"agree-link\"", $text_agree); ?></span>
							</div>
							<div class="col-md-offset-4 col-md-4">
								<input type="submit" value="<?php echo $text_register; ?>" class="button btn-block" />
							</div>
						</div>
			</div>
			<?php } else { ?>
			<div class="buttons">
				<div>
					<input type="submit" value="<?php echo $text_register; ?>" class="button" />
				</div>
			</div>
			<?php } ?>
			<?php
				/*
				 пока нет регистрации через соц-сети
				 <ul class="register-socials">
				<li class="social-text"><?= $text_social_register;?></li>
				<li class="vk"></li>
				<li class="fb"></li>
				<li class="tw"></li>
			</ul>
				 */
			?>
			</form>
			</article>
			<div class="clearfix"></div>

		</div>
  </div>
      <?php echo $content_bottom; ?>
<script type="text/javascript"><!--
// Sort the custom fields
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});

$('#address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
		$('#address .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#address .form-group').length) {
		$('#address .form-group:first').before(this);
	}
});

$('input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/customfield&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}


		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
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
//--></script>
<script type="text/javascript"><!--
    $(document).ready(function(){
        $('#input-telephone, #input-fax').inputmask('+9(999)-99-99-999');
    });

$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
//				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select_region; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script></div>
<?php echo $footer; ?>