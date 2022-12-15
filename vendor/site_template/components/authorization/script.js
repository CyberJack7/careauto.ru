$("document").ready(function () {
  $(":submit").on("click", function () {
    $("input[required]").addClass("req");
  });
  $(":radio").on("click", function () {
    $("input[required]").removeClass("req");
  });
  
  let btn = document.getElementsByClassName("btn-primary")[0];
  let attempts = parseInt(btn.id.match(/\d+/), 10);
  if (attempts == 0) {
    btn.style.display = "none";
    var secs = 30;
    var timer = setInterval(tick,1000);
    function tick(){
      if (secs >= 1) {
        document.getElementsByClassName("alert")[0].innerHTML = "Неверный логин или пароль! Вы потратили все попытки. Вход заблокирован на 30 секунд! Осталось " + --secs + " cек";
      } else {
        document.getElementsByClassName("alert")[0].remove();
        clearInterval(timer);
      }
    }
    setTimeout(function () {
      btn.removeAttribute("style");
      $.post("/vendor/site_template/components/authorization/component.php", {auth: ""});
    }, 31 * 1000);
  }
});
