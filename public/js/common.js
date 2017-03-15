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
    
    $("#text").keypress(function (e) {
        if (e.which == 13) {
            sendMsg();
        }
    });
    
    function sendMsg() {
        var name = $("#name").val();
        var text = $("#text").val();
        var msg = name + ' : ' + text;
        if (msg) {
            conn.send(msg);
            appendMessage(msg);
            $("#text").val("");
        }
        $("#text").focus();
        
        $('body').delay(100).animate({
            scrollTop: $(document).height()
        },1500);
    }
});