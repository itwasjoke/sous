

jQuery(document).ready(function($) {

    function setCookie(cName, cValue, expDays,path) {
        let date = new Date();
        date.setTime(date.getTime() + (expDays * 24 * 60 * 60));
        const expires = "expires=" + date.toUTCString();
        document.cookie = cName + "=" + cValue + "; " + expires + "; path="+path;
    }

    let status=["Не начато", "В процессе","Выполнено", "Подтверждено", "Просрочено"];

    $('.header_burger').click(function(){
        $('.header_burger,.header_menu').toggleClass('active1');
    });
    $('#havesous').change(function(){
        $('input[name=school_f]').toggleClass('hide');
    });


    function validate(email) {
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address = email;
        if(reg.test(address) == false) {
            return false;
        } else {
            return true;
        }
    }

   $('.close').click(function(){
        var num=$(this).attr('notif');
        var path=$('.block_notif').attr('id');
        setCookie('notif'+num,1,15,path);
        $('.block_notif').remove();
   }); 

    $('.cofirm_button_js').click(function(){
        // alert(confirm_user.val());
        var data = {
            action: 'confirm_users',
            user_id: $(this).val() 
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                $('#confirm'+response.data.user_id).remove();
                $('#p_conf'+response.data.user_id).removeClass('confirm_user_p');
                $('#p_conf'+response.data.user_id).addClass('post_member');
                $('#p_conf'+response.data.user_id).text('Должность: ');
                $('<span class="desc_user'+response.data.user_id+'">отсутствует</span>').appendTo('#p_conf'+response.data.user_id);
                $('<div class="start_edit_user" level="2" id="'+response.data.user_id+'"><img src="http://sous.spb.ru/wp-content/themes/Api_itwasjoke/img/pencil.png"></div>').appendTo('#div_user_id'+response.data.user_id);
                edit_user();
            },
        });
    });
    function edit_user(){
    $('.start_edit_user').click(function(){
        $('.delete_user').removeClass('hide');
        $('.edit_level_user').removeClass('hide');
        $("#root_lbl").text('Права в сообществе');
        id=$(this).attr('id');
        $('.delete_user').attr('iduser',id);
        leader=$(this).attr('leader');
        if (leader=="yes"){
            $('.delete_user').addClass('hide');
            $('.edit_level_user').addClass('hide');
            $("#root_lbl").text('Это автор соуса');
        }
        var data = {
            action: 'get_user_data',
            user_id: id 
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                var level="";
                if (response.data.wp_user_level==1){
                    level="Назначить администратором";
                    $('.edit_level_user').attr('id',1);
                }
                else{
                    level="Разжаловать администратора";
                    $('.edit_level_user').attr('id',2);

                };
                $('.edit_level_user').text(level);
                contact=$('.contact_info'+response.data.ID).text();
                // $('.contact_info_form').text($('.contact_info'+response.data.ID).text());
                $('.contact_info_form').text(contact);
                $('.contact_info_form').attr('href',"https://"+contact);
                // alert(response.data.description);
                $('#name_user_edit').val(response.data.first_name+" "+response.data.last_name);
                $('#edit_desc_input').val(response.data.description);
                $('.confirm_desc_user').attr('id',response.data.ID);
                $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                $('#edit_user_team').addClass('modal-open');
                $('.modal-background').addClass('z-modal');
                $('.modal-background').addClass('modal-open');
            },
        });
    });

     $('.cancel_desc_user').click(function(){
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('#edit_user_team').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);

    });

     $('.delete_user').click(function(){
        $('#edit_user_team').removeClass('modal-open');
        $('#del_us_form').addClass('modal-open');
        id=$('.delete_user').attr('iduser');
        $('.confirm_del_us').attr('iduser',id);

     })
     $('.cancel_del_us').click(function(){
        $('#edit_user_team').addClass('modal-open');
        $('#del_us_form').removeClass('modal-open');

     })
     $('.confirm_del_us').click(function(){
        iduser=$(this).attr('iduser');
        team=$(this).attr('team');
        // alert(iduser);
        // alert(team);
        var data={
            action: 'delete_item',
            info: 'user',
            id_user: iduser,
            id_team: team,
        };
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                $('#div_user_id'+response.data.id_member).remove();
                 $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
                $('#del_us_form').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
            }
        });
     })

    $('.edit_level_user').click(function(){
        id=$(this).attr('id');
        var level="";
        if (id==1){
            level="Разжаловать администратора";
            $(this).attr('id',2);
            $(this).text(level);
        }
        else {
            level="Назначить администратором";
            $(this).attr('id',1);
            $(this).text(level);
        };
        
    });
    $('.confirm_desc_user').click(function(){
        var id=$(this).attr('id');
        $(this).attr('id','');
        var user_post=$('#edit_desc_input').val();
        $('.desc_user'+id).text(user_post);
        $('#name_user_edit').text('');
        $('#edit_desc_input').val('');
        var level=$('.edit_level_user').attr('id');
        var level_old=$('#'+id).attr('level');
        if (level!=level_old){
            if (level==1){
                jQuery('#div_user_id'+id).detach().appendTo('#users_members');
                $('#'+id).attr('level',1);
            }
            else if (level==2){
                jQuery('#div_user_id'+id).detach().appendTo('#users_admins');
                $('#'+id).attr('level',2);
            }
        }
        // alert(id_u);
        var data = {
            action: 'update_user_desc',
            user_id: id,
            user_val: user_post,
            user_level: level
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(){
                $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
                $('#edit_user_team').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
            },
        });

    });
    }
    edit_user();
    $('.start_edit_team').click( function(){
        $('.confirm_user_p').addClass('hide');
        var name=$('#name_team_p').text();
        var desc=$('#desc_team_p').text();
        var school=$('#school_team_p').text();
        $('#name_team_js').val(name);
        $('#desc_team_js').val(desc);
        $('#school_team_js').val(school);
        $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
        $('#edit_team').addClass('modal-open');
        $('.modal-background').addClass('z-modal');
        $('.modal-background').addClass('modal-open');
    });



    $('.confirm_desc_team').click(function(){
        var name=($('#name_team_js').val()).trim();
        var desc=($('#desc_team_js').val()).trim();
        var school=($('#school_team_js').val()).trim();
        if (name=="" || desc=="" || school==""){
            $('.confirm_user_p').removeClass('hide');
        }
        else{
        var id=$('.confirm_desc_team').attr('id');
        var data={
            action: 'update_team_info',
            name_team: name,
            desc_team: desc,
            school_team: school,
            id_team: id
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(){
                
                 $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
                $('#edit_team').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
                $('#name_team_js').val('');
                $('#desc_team_js').val('');
                $('#school_team_js').val('');
                
            },
        });
        $('#name_team_p').text(name);
        $('#desc_team_p').text(desc);
        $('#school_team_p').text(school);
        }
    });
    $('.cancel_desc_team').click(function(){
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('#edit_team').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
    })

    $(".task_action").click(function(){
        // id_last=$(this).attr('last_id');
        
        task_action=$(this).attr('id');
        $('#archive_input').attr('name',task_action);
        if (task_action=="submitted"){
            $('.btn_create_task').addClass('go_btn');
            $('#received').removeClass("active_sel");
            $('#submitted').addClass("active_sel");
            $('#btn_archive').text('Архив отправленных задач');
        }
        else{
            $('.btn_create_task').removeClass('go_btn');
            $('#submitted').removeClass("active_sel");
            $('#received').addClass("active_sel");
            $('#btn_archive').text('Архив полученных задач');
        }
        
        $('#load_more').attr('last_id','1');
        $('#load_more').attr('act',String(task_action));
        $('#load_more').removeClass('hide');
        var data={
            action: 'get_tasks',
            user_id: $(this).attr('user_id'),
            task_t: task_action,
            team_id: $(this).attr('team')
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                // alert(response.data.length);
                $("#all_tasks").empty("div");
                id_task = response.data[0].ID;
                count_tasks=0;
                response.data.forEach(function(elem){
                    $('<div id="'+elem['ID']+'" class="task_div" task="'+elem['task_type']+'">'+
                        '<p class="task_name">'+elem['post_title']+'</p>'+
                        '<p class="task_date">Дата сдачи: '+elem['deadline']+'</p>'+
                        '<p class="task_fromto">'+elem['user_fromto']+'</p>'+
                        // '<p class="task_desc">'+elem['post_content']+'</p>'+
                        // '<div class="notneedinfo">'+
                        // '<p class="task_date">Дата сдачи: <span>'+elem['deadline']+'</span></p>'+
                        // '<p class="task_fromto">'+elem['user_fromto']+'</p>'+
                        // '</div>'+
                        '<p class="task_status status'+elem['status']+'">'+status[elem['status']]+'</p>'+
                        '</div>').appendTo("#all_tasks");
                        count_tasks++;
                        edit_tasks_js();
                });
                if (count_tasks<5){
                    $('#load_more').addClass('hide');
                }
                
            },
        });
    });
    $('#load_more').click(function(){
        id_last=$(this).attr('last_id')*5;
        // alert(id_last);
        var data={
            action: 'get_more_posts',
            user_id: $(this).attr('user_id'),
            task_t: $(this).attr('act'),
            team_id: $(this).attr('team'),
            last: id_last,
            tp: '!=',
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                count_tasks=0;
                response.data.forEach(function(elem){
                    
                    $('<div id="'+elem['ID']+'" class="task_div" task="'+elem['task_type']+'">'+
                        '<p class="task_name">'+elem['post_title']+'</p>'+
                        '<p class="task_date">Дата сдачи: '+elem['deadline']+'</p>'+
                        // '<p class="task_desc">'+elem['post_content']+'</p>'+
                        // '<div class="notneedinfo">'+
                        '<p class="task_fromto">'+elem['user_fromto']+'</p>'+
                        // '</div>'+
                        '<p class="task_status status'+elem['status']+'">'+status[elem['status']]+'</p>'+
                        '</div>').appendTo("#all_tasks");
                        count_tasks++;
                        edit_tasks_js();
                        
                });
                if (count_tasks<5){
                    $('#load_more').addClass('hide');
                }
                else{
                    last_id=$('#load_more').attr('last_id');
                    last_id=parseInt(last_id)+1;
                    $('#load_more').attr('last_id',String(last_id));
                }
                
            }
        });
    });

     function del_task_arh_f(){
        $('.btn_del_task_arch').click(function(){
            id_task=$(this).attr('del_task');
            // alert(id_task);
            act="task";
            var data={
                action: 'delete_item',
                task_id: id_task,
                info: act,
            }
            $.ajax({
                url:location.origin+"/wp-admin/admin-ajax.php",
                type:"POST",
                data: data,
                success: function(response){
                    // alert(response.data.task);
                    $('#'+response.data.task).remove();
                }
            })
        })
    }
    del_task_arh_f();
    

    $('#load_more_t').click(function(){
        id_last=$(this).attr('last_id')*5;
        // alert(id_last);
        var data={
            action: 'get_more_posts',
            user_id: $(this).attr('user_id'),
            task_t: $(this).attr('act'),
            team_id: $(this).attr('team'),
            last: id_last,
            tp: '=',
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                count_tasks=0;
                response.data.forEach(function(elem){
                    var res='';
                    var del='';
                    if (elem['show_del']){
                        del='<button class="btn_del_task_arch" del_task="'+elem['ID']+'">Удалить</button>';
                    }
                    if (elem['result']){
                        var res="<span>Результат:</span> "+elem['result'];
                    }
                    $('<div id="'+elem['ID']+'" class="div_task_arch">'+
                        del+
                        '<p class="task_name">'+elem['post_title']+'</p>'+
                        '<p class="task_desc">'+elem['post_content']+'</p>'+
                        '<p class="task_arch res_arch">'+res+'</p>'+
                        '<div class="notneedinfo">'+
                            '<p class="task_arch grey">Дата создания: '+elem['date_create']+'</p>'+
                            '<p class="task_arch">Дата выполнения: '+elem['deadline']+'</p>'+
                        '</div>'+
                        '<div class="notneedinfo">'+
                            '<p class="task_arch grey">Отправитель: '+elem['from']+'</p>'+
                            '<p class="task_arch">Получатель: '+elem['to']+'</p>'+
                        '</div>'+
                        '<p class="task_status status'+elem['status']+'">'+status[elem['status']]+'</p>'+
                    '</div>').appendTo("#all_tasks");
                    del_task_arh_f();
                });
                if (count_tasks<5){
                    $('#load_more_t').addClass('hide');
                }
                else{
                    last_id=$('#load_more_t').attr('last_id');
                    last_id=parseInt(last_id)+1;
                    $('#load_more_t').attr('last_id',String(last_id));
                }
                
            }
        });
    });

   

    function edit_tasks_js(){
    $('.task_div').click(function(){
        $('.confirm_user_p').addClass('hide');
        $("#result").val('');
        $('.vk_post').addClass('hide');
        // alert('hello');
        $('.vk_images').empty();
        $('.vk_text').empty();
        id=$(this).attr('id');
        var data={
            action: 'get_single_task',
            task_id: id
        };
        $.ajax({
            url: location.origin+'/wp-admin/admin-ajax.php',
            type:'POST',
            data: data,
            success: function(response){

                task_type=$('#'+response.data.ID).attr('task');
                if (task_type=="received"){
                    $('.title_info').text(response.data.post_title);
                    $('.desc_info').text(response.data.post_desc);
                    $('.reporter_info').text("Отправил(а): "+response.data.reporter);
                    // $('.assigne_info').text(response.data.assigne);
                    $('.date_info').text(response.data.post_date);
                    $('.deadline_info').text("Дедлайн: "+response.data.deadline);
                    // alert(response.data.result);
                    $("#result").val(response.data.result);
                    $("#save_from").attr('id_task', response.data.ID);
                    // alert(response.data.status);
                    if (response.data.status==4){
                        // alert('h');
                        $("#status_pros").text('Просрочено');
                        $("#sel_status_as").addClass('hide');
                    }
                    else if(response.data.status==3){
                        $("#status_pros").text('Подтверждено');
                        $("#result").attr('disabled','disabled');
                        $("#sel_status_as").addClass('hide');
                    }
                    else{
                        $('#option_status_'+response.data.status).prop('selected',true);
                    }
                    $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                    $('#edit_assigne_div').addClass('modal-open');
                    $('.modal-background').addClass('z-modal');
                    $('.modal-background').addClass('modal-open');
                }
                else{
                    timedate=response.data.deadline.split(' ');
                    // alert(timedate[0]);
                    date=timedate[0].split('.');
                    day=parseInt(date[0]);
                    month=parseInt(date[1]);
                    year=parseInt(date[2]);
                    // d=new Date(year,month,day);
                    d2=year+'-'+month+'-'+day;
                    // alert(d2);
                    var deadline_in=document.getElementById('date_of_deadline');
                    deadline_in.value = d2;
                    $("input[name=task_name_edit]").val(response.data.post_title);
                    $("textarea[name=task_desc_edit]").val(response.data.post_desc);
                    // $("input[name=task_date_ed]").val(d2);
                    // $("#task_date_id_ed").prop('value',String(timedate[0]));
                    // $("input[name=task_date_edit]").val(String(d));
                    $("input[name=task_time_edit]").val(timedate[1]);
                    // alert(response.data.result);
                    if (response.data.result){
                        $(".result_p").text(response.data.result);
                    }
                    else{
                        $(".result_p").text('Результата еще нет');
                    }
                    $('.status_p').text(status[response.data.status]);
                    if (response.data.post){
                         $('.vk_images').empty();
                        $('.vk_text').empty();
                        $(response.data.vk_text).appendTo('.vk_text');
                        $(response.data.vk_img).appendTo('.vk_images');
                        $('.vk_post').removeClass('hide');
                    }
                    // alert(response.data.status);
                    if ((response.data.status==4 || response.data.status==0) && !response.data.result){

                        $("#confirm_result_task").addClass('hide');
                    }
                   
                    // if (response.data.status==4){
                    //     $(".result_p").addClass('hide');
                    //     $("#res_lbl").addClass('hide');
                    // }
                    // alert(response.data.status);
                    // if (response.data.status==4){
                    //     $('#confirm_result_task').attr('confirm','true');
                    //     $('#confirm_result_task').attr('disabled','disabled');
                    //     $('#confirm_result_task').text('Подтвержено');
                    // }
                    else{
                        // $('#confirm_result_task').attr('confirm','false');
                        $('#confirm_result_task').text('Подтвердить выполнение');
                        $('#confirm_result_task').removeAttr('disabled');
                    }
                    $('#user'+response.data.asssigne_id).prop('selected',true);
                    $("#save_to").attr('id_task', response.data.ID);
                    $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                    $('#edit_reporter_div').addClass('modal-open');
                    $('.modal-background').addClass('z-modal');
                    $('.modal-background').addClass('modal-open');
                }
            }
        });
    });
    $("#delete_task").click(function() {
        $('#edit_reporter_div').removeClass('modal-open');
        $('#del_task_form').addClass('modal-open');
    })
    $('.cancel_del_task').click(function() {
        $('#edit_reporter_div').addClass('modal-open');
        $('#del_task_form').removeClass('modal-open');
    })

    $('.confirm_del_task').click(function() {
        id_task=$("#save_to").attr('id_task');
        // alert(id_task);
        act="task";
        var data={
            action: 'delete_item',
            task_id: id_task,
            info: act,
        }
        $.ajax({
            url:location.origin+"/wp-admin/admin-ajax.php",
            type:"POST",
            data: data,
            success: function(response){
                // alert(response.data.task);
                $('#'+response.data.task).remove();
                $('#del_task_form').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
            }
        })
    })

    $("#save_from").click(function(){
        id_task=$(this).attr('id_task');
        type_task="received";
        result_data=($("#result").val()).trim();
        // alert(result_data);
        if ($("#status_pros").text()=='Просрочено'){
            // alert('yes');
            status_sel=4;
        }
        else{
            // alert('no');
            status_sel=$("select[name=status_edit]").val();
            // alert(status_sel);
            // status_sel=2;
        }
        data={
            action: "update_task_sous",
            task_id: id_task,
            task_type: type_task,
            result: result_data,
            status: status_sel,
        };
        $.ajax({
            url:location.origin+"/wp-admin/admin-ajax.php",
            type:"POST",
            data: data,
            success: function(response){
                $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
                $('#edit_assigne_div').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
                $("#sel_status_as").removeClass('hide');
                $("#status_pros").text('');
                $("#result").removeAttr('disabled');
            },
        });

    });
    $('#save_to').click(function(){
        id_task=$(this).attr('id_task');
        type_task="submitted";
        title=($("input[name=task_name_edit]").val()).trim();
        desc=($("textarea[name=task_desc_edit]").val()).trim();
        // alert(desc);
        if (title=="" || desc==""){
            $('.confirm_user_p').removeClass('hide');
        }
        else{
        date=$("input[name=task_date_edit]").val();
        time=$("input[name=task_time_edit]").val();
        assigne=$("select[name=to_who_edit]").val();
        status_value=$('#confirm_result_task').attr('confirm');
        // $('#'+id_task).children('.task_name').text(title);
        // $('#'+id_task).children('.task_name').text(title);
        // $('#'+id_task).children('.task_name').text(title);
        // var status_val=false;
        // alert(status_val);
        if (status_value=="1"){
            $("#"+id_task).remove();
            status_val=true;
        }
        else{
            status_val=false;
        }
        // alert(status_val);
        datetime=date+" "+time;
        date1=date.split('-');
        date2=date1[2]+'.'+date1[1]+'.'+date1[0];
        datetime2=date2+" "+time;
        $('#'+id_task).children('.task_name').text(title);
        $('#'+id_task).children('.task_desc').text(desc);
        $('#'+id_task).find('.task_date').empty();
        $('#'+id_task).find('.task_fromto').empty();
        $('#'+id_task).find('.task_date').append("Дата сдачи: <span>"+datetime2+"</span>");
        forwho=$("select[name=to_who_edit]").find('option:selected').text();
        $('#'+id_task).find('.task_fromto').append("Для кого: <span>"+forwho+"</span>");
        data={
            action: "update_task_sous",
            task_id: id_task,
            task_type: type_task,
            title: title,
            desc: desc,
            datetime: datetime,
            assigne: assigne,
            status: status_val,
        };
        $.ajax({
            url:location.origin+"/wp-admin/admin-ajax.php",
            type:"POST",
            data: data,
            success: function(response){
                $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
                $('#edit_reporter_div').removeClass('modal-open');
                $('.modal-background').removeClass('modal-open');
                setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
                $("confirm_result_task").removeClass('hide');
                $(".result_p").removeClass('hide');
                $("#res_lbl").removeClass('hide');
            },
        });
        }
    });

    }
    edit_tasks_js();

    $('#confirm_result_task').click(function(){
        var conf=$(this).attr('confirm');
        // alert(conf);
        if (conf==="0"){
            $(this).attr('confirm','1');
            $(this).text('Подтверждено');
        }
        else{
            $(this).attr('confirm','0');
            $(this).text('Подтвердить выполнение');
        }
    })

    $("#reporter_cancel").click(function() {
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('#edit_reporter_div').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
        $("confirm_result_task").removeClass('hide');
        $(".result_p").removeClass('hide');
        $("#res_lbl").removeClass('hide');
        $("#confirm_result_task").removeClass('hide');
    })
    $("#createtask_cancel").click(function() {
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('input[name=task_name]').val('');
        $('textarea[name=task_desc]').val('');
        $('input[name=task_date]').val('');
        $('input[name=task_time]').val('');
        $('#create_task_form_div').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
    })
    $('#save_from_cancel').click(function(){
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('#edit_assigne_div').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
        $("#sel_status_as").removeClass('hide');
        $("#status_pros").text('');
        $("#result").removeAttr('disabled');
    })
    $('.btn_create_task').click(function() {
        $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
        $('.confirm_user_p').addClass('hide');
        $('#create_task_form_div').addClass('modal-open');
        $('.modal-background').addClass('z-modal');
        $('.modal-background').addClass('modal-open');
    })
    $('#sbm_reg').click(function() {
        first_name=($('input[name=first_name]').val()).trim();
        second_name=($('input[name=second_name]').val()).trim();
        contact=($('input[name=contact]').val()).trim();
        classs=($('input[name=class]').val()).trim();
        email=($('input[name=email]').val()).trim();
        school=($('input[name=school_f]').val()).trim();
        havesouss=$('input[name=have_sous]').prop('checked');
        password=($('input[name=password]').val()).trim();
        agree=$('input[name=agree_politics]').prop('checked');
        // $('#error_sign_up').text('Некоторые поля пусты');
        // alert(email);
        if ((school==0 && havesouss) || first_name=="" || second_name=="" || contact=="" || classs=="" || email=="" || password=="" || !agree){
            $('#error_form').addClass('modal-open');
            // alert('yes');
            $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
            $('.modal-background').addClass('z-modal');
            $('.modal-background').addClass('modal-open');
        }
        else{
            var data={
                action: 'email',
                info: email,
                school,
            }
            $.ajax({
                url:location.origin+"/wp-admin/admin-ajax.php",
                type:"POST",
                data: data,
                success: function(response){
                    // alert(response.data.check);
                    if (response.data.check){
                        $('#error_sign_up').text('Такой Email уже существует');
                        $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                        $('#error_form').addClass('modal-open');
                        $('.modal-background').addClass('z-modal');
                        $('.modal-background').addClass('modal-open');
                    }
                    else{
                        if (response.data.team){
                            $('input[name=school]').val(response.data.team_id);

                        }
                        else{
                        $('#error_sign_up').text('Школа введена некорректно');
                        $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                        $('#error_form').addClass('modal-open');
                        $('.modal-background').addClass('z-modal');
                        $('.modal-background').addClass('modal-open');
                        }
                        $('#register_f').submit();
                    }
                }

            })
            
        }
    });
    $('#cancel_error').click(function() {
        $('.header').removeClass('header_none'); $('.header_menu').removeClass('header_none2');
        $('#error_sign_up').text('Возможно, некоторые поля пусты');
        $('#error_form').removeClass('modal-open');
        $('.modal-background').removeClass('modal-open');
        setTimeout(()=>$('.modal-background').removeClass('z-modal'),1000);
    })
    $('.btn_sum_in').click(function() {
        email=($('#user_login').val()).trim();
        pass=($('#user_pass').val()).trim();
        error_infot='';
        if (email=="" && pass==""){
            error_infot="Данные не введены";
            $('#error_info').text(error_infot);
            $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
            $('#error_form').addClass('modal-open');
            $('.modal-background').addClass('z-modal');
            $('.modal-background').addClass('modal-open');
        }
        else if(!validate(email)){
            error_infot="Email введен некорректно";
            $('#error_info').text(error_infot);
            $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
            $('#error_form').addClass('modal-open');
            $('.modal-background').addClass('z-modal');
            $('.modal-background').addClass('modal-open');
        }
        else if (email==""){
            error_infot="Email не введен";
            $('#error_info').text(error_infot);
            $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
            $('#error_form').addClass('modal-open');
            $('.modal-background').addClass('z-modal');
            $('.modal-background').addClass('modal-open');
        }
        else if(pass==""){
            error_infot="Пароль не введен";
            $('#error_info').text(error_infot);
            $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
            $('#error_form').addClass('modal-open');
            $('.modal-background').addClass('z-modal');
            $('.modal-background').addClass('modal-open');
        }
        else {
            data={
                action: "check_user",
                username: email,
                password: pass,
            };
            $.ajax({
                url:location.origin+"/wp-admin/admin-ajax.php",
                type:"POST",
                data: data,
                success: function(response){
                    // alert(response.data.exist);
                    // alert(response.data.error);
                    if (response.data.exist){
                        $('#sigin_loginform').submit();
                    }
                    else{
                        error_infot=response.data.error;
                        $('#error_info').text(error_infot);
                        $('.header').addClass('header_none'); $('.header_menu').addClass('header_none2');
                        $('#error_form').addClass('modal-open');
                        $('.modal-background').addClass('z-modal');
                        $('.modal-background').addClass('modal-open');
                    }
                    
                },
            });
        }
    })
    $("input[name=create_task]").click(function(){

        $("input[name=create_task]").disabled = true;
        task_name1=($("input[name=task_name]").val()).trim();
        task_desc1=($("textarea[name=task_desc]").val()).trim();
        if (task_name1=="" || task_desc1==""){
            $('.confirm_user_p').removeClass('hide');
        }
        else{
        team1=$(".task_action").attr('team');
        task_date1=$("input[name=task_date]").val();
        task_time1=$("input[name=task_time]").val();
        plan_checkbox=$('#isplan').prop('checked');
        $('#isplan').prop('checked','');
        if (plan_checkbox){
            plan_val='1';
        }
        else{
            plan_val='0';
        }
        // alert(plan_val);
        to_who1=$("#to_who").val();
        var data={
            action: 'create_task',
            task_name: task_name1,
            task_desc: task_desc1,
            task_date: task_date1,
            task_time: task_time1,
            team: team1,
            to_who: to_who1,
            plan: plan_val,
        }
        $.ajax({
            url: location.origin +'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: data,
            success: function(response){
                task_name1=$("input[name=task_name]").val('');
                task_desc1=$("textarea[name=task_desc]").val('');
                task_date1=$("input[name=task_date]").val('');
                task_time1=$("input[name=task_time]").val('');
                $('#isplan').prop('checked','');
                let elem=[];
                elem['ID']=response.data.id_task_post;
                elem['post_title']=response.data.task_name;
                elem['post_content']=response.data.task_desc;
                elem['deadline']=response.data.task_datetime;
                elem['user_fromto']=response.data.task_user_to;
                $('#create_task_form_div').removeClass('modal-open');
                $('.modal-background').removeClass('z-modal');
                $('.modal-background').removeClass('modal-open');
                $("input[name=create_task]").disabled = false;
                $('<div id="'+elem['ID']+'" class="task_div" task="submitted">'+
                    '<p class="task_name">'+elem['post_title']+'</p>'+
                    '<p class="task_desc">'+elem['post_content']+'</p>'+
                    '<div class="notneedinfo">'+
                    '<p class="task_date">Дата сдачи: <span>'+elem['deadline']+'</span></p>'+
                    '<p class="task_fromto">Для кого: <span>'+elem['user_fromto']+'</span></p>'+
                    '</div>'+
                    '<p class="task_status status0">'+status[0]+'</p>'+
                    '</div>').prependTo("#all_tasks");
                    edit_tasks_js();
                      
                
            }
        });
        }
    });
    $('input[name=class]').on('input',function(){
        val=$(this).val();
        if (val==""){
            $('input[name=edit_profile]').attr('disabled',true);
            $('.confirm_user_p').removeClass('hide');
        }
        else{
            $('.confirm_user_p').addClass('hide');
        }
    })
    $('input[name=contact]').on('input',function(){
        val=$(this).val();
        if (val==""){
            $('input[name=edit_profile]').attr('disabled',true);
            $('.confirm_user_p').removeClass('hide');
        }
        else{
            $('.confirm_user_p').addClass('hide');
        }
    })
    $('input[name=description]').on('input',function(){
        val=$(this).val();
        if (val==""){
            $('input[name=edit_profile]').attr('disabled',true);
            $('.confirm_user_p').removeClass('hide');
        }
        else{
            $('.confirm_user_p').addClass('hide');
        }
    })
    $('input[name=last_name]').on('input',function(){
        val=$(this).val();
        if (val==""){
            $('input[name=edit_profile]').attr('disabled',true);
            $('.confirm_user_p').removeClass('hide');
        }
        else{
            $('.confirm_user_p').addClass('hide');
        }
    })
    $('input[name=first_name]').on('input',function(){
        val=$(this).val();
        if (val==""){
            $('input[name=edit_profile]').attr('disabled',true);
            $('.confirm_user_p').removeClass('hide');
        }
        else{
            $('.confirm_user_p').addClass('hide');
        }
    })
});