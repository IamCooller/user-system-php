/**
 * Dynamic configuration for JavaScript
 * This script determines the base path from the current URL
 */

// Get the folder name from the current URL path
function getBasePath() {
	// Get the pathname (e.g., "/task/index.html" or "/user-system-php/index.html")
	const pathname = window.location.pathname;

	// Extract the first directory segment from the pathname
	const match = pathname.match(/^\/([^/]+)/);

	// If we found a match, use it as the base folder, otherwise use empty string
	const folder = match && match[1] ? match[1] : "";

	return "/" + folder + "/";
}

// Configuration object
const appConfig = {
	basePath: getBasePath(),
	apiPath: getBasePath() + "api",
	appName: "User Management System",
};

// For debugging
console.log("App config loaded:", appConfig);
