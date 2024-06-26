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
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Задача</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Описание</label>
			<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
				<?php echo isset($description) ? $description : '' ?>
			</textarea>
		</div>
		<div class="form-group">
			<label for="">Статус</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Ожидания</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>В процессе</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Сделано</option>
			</select>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){


	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'стиль', [ 'style' ] ],
            [ 'шрифт', [ 'полужирный', 'курсив', 'подчеркивание', 'зачеркивание', 'верхний индекс', 'подстрочный индекс', 'очистить'] ],
            [ 'имя шрифта', [ 'fontname' ] ],
            [ 'размер шрифта', [ 'fontsize' ] ],
            [ 'цвет', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'абзац', 'высота' ] ],
            [ 'таблица', [ 'table' ] ],
            [ 'просмотр', [ 'отменить', 'повторить', 'полноэкранный режим', 'просмотр кода', 'справка' ] ]
        ]
    })
     })
    
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Данные успешно сохранены',"Успех");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>