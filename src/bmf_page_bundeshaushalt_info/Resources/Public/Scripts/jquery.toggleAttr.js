$.fn.toggleAttr = function(attr, attr1, attr2) {
    return this.each(function() {
        var self = $(this);
        if (self.attr(attr) == attr1)
            self.attr(attr, attr2);
        else
            self.attr(attr, attr1);
    });
};