(function (window, Model) {
	window.request = Model.initialize();
	window.opts = {};
}(window, window.Model));

$(document).ready(function() {
	// facebook login
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/en_US/sdk.js', getFBScript);

	$("#fbLogin").on("click", function (e) {
		e.preventDefault();
		var self = $(this);
		self.addClass('disabled');
		if (!fbinit) {
			getFBScript();
		}
		isLoggedIn();
		self.removeClass('disabled');
	});

});

function getFBScript() {
	FB.init({
		appId: '1312032535489864',
		version: 'v2.4'
	});
	fbinit = true;
}

function isLoggedIn() {
	FB.getLoginStatus(function (response) {
		if (response.status === 'connected') {
			getFBInfo();	// User logged into fb and app
		} else {
			FB.login(function (response) {
				if (response.status === 'connected') {
					getFBInfo();
				} else {
					// $('#alertMessage').html("You need to give access to playmusic.net");
					// $('#alertModal').modal('show');
					alert('Provide access');
				}
			}, {scope: 'public_profile,email'});
		}
	});
}

function getFBInfo() {
	FB.api('/me?fields=name,email', function (response) {
		request.create({
			action: '/organizer/fbLogin',
			data: {action: 'fbLogin', email: response.email, name: response.name, token: ''},
			callback: function (data) {
				if (data == "Success") {
					window.location.href = "/organizer/";
				} else {
					// $('#alertMessage').html("Something went wrong");
					// $('#alertModal').modal('show');
					alert('something went wrong');
				}
			}
		});
	});
}