import Vue from 'vue';

Vue.filter('price_format', (val, suffix = false) => {
    var number = new Number(val);
    var p = number.toFixed(2).split(".");
    var price ='';
    if(suffix){
        price += "TK ";
    }
    price += p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
    }, "") + "." + p[1];

    return price;
})

Vue.filter('convertDate', function (date) {
    if(typeof date === "undefined" || date === null ){
        return date;
    }
    var current_date = new Date(date.replace(/-/g, "/"));
    //var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var daysOfMonth = ["NaN", "1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th",
        "11th", "12th", "13th", "14th", "15th", "16th", "17th", "18th", "19th", "20th",
        "21st", "22nd", "23rd", "24th", "25th", "26th", "27th", "28th", "29th", "30th", "31st"];


    // var returnDate = daysOfWeek[current_date.getDay()] + ", "
    //     + monthsOfYear[current_date.getMonth()] + " "
    //     + daysOfMonth[current_date.getDate()]
    //     + ", " + current_date.getFullYear();

    var returnDate = daysOfMonth[current_date.getDate()] + " " + monthsOfYear[current_date.getMonth()] + " " + current_date.getFullYear();

    return returnDate;
});
