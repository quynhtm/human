HR = {
    editItem:function(id, $url){
        var _token = $('meta[name="csrf-token"]').attr('content');
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
    resetItem:function($elementKey, $elementValue){
        $('input[type="text"]').val('');
        $($elementKey).val($elementValue);
        $('.frmHead').text('Thêm mới');
        $('.icChage').removeClass('fa-edit').addClass('fa-plus-square');
    },
}
