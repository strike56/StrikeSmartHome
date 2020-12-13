let home = {

    modules: [],
    blocks: [],
    init: function() {
        $(this.blocks).each(function() {
            if (typeof this.init != 'undefined') {
                this.init();
            }
        });
    },
    push: function(name, module) {
        this.modules[name] = module;
    },
    add: function(id, uid, module) {
        let moduleObj = this.modules[module];
        let blockObj = Object.assign({'id': uid}, moduleObj);
        this.blocks.push(blockObj);
        if (typeof blockObj.add != 'undefined') {
            $(id).append(blockObj.add());
        }
        return "";
    },
    refresh: function() {
        let data = [];
        $(this.blocks).each(function() {
            data.push({
                'action': this.action,
                'id': this.id
            });
        });
        connector.send('Refresh', data);
    },
    update: function(id, data) {
        $(this.blocks).each(function() {
            if (typeof(this.id) != 'undefined' &&
                this.id === id &&
                typeof(this.update) != 'undefined'
            ) {
                this.update(data);
            }
        });
    }
}

$(document).ready(function() {
    home.init();
});

actions.onopen = function() {
    home.refresh();
}

actions.REFRESH = function(data) {
    for(let id in data.data) {
        let vals = data.data[id];
        home.update(id, vals);
    }
}
