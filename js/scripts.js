'use strict';

var Chat = {
	click: function(chatter_name, chatter_id) {
		alert("Name: " + chatter_name + " (" + chatter_id + ") clicked");
	},
	command: function(command, chatter_id, user_status) {
	
	},
	init: function() {
		$("#chat_messages").load("chat.php");
		setTimeout(Chat.init, 3000);
	}
}

$(document).ready(function() {
	'use strict';
	
	$('[data-toggle="offcanvasleft"]').on("click", function() {
		$('.row-offcanvas-right').removeClass('active');
		$('.row-offcanvas-left').toggleClass('active');
	});

	$('[data-toggle="offcanvasright"]').on("click", function() {
		$('.row-offcanvas-left').removeClass('active');
		$('.row-offcanvas-right').toggleClass('active');
	});
	
	$('#chat_messages').load('chat.php');
	
	$("#chat_write").on("submit", function(e) {
	
		e.preventDefault();
	
		$.ajax({
			url: "chat.php?action=send_message",
			type: "POST",
			data: $("#chat_write").serialize(),
			success: function() {
				$("#chat_messages").load("chat.php");
			}
		}).done(function() {
			$("#chat_write")[0].reset();
		}).fail(function() {
			$("#chat_messages").html("An error occured while attempting to send a message.");
		});
		
		e.preventDefault();
		
	});

	Chat.init();
});
     