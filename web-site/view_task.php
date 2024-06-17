<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Задачи</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Статус</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-secondary'>Ожидаемый</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-primary'>В процессе</span>";
        	}elseif($status == 3){
		  		echo "<span class='badge badge-success'>Сделано</span>";
        	}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Описание</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
</div>