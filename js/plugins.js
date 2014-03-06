$.fn.pixels = function(property)
{
    return parseInt(this.css(property).replace("px", ""));
};
