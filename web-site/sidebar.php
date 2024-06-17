  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
    <a href="./" class="brand-link">
    <?php
    switch ($_SESSION['login_type']) {
        case 1:
            echo '<h3 class="text-center p-0 m-0"><b>Администратор</b></h3>';
            break;
        case 2:
            echo '<h3 class="text-center p-0 m-0"><b>Менеджер-проекта</b></h3>';
            break;
        case 3:
            echo '<h3 class="text-center p-0 m-0"><b>Дизайнер</b></h3>';
            break;
        case 4:
            echo '<h3 class="text-center p-0 m-0"><b>Тестировщик</b></h3>';
            break;
        case 5:
            echo '<h3 class="text-center p-0 m-0"><b>Системный Админ</b></h3>';
            break;
        case 6:
            echo '<h3 class="text-center p-0 m-0"><b>Аналитик данных</b></h3>';
            break;
        case 7:
            echo '<h3 class="text-center p-0 m-0"><b>SEO-специалист</b></h3>';
            break;
        case 8:
            echo '<h3 class="text-center p-0 m-0"><b>Веб-разработчик</b></h3>';
            break;
        default:
            echo '<h3 class="text-center p-0 m-0"><b>Неизвестный тип пользователя</b></h3>';
            break;
    }
    ?>
</a>

    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Главная
              </p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_project nav-view_project">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
              Проекты
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if($_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 4 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 7): ?>
              <li class="nav-item">
                <a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Добавить новый проект</p>
                </a>
              </li>
            <?php endif; ?>
              <li class="nav-item">
                <a href="./index.php?page=project_list" class="nav-link nav-project_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Список проектов</p>
                </a>
              </li>
            </ul>
          </li> 
          <li class="nav-item">
                <a href="./index.php?page=task_list" class="nav-link nav-task_list">
                  <i class="fas fa-tasks nav-icon"></i>
                  <p>Задачи</p>
                </a>
          </li>
          <?php if($_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 4 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 7): ?>
           <li class="nav-item">
                <a href="./index.php?page=reports" class="nav-link nav-reports">
                  <i class="fas fa-th-list nav-icon"></i>
                  <p>Отчеты</p>
                </a>
          </li>
          <?php endif; ?>
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Пользователи
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Добавить пользователя</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Список Пользователей</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>