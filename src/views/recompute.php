<?php /* @var $this TdbmController */ ?>
<h1>Recompute stats</h1>

<p>By clicking the button below, you will purge and recompute all the stat table, from the parent table's data.</p>

<form action="recompute" method="post">
<input type="hidden" id="name" name="name" value="<?php echo plainstring_to_htmlprotected($this->instanceName) ?>" />
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<div>
<label for="transaction" >Perform this in a transaction</label><input type="checkbox" name="transaction" id="transaction" value="true" checked="checked"></input>
</div>
<br/>
<div class="warning">Warning, this can be a long process. Running in a transaction is the only way to get precise stats, but this could halt any writing in the logs while the stats are being processed.</div>
<br/>
<div style="clear: both">
	<button name="action" value="recompute" type="submit">Generate stat table</button>
</div>
</form>