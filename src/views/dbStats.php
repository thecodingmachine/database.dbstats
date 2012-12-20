<?php /* @var $this TdbmController */ ?>
<h1>Generate stat table</h1>

<p>By clicking the button below, you will automatically generate the stat table, and add triggers to the parent table.</p>

<form action="generate" method="post">
<input type="hidden" id="name" name="name" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<div>
<label for="dropIfExist" >Drop stat table if it already exists</label><input type="checkbox" name="dropIfExist" id="dropIfExist" value="true"></input>
</div>
<br/>
<br/>
<div style="clear: both">
	<button name="action" value="generate" type="submit">Generate stat table</button>
</div>
</form>