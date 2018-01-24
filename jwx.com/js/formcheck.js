function formblur(id,err)
{
    var patrn = /^(-)?\d+(\.\d+)?$/;
    var psw = /^[a-zA-Z]\w{5,17}$/;
    // var date = /^(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))-02-29)$/;
    var date = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/;
    $("#"+id).bind('input propertychange',function()
    {
        var value = $.trim($(this).val());
        console.log($("#"+id).val());
        if(eval(err))
        {
//                $(document).ready(function(){ $("#"+id).focus();})
            $("#"+id+"error").css("display","block");
            // $("#formsubmit").click(function() {return false});
            // alert("false");
        }
        else
        {
            $("#"+id+"error").css("display","none");
            // $("#formsubmit").click(function() {return true});
            // alert("true");
        }
    });

}

function aftersubmit(id,err)
{
    var v = $.trim($("#"+id).val());
    var patrn = /^(-)?\d+(\.\d+)?$/;
    var psw = /^[a-zA-Z]\w{5,17}$/;
    var date = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/;
    if(eval(err))
    {
        $("#"+id+"error").css("display","block");
    }
    else
    {
        $("#"+id+"error").css("display","none");
    }
}

function isempty(id)
{
    var val = $.trim($("#"+id).val());
    if(val.length==0)
    {
        $("#"+id+"error").css("display","block");
        console.log("empty！！！");
//                $(document).ready(function(){ $("#"+id).focus();})
    }
    else
    {
        $("#"+id+"error").css("display","none");
    }
}

