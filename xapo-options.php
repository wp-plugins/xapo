<div class="wrap">
	<h2>Xapo's WordPress Plugin</h2>

	<form method="post" action="options.php"> 
		<?php settings_fields('xapo-tipping'); ?>
		<?php do_settings_sections('xapo-tipping'); ?>

		<table class="form-table">
        <tr valign="top">
	        <th scope="row">Pay Type*</th>
	        <td>
	<?php
						$pay_selected = (get_option('pay_type') == 'Pay') ? ' selected' : '';
						$tip_selected = (get_option('pay_type') == 'Tip') ? ' selected' : '';
						$donate_selected = (get_option('pay_type') == 'Donate') ? ' selected' : '';
						$deposit_selected = (get_option('pay_type') == 'Deposit') ? ' selected' : '';

	?>
	        	<select name="pay_type">
	            <option value="Pay"<?php echo $pay_selected?>>Pay</option>
	            <option value="Tip"<?php echo $tip_selected?>>Tip</option>
	            <option value="Donate"<?php echo $donate_selected?>>Donate</option>
	            <option value="Deposit"<?php echo $deposit_selected?>>Deposit</option>
	          </select>
	        </td>
        </tr>
         
        <tr valign="top">
	        <th scope="row">Default Amount of Bits</th>
	        <td><input type="number" name="amount_bit" value="<?php echo esc_attr( get_option('amount_bit') ); ?>" /></td>
        </tr>

        <tr valign="top">
	        <th scope="row">Tip to Admin<dfn style="display: block;font-size: 10px;color: #545454;">By default the tips goes to author email</dfn></th>
	        <td>
	        	<?php $tip_to_admin_checked  = (get_option('tip_to_admin') == 'true') ? 'checked' : '' ; ?>
	        	<input type="checkbox" name="tip_to_admin" value="true" <?php echo $tip_to_admin_checked?>/> use wordpress admin's email instead of page/post author
        	</td>
        </tr>

        <tr valign="top">
	        <th scope="row">Tip to custom Xapo email<dfn style="display: block;font-size: 10px;color: #545454;">Will override author/admin email</dfn></th>
	        <td><input type="email" name="tip_to_custom" value="<?php echo esc_attr( get_option('tip_to_custom') ); ?>" /></td>
        </tr>
    </table>

		<?php submit_button(); ?>
	</form>

</div>