<!DOCTYPE html>
<html lang="en">
<?php
// Начало сессии
session_start();

// Включение файла для подключения к базе данных
include('./db_connect.php');

// Буферизация вывода, чтобы сохранить заголовки и не выводить их до завершения скрипта
ob_start();

// Получение данных из таблицы "system_settings" и сохранение их в сессионной переменной
$system_settings_query = $conn->query("SELECT * FROM system_settings");
$system_settings = $system_settings_query->fetch_array();
foreach ($system_settings as $key => $value) {
    $_SESSION['system'][$key] = $value;
}

// Завершение буферизации вывода
ob_end_flush();

// Проверка наличия сессионной переменной "login_id" и перенаправление пользователя
if (isset($_SESSION['login_id'])) {
    header("location:index.php?page=home");
    exit; // Остановка выполнения скрипта после перенаправления
}
?>
<?php include 'header.php' ?>
<body class="myColorbody">
<div class="container">
      <!-- Login Form Start -->
      <div class="row justify-content-center wrapper" id="login-box">
        <div class="col-lg-10 my-auto myShadow">
          <div class="row">
            <div class="col-lg-7 myColorfonts p-4">
              <h1 class="text-center  myColorLogin">Войдите в учетную запись</h1>
              <hr class="my-3" />
              <form action="#" method="post" class="px-3" id="login-form">
                <div class="input-group input-group-lg form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text rounded-0"><i class="far fa-envelope fa-lg fa-fw"></i></span>
                  </div>
                  <input type="email" id="email" name="email" class="form-control rounded-0" placeholder="E-Mail" required />
                </div>
                <div class="input-group input-group-lg form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg fa-fw"></i></span>
                  </div>
                  <input type="password" id="password" name="password" class="form-control rounded-0" minlength="5" placeholder="Password" required autocomplete="off" />
                </div>
                <div class="form-group clearfix">
                  <div class="custom-control custom-checkbox float-left">
                    <input type="checkbox" class="custom-control-input" id="customCheck" name="rem" />
                    
                  </div>
                </div>
                <div class="form-group">
                  <input type="submit" id="login-btn" value="Войти" class="btn btn-primary btn-lg btn-block myBtn" />
                </div>
              </form>
            </div>
            <div class="col-lg-5 d-flex flex-column justify-content-center myColor p-4">
              <h1 class="text-center font-weight-bold text-white">Добро пожаловать!</h1>
              <hr class="my-3 bg-light myHr" />
              <p class="text-center font-weight-bolder text-light lead">Пожалуйста, заполните свои личные данные, чтобы начать работать с нами!</p>
            </div>
          </div>
        </div>
      </div>
      <script>
$(document).ready(function(){
  // Когда документ загружен и готов
  $('#login-form').submit(function(e){
    // Предотвращаем стандартное поведение отправки формы (перезагрузку страницы)
    e.preventDefault();
    // Запускаем функцию start_load() (предположительно, это функция, которая начинает анимацию загрузки)

    // Если форма содержит предупреждение об ошибке, удаляем его
    if($(this).find('.alert-danger').length > 0 )
      $(this).find('.alert-danger').remove();

    // Отправляем асинхронный POST-запрос на сервер
    $.ajax({
      url: 'ajax.php?action=login', // URL для отправки запроса
      method: 'POST', // Метод запроса
      data: $(this).serialize(), // Сериализуем данные формы для отправки
      error: function(err){
        // Обработка ошибки (вывод в консоль и, предположительно, вызов функции end_load() для завершения анимации загрузки)
        console.log(err);
        end_load();
      },
      success: function(resp){
        // Обработка успешного ответа от сервера
        if(resp == 1){
          // Если ответ равен 1, перенаправляем пользователя на страницу 'index.php?page=home'
          location.href ='index.php?page=home';
        } else {
          // Если ответ не равен 1, добавляем предупреждение об ошибке в форму
          $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
          // Завершаем анимацию загрузки с помощью функции end_load()
          end_load();
        }
      }
    });
  });
});
</script>
<?php include 'footer.php' ?>


</body>
</html>