$(function(){
    var conn = new WebSocket('ws://' + location.hostname + ':4502');
    conn.onopen = function(e) {
        appendMessage("Connection established!");
    };
    conn.onmessage = function(e) {
        appendMessage(e.data);
    };
    
    function appendMessage(msg) {
        $("#messages").append(msg + "<br>\n");
        
        var synthes = new SpeechSynthesisUtterance(msg);
        synthes.lang = "ja-JP"
        synthes.volume = 1;
        speechSynthesis.speak(synthes);
    }
    
    $("#send_btn").on('click', function(){
        sendMsg();
    });
    
    $("#message").keypress(function (e) {
        if (e.which == 13) {
            if(event.ctrlKey || event.shiftKey){
                sendMsg();
                return false;
            }
        }
    });
    
    function sendMsg() {
        var name = $("#name").val();
        var message = $("#message").val();
        var msg = name + ' : ' + message;
        if (msg) {
            conn.send(msg);
            appendMessage(msg);
        }
        $("#message").val("");
        $("#message").focus();
        
        $('#messages').delay(100).animate({
            scrollTop: $('#messages').height()
        },1500);
    }
});