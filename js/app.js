$(document).ready(function () {
	let currentUser = null;
	const API_BASE_URL = appConfig.apiPath;

	// Helper function to show alerts
	function showAlert(message, type = "success") {
		const alert = $(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);
		$(".container").prepend(alert);
		setTimeout(() => alert.alert("close"), 5000);
	}

	// Helper function to handle API errors
	function handleError(error) {
		const message = error.responseJSON?.error || "An error occurred";
		showAlert(message, "danger");
	}

	// Functions to manage user session
	function saveUserSession(user) {
		localStorage.setItem("currentUser", JSON.stringify(user));
		currentUser = user;
		updateUIState();
	}

	function clearUserSession() {
		localStorage.removeItem("currentUser");
		currentUser = null;
		updateUIState();
	}

	function loadUserSession() {
		const savedUser = localStorage.getItem("currentUser");
		if (savedUser) {
			try {
				currentUser = JSON.parse(savedUser);
				updateUIState();
				return true;
			} catch (e) {
				clearUserSession();
			}
		}
		return false;
	}

	// Update UI based on authentication state
	function updateUIState() {
		if (currentUser) {
			$("#authForms").hide();
			showProfile();
		} else {
			$("#authForms").show();
			$("#profileSection").hide();
		}
	}

	// Registration form submission
	$("#registerForm").on("submit", function (e) {
		e.preventDefault();

		const data = {
			name: $("#registerName").val(),
			email: $("#registerEmail").val(),
			dob: $("#registerDob").val(),
			password: $("#registerPassword").val(),
		};

		$.ajax({
			url: `${API_BASE_URL}/register`,
			method: "POST",
			contentType: "application/json",
			data: JSON.stringify(data),
			success: function (response) {
				showAlert("Registration successful! Please login.");
				$("#registerForm")[0].reset();
			},
			error: handleError,
		});
	});

	// Login form submission
	$("#loginForm").on("submit", function (e) {
		e.preventDefault();

		const data = {
			email: $("#loginEmail").val(),
			password: $("#loginPassword").val(),
		};

		$.ajax({
			url: `${API_BASE_URL}/login`,
			method: "POST",
			contentType: "application/json",
			data: JSON.stringify(data),
			success: function (response) {
				saveUserSession(response.user);
				showAlert("Login successful!");
				$("#loginForm")[0].reset();
			},
			error: handleError,
		});
	});

	// Profile form submission
	$("#profileForm").on("submit", function (e) {
		e.preventDefault();

		const data = {
			name: $("#profileName").val(),
			email: $("#profileEmail").val(),
			dob: $("#profileDob").val(),
		};

		$.ajax({
			url: `${API_BASE_URL}/users/${currentUser.id}`,
			method: "PUT",
			contentType: "application/json",
			data: JSON.stringify(data),
			success: function (response) {
				showAlert("Profile updated successfully!");
				currentUser = { ...currentUser, ...data };
				saveUserSession(currentUser);
			},
			error: handleError,
		});
	});

	// Logout button click
	$("#logoutBtn").on("click", function () {
		$.ajax({
			url: `${API_BASE_URL}/logout`,
			method: "POST",
			contentType: "application/json",
			success: function () {
				clearUserSession();
				showAlert("Logged out successfully!");
			},
			error: handleError,
		});
	});

	// Function to show profile section
	function showProfile() {
		$("#profileName").val(currentUser.name);
		$("#profileEmail").val(currentUser.email);
		$("#profileDob").val(currentUser.dob);
		$("#profileSection").show();
	}

	// Check if user is already logged in
	function checkLoginStatus() {
		if (loadUserSession()) {
			// Verify session with server
			$.ajax({
				url: `${API_BASE_URL}/session`,
				method: "GET",
				error: function () {
					// If server rejects the session, clear it
					clearUserSession();
				},
			});
		} else {
			updateUIState();
		}
	}

	// Initial check for login status
	checkLoginStatus();
});
