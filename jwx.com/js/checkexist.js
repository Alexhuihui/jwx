function checkexist2(id,url)
{
    var updatedata;
    var currentdata = $.trim($("#"+id).val());
    $("#"+id).bind("input propertychange",function ()
    {
        updatedata =  $.trim($("#"+id).val());
        console.log("currentdata"+currentdata);
        console.log("updatedata"+updatedata);
        var content={"current_value":currentdata,"update_value":updatedata};
//       ""里填写希望请求的 URL
        $.post(url,content,function (data)
        {
            var json = JSON.parse(data);
            var flag = json.exist;
            console.log("data  "+flag);

            if(flag==0)
            {
                $("#inexistence").css("display","block");
            }
            else
            {
                $("#inexistence").css("display","none");
            }
        });

    });

}