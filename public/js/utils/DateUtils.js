var DateUtils = function(servertime) {
    var me = this;

    me.SERVER_TIME = null;
    me.SERVER_DIFF = 0;

    init = function(){
        let xserver = me.parse(servertime);
        let xnow = new Date();

        me.SERVER_DIFF = xnow - xserver;

        me.SERVER_TIME = new Date(xserver.getTime() + me.SERVER_DIFF);
    }

    me.now = function (){
        return me.SERVER_TIME;
    }

    me.parse = function(datetime){
        if(datetime){
            var now  = new Date();
            var date = now.format('Y-m-d');
            var time = '00:00:00';

            let dt = datetime.split(' ');
                dt = (dt.length > 1) ? dt : datetime.split('T');
            if(dt.length > 1){
                date = dt[0];
                time = dt[1] || '00:00:00';
            }
            else {
                if(dt[0].split('-').length > 1) date = dt[0];
                else if(dt[0].split(':').length > 1) time = dt[0];
                else if(dt[0].split('.').length > 1) time = dt[0].split('.').join(':');
            }

            let xdate = date.split('-');
            date = xdate[0];
            date += '-' + ((xdate[1].length < 2) ? ('0'+xdate[1]) : xdate[1]);
            date += '-' + ((xdate[2].length < 2) ? ('0'+xdate[2]) : xdate[2]);

            return new Date(date + 'T' + time.substr(0,8) +'.000+00:00');
        }
        return new Date();
    };

    me.parseTime = function(time){
        var now = new Date();
        if(me.servernow != undefined && me.servernow) now = me.parse(me.servernow);

        var y = now.getFullYear();
        var m = now.getMonth();
        var d = now.getDate();

        var h = time.substr(0, 2);  h = h ? h : '00';
        var i = time.substr(3, 2);  i = i ? i : '00';
        var s = time.substr(6, 2);  s = s ? s : '00';

        if(m < 10) m = '0'+m;
        if(d < 10) d = '0'+d;

        return me.parse(y+'-'+m+'-'+d+' '+h+':'+i+':'+s);
    };

    me.diff = function(date1, date2){
        if(typeof date1 == 'string') date1 = (date1.length >= 10) ? me.parse(date1) : me.parseTime(date1);
        if(typeof date2 == 'string') date2 = (date2.length >= 10) ? me.parse(date2) : me.parseTime(date2);
        var distance = date2 - date1;
        var diff = distance;

        let result = {
            distance: distance,
            total: {
                year: Math.floor(diff / (1000 * 60 * 60 * 24 * 365)),
                month: Math.floor(diff / (1000 * 60 * 60 * 24 * 30)),
                day: Math.floor(diff / (1000 * 60 * 60 * 24)),
                hour: Math.floor(diff / (1000 * 60 * 60)),
                minute: Math.floor(diff / (1000 * 60)),
                second: Math.floor(diff / (1000)),
            }
        };

        result.time = {
            year: Math.floor(result.total.year),
            month: Math.floor(result.total.month % 12),
            day: Math.floor(result.total.day % 30),
            hour: Math.floor(result.total.hour % 24),
            minute: Math.floor(result.total.minute % 60),
            second: Math.floor(result.total.second % 60),
        };

        let text = 'Just now';
        let shortText = 'Just now';
        let t = result.time;
        if(t.year) {
            text = t.year + ' year'; if(t.year == 1 && t.month) text += ', ' + t.month + ' month';
            shortText = t.year + 'Y'; if(t.year == 1 && t.month) shortText += ', ' + t.month + 'M';
        }
        else if(t.month) {
            text = t.month + ' month'; if(t.month == 1 && t.day) text += ', ' + t.day + ' day';
            shortText = t.month + 'M'; if(t.month == 1 && t.day) shortText += ', ' + t.day + 'd';
        }
        else if(t.day) {
            text = t.day + ' day'; if(t.day == 1 && t.hour) text += ', ' + t.hour + ' hour';
            shortText = t.day + 'd'; if(t.day == 1 && t.hour) shortText += ', ' + t.hour + ' h';
        }
        else if(t.hour) {
            text = t.hour + ' hour'; if(t.hour == 1 && t.minute) text += ', ' + t.minute + ' minute';
            shortText = t.hour + 'h'; if(t.hour == 1 && t.minute) shortText += ', ' + t.minute + 'm';
        }
        else if(t.minute) {
            text = t.minute + ' minute'; if(t.minute == 1 && t.second) text += ', ' + t.second + ' second';
            shortText = t.minute + 'm'; if(t.minute == 1 && t.second) shortText += ', ' + t.second + 's';
        }
        else if(t.second) {
            text = t.second + ' second';
            shortText = t.second + 's';
        }

        result.text = text;
        result.shortText = shortText;

        return result;
    };

    me.diffServer = function(time){
        return me.diff(time, me.now());
    };

    me.format = function(date, dateformat){
        if (dateformat == undefined) dateformat = 'd/m/Y';
        if(typeof date == "object");
        else {
            date = date.replace("T", " ");
            date = date.substr(0, 19);
            if (typeof date == 'string') date = me.parse(date);
        }
        console.log(dateformat);
        return date.format(dateformat);
    }

    me.separator = function (text){
        if(text){
            if(text.find('/')) return '/';
            if(text.find('-')) return '-';
            if(text.find(' ')) return ' ';
        }
        return '';
    }

    me.shortDay = function(date){
        let d = me.diffServer(date+' 23:59:59');
        if(d.day > 360 || d.day < -360) return Math.ceil(d.day/360) + 'y';
        else if(d.day > 30 || d.day < -30) return Math.ceil(d.day/30) + 'm';
        else if(d.day == 0) return 'Today';
        return d.day+'d';
    }

    init();
}

