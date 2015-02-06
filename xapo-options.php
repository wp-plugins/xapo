<div class="wrap">
	<h2>Xapo's WordPress Plugin</h2>

	<form method="post" action="options.php"> 
		<?php settings_fields('xapo-tipping'); ?>
		<?php do_settings_sections('xapo-tipping'); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">Pay Type</th>
				<td>
					<?php
					$pay_selected = (get_option('xapo_pay_type') == 'Pay') ? ' selected' : '';
					$tip_selected = (get_option('xapo_pay_type') == 'Tip') ? ' selected' : '';
					$donate_selected = (get_option('xapo_pay_type') == 'Donate') ? ' selected' : '';
					$deposit_selected = (get_option('xapo_pay_type') == 'Deposit') ? ' selected' : '';

					?>
					<select name="xapo_pay_type">
						<option value="Pay"<?php echo $pay_selected?>>Pay</option>
						<option value="Tip"<?php echo $tip_selected?>>Tip</option>
						<option value="Donate"<?php echo $donate_selected?>>Donate</option>
						<option value="Deposit"<?php echo $deposit_selected?>>Deposit</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Amount of bits<dfn style="display: block;font-size: 10px;color: #545454;">Blank if you want the reader to decide</dfn></th>
				<td><input type="number" name="xapo_amount_bit" value="<?php echo esc_attr( get_option('xapo_amount_bit') ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Tip to Admin<dfn style="display: block;font-size: 10px;color: #545454;">By default the tips goes to author email</dfn></th>
				<td>
					<?php $tip_to_admin_checked  = (get_option('xapo_tip_to_admin') == 'true') ? 'checked' : '' ; ?>
					<input type="checkbox" name="xapo_tip_to_admin" value="true" <?php echo $tip_to_admin_checked?>/> Use WordPress Admin's email instead of page/post author
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Tip to custom email<dfn style="display: block;font-size: 10px;color: #545454;">Will override author/admin email</dfn></th>
				<td><input type="email" name="xapo_tip_to_custom" value="<?php echo esc_attr( get_option('xapo_tip_to_custom') ); ?>" /></td>
			</tr>

			<tr valign="top">
			<th scope="row">Placement</th>
				<td>
					<?php $post_bottom  = (get_option('xapo_post_bottom') == 'true') ? 'checked' : '' ; ?>
					<input type="checkbox" name="xapo_post_bottom" value="true" <?php echo $post_bottom?>/> Display at the bottom of the post
			</tr>

		</table>

		<?php submit_button(); ?>
	</form>
	
	<p>Help us spread the word by <a href="http://ctt.ec/wlY8G">tweeting about</a> our plugin.<br>
		Have feedback or ideas? Please, <a href="mailto:feedback+wordpress@xapo.com?subject=WordPress plugin feedback">let us know</a>.</p>

	</div>