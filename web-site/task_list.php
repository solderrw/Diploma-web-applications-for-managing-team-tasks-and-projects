<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
        <?php if($_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 4 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 7): ?>


            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Добавить новый проект</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-condensed" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Проекты</th>
                        <th>Задачи</th>
                        <th>Начало проекта</th>
                        <th>Срок выполнения проекта</th>
                        <th>Статус проекта</th>
                        <th>Статус задачи</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $where = "";
                    if ($_SESSION['login_type'] == 2) {
                        $where = " where p.manager_id = '{$_SESSION['login_id']}' ";
                    } elseif ($_SESSION['login_type'] == 3) {
                        $where = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                    }

                    $stat = array(
                        "Ожидаемый",
                        "Начатый",
                        "В процессе",
                        "Удержание",
                        "Просроченный",
                        "Сделано"
                    );

                    $qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
                    while ($row = $qry->fetch_assoc()):
                        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                        $desc = strtr(html_entity_decode($row['description']), $trans);
                        $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
                        $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
                        $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
                        $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                        $prog = $prog > 0 ?  number_format($prog, 2) : $prog;
                        $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['pid']}")->num_rows;
                        if ($row['pstatus'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])) {
                            if ($prod > 0 || $cprog > 0) {
                                $row['pstatus'] = 2;
                            } else {
                                $row['pstatus'] = 1;
                            }
                        } elseif ($row['pstatus'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
                            $row['pstatus'] = 4;
                        }
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++ ?></td>
                            <td>
                                <p><b><?php echo ucwords($row['pname']) ?></b></p>
                            </td>
                            <td>
                                <p><b><?php echo ucwords($row['task']) ?></b></p>
                                <p class="truncate"><?php echo strip_tags($desc) ?></p>
                            </td>
                            <td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
                            <td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
                            <td class="text-center">
                                <?php
                                echo "<span class='badge badge-" . ($row['pstatus'] >= 0 && $row['pstatus'] < count($stat) ? 'primary' : 'secondary') . "'>" . (isset($stat[$row['pstatus']]) ? $stat[$row['pstatus']] : 'Неизвестный статус') . "</span>";
                                ?>
                            </td>
                            <td>
                                <?php
                                $task_stat = array(
                                    "Ожидаемый",
                                    "Начатый",
                                    "В процессе",
                                    "Сделано"
                                   
                                );

                                if ($row['status'] == 1) {
                                    echo "<span class='badge badge-" . ($row['status'] >= 0 && $row['status'] < count($task_stat) ? 'primary' : 'secondary') . "'>" . (isset($task_stat[$row['status']]) ? $task_stat[$row['status']] : 'Неизвестный статус') . "</span>";
                                } elseif ($row['status'] == 2) {
                                    echo "<span class='badge badge-" . ($row['status'] >= 0 && $row['status'] < count($task_stat) ? 'primary' : 'secondary') . "'>" . (isset($task_stat[$row['status']]) ? $task_stat[$row['status']] : 'Неизвестный статус') . "</span>";
                                } elseif ($row['status'] == 3) {
                                    echo "<span class='badge badge-" . ($row['status'] >= 0 && $row['status'] < count($task_stat) ? 'primary' : 'secondary') . "'>" . (isset($task_stat[$row['status']]) ? $task_stat[$row['status']] : 'Неизвестный статус') . "</span>";
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Действие
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item new_productivity" data-pid='<?php echo $row['pid'] ?>' data-tid='<?php echo $row['id'] ?>' data-task='<?php echo ucwords($row['task']) ?>' href="javascript:void(0)">Повышение производительности</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    table p {
        margin: unset !important;
    }

    table td {
        vertical-align: middle !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('#list').dataTable({
            "oLanguage": {
                "sLengthMenu": "Показать _MENU_ записей",
                "sInfo": "Показано с _START_ по _END_ из _TOTAL_ записей",
                "sInfoEmpty": "Показано с 0 по 0 из 0 записей",
                "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
                "sSearch": "Поиск:",
                "oPaginate": {
                    "sFirst": "Первая",
                    "sLast": "Последняя",
                    "sNext": "Следующая",
                    "sPrevious": "Предыдущая"
                }
            }
        });
        $('.new_productivity').click(function() {
            uni_modal("<i class='fa fa-plus'></i> Новый прогресс для: " + $(this).attr('data-task'), "manage_progress.php?pid=" + $(this).attr('data-pid') + "&tid=" + $(this).attr('data-tid'), 'large')
        })
    });

    function delete_project($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_project',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Данные успешно удалены", 'Успех')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
