var jq=jQuery.noConflict();
var index=-1;
window.onload = function ()
{
    addmore();
};

var addmore = function(){
    var article_num={"id":index};

    if(index==-1)
    {
        jq.ajax({
            url: "../../index.php/foreground/article/load_more",
            type: "POST",
            data: article_num,
            success: function (data,status)
            {
                console.log("data "+data);
                // alert(status);
                if(data == "")
                {
//                        alert("这里还没有文章");
                    jq("#show").html("<span>这里还没有文章</span>");
                    jq("#show").css("margin-top","30px");
                    jq("#show").css("text-align","center");
                    jq("#click_more").hide();
                }
                else
                {
                    var html1="";
                    for (var i = 0; i < data.length; i++)
                    {

                        console.log(data[i].articleimg);
                        var html1 = html1 +
                            "<a href='../../index.php/foreground/article/check/"+data[i].articleid+"'>" +
                            "<div class=\"row clearfix article\">" +
                            "<div class=\"thumbnail article_img col-xs-4 col-md-2 \">" +
                            "<img  alt='缩略图' id='imgcon' src='" + data[i].articleimg + "'>" +
                            "</div>" +
                            "<div class=\"article_title col-xs-8 \">" +
                            "<h4>" + data[i].articlename + "</h4>" +
                            "<h5>" + data[i].updatetime + "</h5>" +
                            "</div>" +
                            "</div>"+
                            "</a>";
                    }
                    if (data.length<10)
                    {
                        jq("#click_more").hide();
                    }
                    else
                    {
                        index = data[data.length - 1].articleid;
                        jq("#click_more").show();
                    }
                    console.log("b"+index);
                    jq("#show").append(html1);
                }
            },
            error:function()
            {
                jq("#show").html("<span style='font-size: 20px;min-height: 60px;'>出现异常</span>");
                jq("#show").css("margin-top","30px");
                jq("#show").css("text-align","center");
                jq("#click_more").hide();
            },
            dataType: "json"
        });

        console.log("a"+index);
    }
    else
    {
        console.log("2333");
        console.log(article_num);
        var html2="";

        jq.ajax({
            url: "../../index.php/foreground/article/load_more",
            type: "POST",
            data: article_num,
            success: function (data)
            {
                for(var i = 0 ; i < data.length ; i++)
                {
                    html2=html2+
                        "<a href='../../index.php/foreground/article/check/"+data[i].articleid+"'>" +
                        "<div class=\"row clearfix article\">"+
                        "<div class=\"thumbnail article_img col-xs-4 col-md-2 \">"+
                        "<img  alt='缩略图' id='imgcon' src='" + data[i].articleimg + "'>" +
                        "</div>"+
                        "<div class=\"article_title col-xs-8 \">"+
                        "<h4>"+data[i].articlename+"</h4>"+
                        "<h5>"+data[i].updatetime+"</h5>"+
                        "</div>"+
                        "</div>"+
                        "</a>";
                }
                jq("#show").append(html2);
                if(data.length<5)
                {
                    jq("#click_more").hide();
                }
                else
                {
                    index = data[data.length - 1].articleid;
                }
            },
            error:function()
            {
                jq("#show").html("<span style='font-size: 16px;min-height: 60px;'>出现异常</span>");
                jq("#show").css("margin-top","30px");
                jq("#show").css("text-align","center");
                jq("#click_more").hide();
            },
            dataType: "json"
        });
    }
}