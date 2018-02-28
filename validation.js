function digitsOnly(el, error) { // validation numeric only

    el.keydown(function (ev) {

        var n = ev.which || ev.keyCode;

        if (ev.ctrlKey || ev.altKey ||
            $.inArray(n, [8, 9, 13, 46, 27, 110, 116, 190]) !== -1 || //backspace, tab, delete, esc, decimal, F5, enter, 
            (n >= 35 && n <= 40) //home, end, left, right, down, up
        ) {
            
            $(error).css("display", "none");

        }else if((ev.shiftKey || (n < 48 || n > 57)) && (n < 96 || n > 105)){

            ev.preventDefault();
            $(error).css("display", "block");

        }else{

            $(error).css("display", "none");
        }
        
    });

}

function charactersOnly(el, error) { // validation numeric only

    el.keydown(function (ev) {

        var n = ev.which || ev.keyCode;

        if (ev.ctrlKey || ev.altKey ||
            $.inArray(n, [8, 9, 13, 32, 44, 45, 46, 27, 110, 116, 190]) !== -1 || //backspace, tab, delete, space, hyphen, comma, esc, decimal, F5, enter, 
            (ev.which && n == 39) ||  // single quote
            (!ev.which && n >= 35 && n <= 40) ||    // arrow keys/home/end
            (n >= 65 && n <= 90) ||                 // capital alphabets 
            (n >= 97 && n <= 122)                   // small alphabets
            
        ) {
            
            $(error).css("display", "none");

        }else if((n > 48 || n < 57) && (n > 96 || n < 105)){

            ev.preventDefault();
            $(error).css("display", "block");

        }else{

            $(error).css("display", "none");
        }
        
    });

}

function alphanumericOnly(el, error) { // validation numeric only

    el.keydown(function (ev) {

        var n = ev.which || ev.keyCode;

        if (ev.ctrlKey || ev.altKey ||
        // Allow: backspace, delete, tab, escape, enter and .
        $.inArray(n, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: home, end, left, right, down, up
        (n >= 35 && n <= 40)) {
            return true;
        }

        if ((ev.shiftKey || (n < 48 || n > 57)) && (n < 96 || n > 105)) {
            ev.preventDefault();
            $(error).css("display", "block");
        }else{            
            $(error).css("display", "none");
        }
    });

}





$(function(){
    charactersOnly($("#phoneNumber"), (".phoneErorr"));
    alphanumericOnly($("#passwordTxt"), (".passwordErorr"));
});