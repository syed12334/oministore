<?php
	if(count($getfranchise) >0) {
		?>
		<div class="form-group">
			<label>Pincodes</label>
		<?php
		foreach ($getfranchise as $key => $value) {
			?>
			<input type="hidden" name="pid[]" value="<?= $value->id;?>">
			<input type="number" name="pincode[]" value="<?= $value->pincode;?>" class="form-control" maxlength="6" min="0" minlength="6" onkeypress="if(this.value.length==6) return false;" style="margin-bottom: 15px">
			<?php
		}
		?>
		</div>
		<?php
	}
?>	