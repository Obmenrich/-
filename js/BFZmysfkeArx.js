jQuery(document).ready((function(t){t(".i-checks").iCheck({}),t(".top-menu li").hover((function(){t("> ul",this).stop().fadeIn(200)}),(function(){t("> ul",this).stop().fadeOut(200)})),t(".top-menu > li > ul.sub-menu").prev().addClass("sublist"),loaderTimeoutfc=null,function t(){if("btn btn-warning btn-sm exchangers_tabs_button_current"!=jQuery("#calcButton").attr("class")&&jQuery("td.current>a.firstValute").attr("rel")&&jQuery("td.current>a.changeValute").attr("rel")){var e=jQuery("td.current>a.firstValute").attr("rel"),n=jQuery("td.current>a.changeValute").attr("rel");updateTable(e,n,"")}initOtherPages(),loaderTimeoutfc&&(clearTimeout(loaderTimeoutfc),loaderTimeoutfc=null);let o=localStorage.getItem("timerefresh");loaderTimeoutfc=setTimeout(t,o)}(),t(".addcommtoogle").click((function(){return t(".formcommtoogle").toggle("fast",(function(){})),!1})),t(".delbtn").click((function(){var e=t(this).attr("row");swal({title:"Удалить отзыв?",text:"Удалить отзыв может только автор!",type:"warning",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:"Да, удалить!",cancelButtonText:"Нет, отмена!",closeOnConfirm:!1,closeOnCancel:!1},(function(n){n?(console.log(e),t.post(window.location.href,{comment_id:e,req:"delcoment"}),swal("Готово!","Проверьте свою почту и перейдите по ссылке указанной в ней для подтверждения.","success")):swal("Отмена","Вы отменили удаление.","error")}))})),t(document).on("click",".linkGoo",(function(){t(this).attr("data-name");var e=t(this).attr("data-href");return 4==t(this).attr("data-stat")?swal({title:"Внимание!",text:"Обменный пункт опасный. Есть множество негативных отзывов без ответа, либо замечен в мошенничестве. Переход не рекомендуется",icon:"warning",buttons:!0,dangerMode:!0}).then((t=>{t&&window.open(e)})):(ym(38773075,"reachGoal","Perehod"),window.open(e)),!1}))}));