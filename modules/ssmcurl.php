<?php 
$task_id = $this->param[0];
?>
<div id="progress">none</div>
<script>
function updateProgress(val)
{
document.getElementById('progress').innerHTML = val.toString();
}
</script>
</body>
<iframe src="smcurl/id/<?php echo $task_id?>" style="display:none;"></iframe>
