$(function(){
    var conn = new WebSocket('ws://' + location.hostname + ':4502');
    conn.onopen = function(e) {
        //appendMessage("", "Connection established!", "");
    };
    conn.onmessage = function(e) {
        var jsonObj = JSON.parse(e.data);
        appendMessage(jsonObj.name, jsonObj.message, jsonObj.time);
    };
    
    function appendMessage(name, message, time) {
        // HTMLエスケープして改行を<br>に変換
        var escapeMessage = Handlebars.Utils.escapeExpression(message);
        escapeMessage = escapeMessage.replace(/(\r\n|\n|\r)/gm, '<br>');
        
        // JSテンプレート処理
        var values = {"name":name,"message":escapeMessage,"time":time}
        var source = $("#message_box_template").html();
        var template = Handlebars.compile(source);
        var appendHtml = template(values);
        
        // メッセージを追加
        $("#messages").append(appendHtml);
        
        // 読み上げ
        var synthes = new SpeechSynthesisUtterance(message);
        synthes.lang = "ja-JP"
        synthes.volume = 1;
        speechSynthesis.speak(synthes);
    }
    
    $("#send_btn").on('click', function() {
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
        if (name && message) {
            var jsonStr = JSON.stringify({"name":name,"message":message});
            conn.send(jsonStr);
        }
        $("#message").val("");
        $("#message").focus();
        
        $('#messages').delay(100).animate({
            scrollTop: $('#messages').height()
        },1500);
    }
});