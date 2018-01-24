function checkdata(){
    var telnum=myform.telnum.value;
    var passwd=myform.passwd.value;
    var username=myform.username.value;
    var qqnum=myform.qqnum.value;
    var remark=myform.remark.value;
    with(document.myform){
        var number='0123456789';
        for (var i = 0; i < telnum.value.length; i++) {
            var n=telnum.value.charAt(i);
            if (number.indexOf(n)==-1) {
                alert('联系电话必须全为数字');
                telnum.focus();
                return false;
            }else if(telnum.value.length!=11){
                alert('您输入的联系电话不是11位');
                telnum.focus();
                return false;
            }
        }
        if(passwd.value.length<6){
            alert('密码长度不能小于6位');
            passwd.focus();
            return false;
        }else if (passwd.value.length>20) {
            alert('密码长度不能超出20');
            passwd.focus();
            return false;
        }else{
            return true;
        }
        var letters='#$%*';
        for (var i = 0; i < username.value.length; i++) {
            var ch=username.value.charAt(i);
            if (letters.indexOf(ch)>-1) {
                alert('姓名包含非法字符');
                username.focus();
                return false;
            }
        }
        for (var i = 0; i < qqnum.value.length; i++) {
            var n=qqnum.value.charAt(i);
            if (qqnum.indexOf(n)==-1) {
                alert('QQ必须全为数字');
                qqnum.focus();
                return false;
            }
        }

    //    去除备注验证消息
    //     $('#MaintainEntryForm').data('bootstrapValidator').validateField('remark');
    //     $('#MaintainEntryForm').data('bootstrapValidator')
    //         .updateStatus('remark', 'NOT_VALIDATED', null)
    //         .validateField('remark');
    }
}