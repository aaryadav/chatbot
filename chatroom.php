<?php
session_start();

if (!isset($_SESSION['user_data'])) {
	header('location:index.php');
}

require('database/ChatUser.php');

require('database/ChatRooms.php');

$chat_object = new ChatRooms;

$chat_data = $chat_object->get_all_chat_data();

$user_object = new ChatUser;

$user_data = $user_object->get_user_all_data();

?>

<!DOCTYPE html>
<html>

<head>
	<title>ChatBot</title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
		integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<style type="text/css">
		html,
		body {
			height: 100%;
			width: 100%;
			margin: 0;
		}

		#wrapper {
			display: flex;
			flex-flow: column;
			height: 100%;
		}

		#remaining {
			flex-grow: 1;
		}

		#messages {
			height: 200px;
			background: whitesmoke;
			overflow: auto;
		}

		#chat-room-frm {
			margin-top: 10px;
		}

		#user_list {
			height: 450px;
			overflow-y: auto;
		}

		#messages_area {
			height: 650px;
			overflow-y: auto;
			background-color: #fff;
		}
		
		.bg-info {
			background-color: #3A76F0 !important;
		}

		.bg-alert {
			background-color: #000 !important;
		}

	</style>
</head>

<body>
	<div class="container">
		<br />
		<h3 class="text-center">ChatBot</h3>
		<br />
		<div class="row">

			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h3>Chat Room</h3>
					</div>
					<div class="card-body" id="messages_area">
						<?php
						foreach ($chat_data as $chat) {
							if (isset($_SESSION['user_data']['user_id'])) {
								// $from = 'Me';
								$row_class = 'row';
								$background_class = 'text-light bg-info';
							} // if userid is 1 then it is bot
							else if ($chat['user_id'] == 1) {
								$from = $chat['user_name'];
								$row_class = 'row';
								$background_class = 'text-light bg-alert';
							} else {
								$from = $chat['user_name'];
								$row_class = 'row justify-content-end';
								$background_class = 'text-light bg-info';
							}

							echo '
						<div class="' . $row_class . '">
							<div class="col-sm-12">
								<div class="shadow-sm alert ' . $background_class . '">
									<b>' . $from . ' - </b>' . $chat["msg"] . '
									<br />
									<div class="text-right">
										<small><i>' . $chat["created_on"] . '</i></small>
									</div>
								</div>
							</div>
						</div>
						';
						}
						?>
					</div>
				</div>

				<form method="post" id="chat_form" data-parsley-errors-container="#validation_error">
					<div class="input-group mb-3">
						<textarea class="form-control" id="chat_message" name="chat_message"
							placeholder="Type Message Here" data-parsley-maxlength="1000"
							data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" required></textarea>
						<div class="input-group-append">
							<button type="submit" name="send" id="send" class="btn btn-primary"><i
									class="fa fa-paper-plane"></i></button>
						</div>
					</div>
					<div id="validation_error"></div>
				</form>
			</div>
			<div class="col-lg-4">
				<?php

				$login_user_id = '';

				foreach ($_SESSION['user_data'] as $key => $value) {
					$login_user_id = $value['id'];
					?>
					<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" />
					<div class="mt-3 mb-3 text-center">
						<h3 class="mt-2">
							<?php echo $value['name']; ?>
						</h3>
						<input type="button" class="btn btn-primary mt-2 mb-2" name="logout" id="logout" value="Logout" />
					</div>
					<?php
				}
				?>

				<div class="card mt-3">
					<div class="card-header">User List</div>
					<div class="card-body" id="user_list">
						<div class="list-group list-group-flush">
							<?php
							if (count($user_data) > 0) {
								foreach ($user_data as $key => $user) {
									$icon = '<i class="fa fa-circle text-danger"></i>';

									if ($user['user_login_status'] == 'login') {
										$icon = '<i class="fa fa-circle text-success"></i>';
									}

									if ($user['user_id'] != $login_user_id) {
										echo '
									<a class="list-group-item list-group-item-action">
										<span class="ml-1"><strong>' . $user["user_name"] . '</strong></span>
										<span class="mt-2 float-right">' . $icon . '</span>
									</a>
									';
									}

								}
							}
							?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</body>
<script type="text/javascript">

	document.addEventListener("DOMContentLoaded", function () {

		// connect to websocket server
		let conn = new WebSocket('ws://127.0.0.1:8080/');

		conn.onopen = function (e) {
			console.log("Connection established!");
		};

		conn.onmessage = function (e) {
			console.log(e.data);

			var data = JSON.parse(e.data);

			var row_class = '';

			var background_class = '';

			if (data.from == 'Me') {
				row_class = 'row ';
				background_class = 'text-light bg-info';
			}
			else {
				row_class = 'row ';
				background_class = 'alert bg-info';
			}

			var html_data = "<div class='" + row_class + "'><div class='col-sm-12'><div class='shadow-sm alert " + background_class + "'><b>" + data.from + " - </b>" + data.msg + "<br /><div class='text-right'><small><i>" + data.dt + "</i></small></div></div></div></div>";

			document.getElementById('messages_area').innerHTML += html_data;

			document.getElementById("chat_message").value = "";
		};

		document.getElementById('messages_area').scrollTop = document.getElementById('messages_area').scrollHeight;

		document.getElementById('chat_form').addEventListener('submit', function (event) {

			event.preventDefault();

			var user_id = document.getElementById('login_user_id').value;

			var message = document.getElementById('chat_message').value;

			var data = {
				userId: user_id,
				msg: message
			};

			conn.send(JSON.stringify(data));

			document.getElementById('messages_area').scrollTop = document.getElementById('messages_area').scrollHeight;


		});

		document.getElementById('logout').addEventListener('click', function () {
			console.log('logout');

			user_id = document.getElementById('login_user_id').value;

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'action.php', true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onload = function () {
				if (this.status == 200) {
					var response = JSON.parse(this.responseText);

					if (response.status == 1) {
						conn.close();
						location = 'index.php';
					}
				}
			}
			xhr.send('user_id=' + user_id + '&action=leave');

		});

	});
</script>

</html>