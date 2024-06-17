<?php
include 'db_connect.php';
$stat = array(
    "Ожидаемый",
    "Начатый",
    "В процессе",
    "Удержание",
    "Просроченный",
    "Сделано"
);
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Название проекта</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Описание</b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Дата начало</b></dt>
								<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Дата конца</b></dt>
								<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
							<dt><b class="border-bottom border-primary">Статус</b></dt>
						<dd>
						<?php
						if($stat[$status] =='Ожидаемый'){
							echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
						} elseif($stat[$status] =='Начатый'){
							echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
						} elseif($stat[$status] =='В процессе'){
							echo "<span class='badge badge-info'>{$stat[$status]}</span>";
						} elseif($stat[$status] =='Удержание'){
							echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
						} elseif($stat[$status] =='Просроченный'){
							echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
						} elseif($stat[$status] =='Сделано'){
							echo "<span class='badge badge-success'>{$stat[$status]}</span>";
						}
                       ?>
                        </dd>
							<dl>
								<dt><b class="border-bottom border-primary">Менеджер проекта</b></dt>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['name']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Менеджер удален из базы данных</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Член/ы команды:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
			                        <!-- <span class="users-list-date">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Список задач:</b></span>
						<?php if($_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 4 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 7): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> Новая задача</button>
					</div>
				<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
					<table class="table table-condensed m-0 table-hover">
						<colgroup>
							<col width="5%">
							<col width="25%">
							<col width="30%">
							<col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
							<th>#</th>
							<th>Задачи</th>
							<th>Описание</th>
							<th>Статус</th>
							<th>Действие</th>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$tasks = $conn->query("SELECT * FROM task_list where project_id = {$id} order by task asc");
							while($row=$tasks->fetch_assoc()):
								$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
								unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
								$desc = strtr(html_entity_decode($row['description']),$trans);
								$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
							?>
								<tr>
			                        <td class="text-center"><?php echo $i++ ?></td>
			                        <td class=""><b><?php echo ucwords($row['task']) ?></b></td>
			                        <td class=""><p class="truncate"><?php echo strip_tags($desc) ?></p></td>
			                        <td>
			                        	<?php 
			                        	if($row['status'] == 1){
									  		echo "<span class='badge badge-secondary'>Ожидаемый</span>";
			                        	}elseif($row['status'] == 2){
									  		echo "<span class='badge badge-primary'>В процессе</span>";
			                        	}elseif($row['status'] == 3){
									  		echo "<span class='badge badge-success'>Сделано</span>";
			                        	}
			                        	?>
			                        </td>
			                        <td class="text-center">
										<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					                      Действие
					                    </button>
					                    <div class="dropdown-menu" style="">
					                      <a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Рассмотреть</a>
					                      <div class="dropdown-divider"></div>
					                      	<?php if($_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 4 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 7): ?>
					                      <a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Редактировать</a>
					                      <div class="dropdown-divider"></div>
					                      <a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Удалить</a>
					                  <?php endif; ?>
					                    </div>
									</td>
		                    	</tr>
							<?php 
							endwhile;
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Прогресс/активность участников</b>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Новая производительность</button>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$progress = $conn->query("SELECT p.*, concat(u.firstname,' ',u.lastname) as uname, u.avatar, t.task, 
					CASE
					   WHEN u.type = 1 THEN 'Администратор'
					   WHEN u.type = 2 THEN 'Менеджер-проекта'
					   WHEN u.type = 3 THEN 'Дизайнер'
					   WHEN u.type = 4 THEN 'Тестировщик'
					   WHEN u.type = 5 THEN 'Системный Админ'
					   WHEN u.type = 6 THEN 'Аналитик данных'
					   WHEN u.type = 7 THEN 'SEO-специалист'
					   WHEN u.type = 8 THEN 'Веб-разработчик'
					   ELSE 'Неизвестно'
					END AS role
					FROM user_productivity p 
					INNER JOIN users u ON u.id = p.user_id 
					INNER JOIN task_list t ON t.id = p.task_id 
					WHERE p.project_id = $id 
					ORDER BY unix_timestamp(p.date_created) DESC ");
					while($row = $progress->fetch_assoc()):
					?>
						<div class="post">

		                      <div class="user-block">
		                      	<?php if($_SESSION['login_id'] == $row['user_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Редактировать</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Удалить</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
								<span class="username">
									<a><?php echo ucwords($row['uname']) ?> <span class="badge badge-info ml-1"><?php echo $row['role'] ?></span><span class="badge badge-primary ml-1"><?php echo ucwords($row['task']) ?></span>
									</a>
								</span>
		                        <span class="description">
		                        	<span class="fa fa-calendar-day"></span>
		                        	<span><b><?php echo date('M d, Y',strtotime($row['date'])) ?></b></span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span>Начал: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['start_time'])) ?></b></span>
		                        	<span> | </span>
                      				<span>Закончил: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['end_time'])) ?></b></span>
	                        	</span>

	                        	

		                      </div>
		                      <!-- /.user-block -->
		                      <div>
		                       <?php echo html_entity_decode($row['comment']) ?>
		                      </div>

		                      <p>
								<a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
							</p>
	                    </div>
	                    <div class="post clearfix"></div>
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	$('#new_task').click(function(){
		uni_modal("Новая задача для <?php echo ucwords($name) ?>","manage_task.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.edit_task').click(function(){
		uni_modal("Редактировать задачу: "+$(this).attr('data-task'),"manage_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.view_task').click(function(){
		uni_modal("Детали задачи","view_task.php?id="+$(this).attr('data-id'),"mid-large")
	})
	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Новый прогресс","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Ход редактирования","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_progress').click(function(){
	_conf("Вы уверены, что хотите удалить этот прогресс?","удаление прогресс",[$(this).attr('data-id')])
	})
function _conf(msg, func, params) {
    if (confirm(msg)) {
        window[func](params);
    }
}

function delete_progress($id) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_progress',
        method: 'POST',
        data: { id: $id },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Данные успешно удалены", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert_toast("Ошибка при удалении данных", 'error');
            }
            end_load();
        },
        error: function() {
            alert_toast("Ошибка при удалении данных", 'error');
            end_load();
        }
    });
}

$(document).ready(function() {
    $('.delete_progress').click(function() {
        _conf("Вы уверены, что хотите удалить этот прогресс?", "delete_progress", [$(this).attr('data-id')]);
    });
});



function delete_task($id) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_task',
        method: 'POST',
        data: { id: $id },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Данные успешно удалены", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert_toast("Ошибка при удалении данных", 'error');
            }
            end_load();
        },
        error: function() {
            alert_toast("Ошибка при удалении данных", 'error');
            end_load();
        }
    });
}

$(document).ready(function() {
    $('.delete_task').click(function() {
        _conf("Вы уверены, что хотите удалить эту задачу?", "delete_task", [$(this).attr('data-id')]);
    });
});



</script>