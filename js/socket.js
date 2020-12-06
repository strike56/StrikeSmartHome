
		socket = {

			url: false,
			ws: false,
			status: false,

			init: function( url ) {
				socket.url = url + ":9201";
				socket.connect();
			},

			connect: function() {
				if (!socket.ws) {
					socket.ws = new ReconnectingWebSocket( 'wss://' + socket.url + '/' );
					socket.ws.onopen = socket.open;
					socket.ws.onclose = socket.close;
					socket.ws.onmessage = socket.receive;
				}
			},
			
			send: function( message ) {
				socket.ws.send( message );
			},
			
			receive: function( event ) {
				connector.receive( event );
			},
			
			open: function() {
				socket.status = true;
				connector.open();
			},
			
			close: function() {
				socket.status = false;
				connector.close();
			},
		}


		connector = {

			url: false,

			init: function() {
				setInterval(connector.pong, 30000);
			},

			send: function(action, data ) {
				if (!socket.ws || socket.ws.readyState !== 1) return false;
				if (typeof data == 'undefined') data = {};
				let out = {
					action: action,
					data: data,
				}
				out = JSON.stringify(out);
				socket.send(out);
				return false;

			},

			receive: function( event ) {
				if (typeof event.data == 'undefined') return false;
				let data = JSON.parse(event.data);
				if (typeof data.action == 'undefined') return false;
				if (typeof actions[data.action] != 'function') return false;
				actions[data.action]( data );
			},
			
			open: function() {
                connector.status = true;
				$('header .status').addClass('online');
				connector.send( 'PING' );
				if (typeof actions["onopen"] == 'function')
					actions["onopen"]();
			},
			
			close: function() {
                connector.status = false;
				$('header .status').removeClass('online');
                if (typeof actions["onclose"] == 'function')
                    actions["onclose"]();
			},
				
			pong: function() {
				connector.send('PING');
			},
			
			connect: function( url ) {
				connector.url = url;
				socket.init( url );
            },
		}

		actions = {
/*
			RESPONSE: function ( data ) {
				if (typeof data.message == 'undefined') return false;
				if (typeof data.status == 'undefined') return false;
				if (typeof connector.messages[data.message] == 'undefined') return false;
				if (data.status == 'OK') {
					delete connector.messages[data.message];
					localStorage["messages"] = JSON.stringify(connector.messages); 
				} else {
					connector.messages[data.message].status = 'error';
				}
				return true;
			},
			
			SESSION: function ( data ) {
				if (typeof data.session == 'undefined') return false;
				connector.session = data.session;

				$('.session').html( data.session );
                if (typeof app != 'undefined') {
                    localStorage.setItem("session", data.session);
                } else {
                    sessionStorage.setItem("session", data.session);
				}


				return true;
			},
			
			SHOW: function ( data ) {
				if (typeof data.page == 'undefined') return false;
				if (typeof data.subblock != 'undefined') {
					$(data.page + ' .subblock').hide();
					$(data.page + ' ' + data.subblock).show();
				}
				judge.page( data.page );
				return true;
			},
			
			BLOCK: function ( data ) {
				if (typeof data.block == 'undefined') return false;
				if (typeof data.content == 'undefined') return false;
				
				$(data.block).html(data.content);
				if (typeof data.subblock != 'undefined') {
					$(data.block + ' .subblock').hide();
					$(data.block + ' ' + data.subblock).show();
				}
				return true;
			},
			
			PING: function ( data ) {
				connector.ping();
			}
*/
		}
		
 