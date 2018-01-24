$(document).ready(function(){
    HR.clickAddParentDepartment();
    HR.clickPostPageNext();
});
HR = {
    editItem:function(id, $url){
        var _token = $('meta[name="csrf-token"]').attr('content');
        $("#loading").fadeIn().fadeOut(10);
        $.ajax({
            type: "POST",
            url: $url,
            data: {id:id},
            headers: {'X-CSRF-TOKEN': _token },
            success: function(data){
                $('.loadForm').html(data);
                return false;
            }
        });
    },
    deleteItem:function(id, url) {
        var a = confirm(lng['txt_mss_confirm_delete']);
        var _token = $('meta[name="csrf-token"]').attr('content');
        $("#loading").fadeIn().fadeOut(10);
        if(a){
            $.ajax({
                type: 'get',
                url: url,
                data: {'id':id},
                headers: {'X-CSRF-TOKEN': _token },
                success: function(data) {
                    if((data.errors)) {
                        alert(data.errors)
                    }else {
                        window.location.reload();
                    }
                },
            });
        }
    },
    getFormData:function(frmElements){
        var out = {};
        var s_data = $(frmElements).serializeArray();
        for(var i = 0; i<s_data.length; i++){
            var record = s_data[i];
            out[record.name] = record.value;
        }
        return out;
    },
    addItem:function(elementForm, elementInput, btnSubmit, $url){
        $("#loading").fadeIn().fadeOut(10);
        var isError = false;
        var msg = {};
        $(elementInput).each(function(){
            var input = $(this);
            if ($(this).hasClass("input-required") && input.val() == '') {
                msg[$(this).attr("name")] = "※" + $(this).attr("title") + lng['is_required'];
                isError = true;
            }
        });
        if(isError == true) {
            var error_msg = '';
            $.each(msg, function(key, value) {
                error_msg = error_msg + value + "\n";
            });
            alert(error_msg);
            return false;
        }else{
            $(btnSubmit).attr("disabled", 'true');
            var data = HR.getFormData(elementForm);
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: $url,
                data: data,
                headers: {'X-CSRF-TOKEN': _token },
                success: function(data) {
                    $(btnSubmit).removeAttr("disabled");
                    if((data.isOk == 0)) {
                        alert(data.errors)
                    }else {
                        window.location.href = data.url;
                    }
                },
            });
        }
    },
    resetItem:function(elementKey, elementValue){
        $("#loading").fadeIn().fadeOut(10);
        $('input[type="text"]').val('');
        $(elementKey).val(elementValue);
        $('.frmHead').text('Thêm mới');
        $('.icChage').removeClass('fa-edit').addClass('fa-plus-square');
    },
    clickAddParentDepartment:function(){
        $('.list-group.ext li').click(function(){
            $('.list-group.ext li').removeClass('act');
            var parent_id = $(this).attr('data');
            var parent_title = $(this).attr('title');
            var Prel = $(this).attr('rel');
            var Psrel = $(this).attr('psrel');
            var Crel = $('#id_hiden').attr('rel');
            if(Prel != Crel){
                if(Psrel != Crel){
                    $(this).addClass('act');
                    $('#sps').show();
                    $('#department_parent_id').val(parent_id);
                    $('#orgname').text(parent_title);
                    $('#department_type').attr('disabled', 'disabled');
                }else{
                    alert('Bạn không thể chọn danh mục con làm cha.');
                }
            }else{
                alert('Bạn chọn danh mục cha khác.');
                $('#sps').hide();
                $('#orgname').text('');
                var datatmp = $('#department_parent_id').attr('datatmp');
                $('#department_parent_id').val(datatmp);
                $('#department_type').removeAttr('disabled');
            }
        });
    },
    clickPostPageNext:function(){
        $('.submitNext').click(function(){
            var department_name = $('#department_name').val();
            if(department_name != ''){
                $('#adminForm').append('<input id="clickPostPageNext" name="clickPostPageNext" value="clickPostPageNext" type="hidden">');
            }else{
                var _alert = "※" + $('#department_name').attr("title") + lng['is_required'];
                alert(_alert);
                return false;
            }
            $('#adminForm').submit();
        });
    },
}
