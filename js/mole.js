var width = $(window).width() - 20,
    height = $(window).height() - 20,
    svg,
    force
    ;
$(function(){
    svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    force = d3.layout.force()
        .size([width, height])
        .charge(-1200)
        .linkDistance(function(d) { return d.source.size + d.target.size + 50; });

});

d3.json("api/user/getUserList", function(graph) {
    if(graph == null || graph == undefined)
        return false;

    if(graph.hasError)
    {
        console.log(graph.message);

        return false;
    }


    force
        .nodes(graph.nodes)
        .links(graph.links)
        .on("tick", tick)
        .start();

    var link = svg.selectAll(".link")
        .data(graph.links)
        .enter().append("g")
        .attr("class", "link");

    link.append("line")
        .style("fill","#696969")
        .style("stroke","#696969")
        .style("stroke-width", function(d) { return (d.bond * 2 - 1) * 2 + "px"; })
    ;

    var node = svg.selectAll(".node")
        .data(graph.nodes)
        .enter().append("g")
        .attr("class", "node")
        .call(force.drag);

    node.append("circle")
        .attr("r", function(d) { return d.size; })
        .style("fill", "lightblue")
        .style("stroke", "grey")
        .on("click", function(d)
        {
            //console.log(d);
            svg.selectAll("circle").style("fill", "lightblue");
            d3.select(this).style("fill", "#4dabe4");

            showPanel(d);
        })
    ;

    node.append("text")
        .attr("dy", ".35em")
        .attr("text-anchor", "middle")
        .text(function(d) { return d.firstName + " " + d.surname; });

    function tick() {
        link.selectAll("line")
            .attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; })
        ;

        node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    }

    $("#friendButtons")
        .on({
            click : function()
            {
                var field = "id";
                var value = $("#panelWrapper .panel .id").text();
                var url = $(this).attr("href") + "/" + field + "/" + value;

                $.get(url, function(response)
                {
                    //console.log(response);
                    renderFriends(response);
                },"json");

                return false;
            }
        },"a")
    ;

    $(".toggler")
        .on({
            click : function()
            {
                var dashboard = $(this).parent();

                if(dashboard.css("left") == "0px")
                {
                    dashboard.animate({
                        left: "-" + (dashboard.width() + 22) + "px"
                    });
                    $(this).text("open");
                }
                else
                {
                    dashboard.animate({
                        left:0
                    });

                    $(this).text("close");
                }

                return false;
            }
        })
    ;
});

function showPanel(d)
{
    var panel = $("#panelWrapper");

    panel.slideUp("slow", function()
    {
        if($("#friendsWrapper").is(":visible"))
            $("#friendsWrapper").slideUp();

        panel.find(".name").text(d.firstName + " " + d.surname);
        panel.find(".gender").text(d.gender);
        panel.find(".age").text(d.age);
        panel.find(".id").text(d.id);

        panel.slideDown();
    });
}
function renderFriends(response)
{
    var html = "";

    $("#friendsWrapper").slideUp("slow", function()
    {
        if(response && response.children)
        {
            var position = $("#separator").position();
            var friendsWrapperHeight = height - position.top - $("#separator").pixels("margin-top") - $("#separator").pixels("margin-bottom") - $("#dashboard").pixels("padding-top") - $("#dashboard").pixels("padding-bottom") - 4;

            $("#friendsWrapper")
                .css("max-height", friendsWrapperHeight + "px")
            ;

            $("#friendsWrapper").find(".count").text(response.count);
            $.each(response.children, function(key, value)
            {
                if(value.children)
                {
                    console.log(value.children.length);
                    $.each(value.children, function(keyC, valueC)
                    {
                        var panel = $("#panelSource");

                        panel.find(".name").text(valueC.firstName + " " + valueC.surname);
                        panel.find(".gender").text(valueC.gender);
                        panel.find(".age").text(valueC.age);

                        html += panel.html();
                    });
                }
                else
                {
                    var panel = $("#panelSource");

                    panel.find(".name").text(value.firstName + " " + value.surname);
                    panel.find(".gender").text(value.gender);
                    panel.find(".age").text(value.age);

                    html += panel.html();
                }
            });
        }
        $("#friendHolder").html(html);
        $("#friendsWrapper").slideDown();
    });
}