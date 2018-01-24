// 折线图
console.log(document.getElementById("content").style.offsetHeight);


var width = 650,
    height = 500,
    margin = {left: 40, top: 30, right: 20, bottom: 20},
    g_width = width - margin.left - margin.right,
    g_height = height - margin.top - margin.bottom;

// 制作图中的线条
// 定义画布
var svg = d3.select('#content')
    .classed("svg-container", true)
    .append("svg")
    .attr("preserveAspectRatio", "xMidYMid meet")
    .attr("viewBox", "0 0 650 600")
    .classed("svg-content-responsive", true);
    // width,height
    // .attr('width', width)//attribute
    // .attr('height', height);


// var svg = d3.select('#s');

var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");//把画布偏移

var data = [1, 3, 5, 7, 8, 4, 3, 7,22];
var xMarks=['Mon','Tues','Wed','Thur','Fri','06','07','08','09'];

// 放大
var scale_x = d3.scale.linear()
    .domain([0, data.length - 1])
    .range([0, g_width]);

var scale_y = d3.scale.linear()
    .domain([0, d3.max(data)])
    .range([g_height, 0]);// 切换坐标轴的朝向，0对应g_height


var line_generator = d3.svg.line()
    .x(function (d, i) {
        // d代表数据
        // i代表下标
        return scale_x(i);
    })
    .y(function (d) {
        return scale_y(d);
    })

d3.select("g")
    .append("path")
    .attr("d",line_generator(data));

// 制作坐标轴
//比例尺
var x_axis = d3.svg.axis()
    .scale(scale_x)
    .orient("bottom");
var y_axis = d3.svg.axis()
    .scale(scale_y)
    .orient("left");

//添加坐标轴
g.append("g")
    .call(x_axis)
    .attr("transform", "translate(0," + g_height + ")")
    .selectAll("text")
    .text(function(d){return xMarks[d];})
    .append("text")

g.append("g")
    .call(y_axis)
    .append("text")
    .text("y") // 添加文本标签
    .attr("transform","rotate(-90)")//旋转标签
    .attr("text-anchor", "end")//对齐方式 end末尾
    .attr("dy", "1em");//dy----y轴平移的距离

//日历