Date.prototype.getMonthName = function(){
    var month_names = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December'
                    ];

    return month_names[this.getMonth()];
}
Date.prototype.getMonthAbbr = function(){
    var month_abbrs = [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ];

    return month_abbrs[this.getMonth()];
}
Date.prototype.getDayFull = function(){
    var days_full = [
                        'Sunday',
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday'
                    ];
    return days_full[this.getDay()];
};
Date.prototype.getDayAbbr = function(){
    var days_abbr = [
                        'Sun',
                        'Mon',
                        'Tue',
                        'Wed',
                        'Thur',
                        'Fri',
                        'Sat'
                    ];
    return days_abbr[this.getDay()];
};
Date.prototype.getDayOfYear = function() {
    var onejan = new Date(this.getFullYear(),0,1);
    return Math.ceil((this - onejan) / 86400000);
};
Date.prototype.getDaySuffix = function() {
    var d = this.getDate();
    var sfx = ["th","st","nd","rd"];
    var val = d%100;

    return (sfx[(val-20)%10] || sfx[val] || sfx[0]);
};
Date.prototype.getWeekOfYear = function() {
    var onejan = new Date(this.getFullYear(),0,1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
}
Date.prototype.isLeapYear = function(){
    var yr = this.getFullYear();

    if ((parseInt(yr)%4) == 0){
        if (parseInt(yr)%100 == 0){
            if (parseInt(yr)%400 != 0){
                return false;
            }
            if (parseInt(yr)%400 == 0){
                return true;
            }
        }
        if (parseInt(yr)%100 != 0){
            return true;
        }
    }
    if ((parseInt(yr)%4) != 0){
        return false;
    }
};
Date.prototype.getMonthDayCount = function() {
    var month_day_counts = [
                                31,
                                this.isLeapYear() ? 29 : 28,
                                31,
                                30,
                                31,
                                30,
                                31,
                                31,
                                30,
                                31,
                                30,
                                31
                            ];

    return month_day_counts[this.getMonth()];
}
Date.prototype.format = function(dateFormat){
    dateFormat = dateFormat.split("");
    var date = this.getDate(),
        month = this.getMonth(),
        hours = this.getHours(),
        minutes = this.getMinutes(),
        seconds = this.getSeconds();

    var date_props = {
        d: date < 10 ? '0'+date : date,
        D: this.getDayAbbr(),
        j: this.getDate(),
        l: this.getDayFull(),
        S: this.getDaySuffix(),
        w: this.getDay(),
        z: this.getDayOfYear(),
        W: this.getWeekOfYear(),
        F: this.getMonthName(),
        m: month < 10 ? '0'+(month+1) : month+1,
        M: this.getMonthAbbr(),
        n: month+1,
        t: this.getMonthDayCount(),
        L: this.isLeapYear() ? '1' : '0',
        Y: this.getFullYear(),
        y: this.getFullYear()+''.substring(2,4),
        a: hours > 12 ? 'pm' : 'am',
        A: hours > 12 ? 'PM' : 'AM',
        g: hours % 12 > 0 ? hours % 12 : 12,
        G: hours > 0 ? hours : "12",
        h: hours % 12 > 0 ? hours % 12 : 12,
        H: hours < 10 ? '0'+hours : hours,
        i: minutes < 10 ? '0' + minutes : minutes,
        s: seconds < 10 ? '0' + seconds : seconds
    };

    var date_string = "";
    for(var i=0;i<dateFormat.length;i++){
        var f = dateFormat[i];
        if(f.match(/[a-zA-Z]/g)){
            date_string += date_props[f] ? date_props[f] : '';
        } else {
            date_string += f;
        }
    }

    return date_string;
};

String.prototype.toDate = function(format){
    let strdate = this;
    if(strdate) {
        format = format || 'Y-m-d';

        let arrformat = format.replace(/[^a-zA-Z0-9]/g, ' ').split(' ');
        let arrdate = strdate.replace(/\D/g, ' ').split(' ');

        let list = {}
        arrformat.forEach(function (e, i){
            list[e] = arrdate[i];
        });

        if(!list.H) list.H = '00';
        if(!list.i) list.i = '00';
        if(!list.s) list.s = '00';

        return new Date(String.format('{Y}-{m}-{d}T{H}:{i}:{s}', list));
    }
    return null;
};

String.prototype.toDateTime = function(format){
    let strdate = this;
    format = format || 'Y-m-d H:i:s';
    return strdate.toDate(format);
};
