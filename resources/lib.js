var tpls = {};
// find all the templates and compile them.  TODO: Look into pre-compiled templates
$('document').ready(function() {
    var tplElems = $('[type="text/x-handlebars-template"]');
    for (var i = 0; i < tplElems.length; i++) {
        var tplElem = $(tplElems[i]);
        tpls[tplElem.attr('id')] = Handlebars.compile(tplElem.html());
    }
});

// returns the markup for the template id
$HB = function(tplId, model, selector) {
    if (!model) model = {};
    var html = tpls[tplId](model);
    if (selector) {
        $(selector).html(html);
    }
    return  html;
};

// returns the markup for the template id as a Handlebars SafeString
$HBS = function(tplId, model) {
    return new Handlebars.SafeString($HB(tplId, model));
};

// left pads numbers with 0s
pad = function(numOrStr, minLength) {
    numOrStr = numOrStr + '';
    var strParts = [];
    while ((numOrStr.length + strParts.length) < minLength) {
        strParts.push('0');
    }
    strParts.push(numOrStr);
    return strParts.join('');
};