var clone = function (data){
    if(data) {
        data = JSON.stringify(data);
        data = JSON.parse(data);
        return data;
    }
    return null;
}

var grep = function (data, search, boolean){
    if(data && (search || search == 0)){
        boolean = boolean || true;
        return $.grep(data, function(e, index) {
            if(typeof search == 'object'){
                for (var key in search) {
                    if(!boolean && (e[key] == search[key])) return true;
                    else if(boolean && (e[key] != search[key])) return false;
                    else if(boolean) return true;
                }
                return false;
            }
            else if(typeof e == 'object'){
                if(e.id != undefined) return (e.id == search);
                return false;
            }
            else return (e == search);
        });
    }
    return null;
};

var find = function (data, search, boolean){
    if(data && (search || search == 0)){
        if(boolean == undefined) logic = true;
        let result = grep(data, search, boolean);
        if(result.length) return result[0];
    }
    return null;
};

var exist = function (data, search, boolean){
    if(data && (search || search == 0)){
        if(boolean == undefined) logic = true;
        let result = grep(data, search, boolean);
        if(result.length) return true;
    }
    return false;
};

Array.prototype.exist = function(search, boolean) {
    return exist(this, search, boolean);
}

Array.prototype.find = function(search, boolean) {
    return find(this, search, boolean);
}

Array.prototype.grep = function(search, boolean) {
    return grep(this, search, boolean);
}
