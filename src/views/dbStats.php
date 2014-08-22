<?php /* @var $this TdbmController */ ?>
<h1>Generate stat table</h1>

<p>By clicking the button below, you will automatically generate the stat table, and add triggers to the parent table.</p>

<form action="generate" method="post" >
<input type="hidden" id="name" name="name" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<div class="control-group">
	<div class="controls">
	<label>
		<input type="checkbox" name="dropIfExist" id="dropIfExist" value="true"></input>
		Drop stat table if it already exists</label>
	</div>
</div>



<div class="control-group">
	<div class="controls">
		<button name="action" value="generate" type="submit" class="btn">Generate stat table</button>
	</div>
</div>

</form>