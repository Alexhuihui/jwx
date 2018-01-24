var date = new Date();
var d = date.toLocaleDateString();
var ti;
//图片预览
function getFullPath(obj)
{
    var newPreview = document.getElementById("img");
    if (obj)
    {
        //ie
        if (window.navigator.userAgent.indexOf("MSIE") >= 1)
        {
            obj.select();
            newPreview.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);";
            newPreview.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.selection.createRange().text;

            return;
        }
        else
        {
            if (obj.files)
            {
                newPreview.src = window.URL.createObjectURL(obj.files.item(0));

                return;
            }
            newPreview.src = obj.value;

            return;
        }
        newPreview.src = obj.value;

        return;
    }
}

//图片上传提示信息
function checkimg()
{
    var w = $("#img")[0].naturalWidth;
    var h = $("#img")[0].naturalHeight;
    console.log("原始大小:" + w + "X" + h);

    if(w>1024||h>768)
    {
        $("#imgerror").css("display","block");
//                $(document).ready(function(){ $("#"+id).focus();})
    }
    else
    {
        $("#imgerror").css("display","none");
    }
}

//隐藏提示信息
function errhidden()
{
    var errspan = $("#upload").next().text();

    var w = $("#img")[0].naturalWidth;
    console.log("w" + $("#img")[0].naturalWidth);
    if(w!=0&&errspan.length>0)
    {
        $("#upload").next().css("display","none");
    }
}

