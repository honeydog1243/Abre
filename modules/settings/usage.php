<?php

	/*
	* Copyright (C) 2016-2019 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

    //Required configuration files

	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require(dirname(__FILE__) . '/../../core/abre_version.php');
	//Google API PHP library files
	require_once(dirname(__FILE__).'/../../core/google/vendor/autoload.php');
	$portal_root = getConfigPortalRoot();


	if(admin()){

		//Create Client request to access Google API
		$client = new Google_Client();
		$client->setApplicationName("Abre");
		$client_id = getConfigGoogleClientID();
		$client->setClientId($client_id);
		$client_secret = getConfigGoogleClientSecret();
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($portal_root.'/modules/settings/usage.php');
		$simple_api_key = getConfigGoogleApiKey();
		$client->setDeveloperKey($simple_api_key);
		$client->setAccessType("offline");
		$client->setApprovalPrompt("auto");
		$client->addScope(array('https://www.googleapis.com/auth/analytics.readonly'));
		$client->setIncludeGrantedScopes(true);

		$authenticated = false;
		$viewId = getSiteAnalyticsViewId();

		//Set Access Token
		if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
			$client->setAccessToken($_SESSION['access_token']);
		}

		if(isset($_GET["code"])){
			$client->authenticate($_GET['code']);
			$_SESSION['access_token'] = $client->getAccessToken();
			$tokenToStore = json_encode($_SESSION["access_token"]);

			$stmt = $db->stmt_init();
			$sql = "UPDATE users SET refresh_token = ? WHERE email = ? AND siteID = ?";
			$stmt->prepare($sql);
			$stmt->bind_param("ssi", $tokenToStore, $_SESSION["useremail"], $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
			$db->close();

			header('Location: '. $portal_root.'/#settings/usage');
		}else{
			if($client->isAccessTokenExpired()){
				$newAccessTokenArray = $client->refreshToken($_SESSION['access_token']['refresh_token']);
				$_SESSION['access_token']['access_token'] = $newAccessTokenArray['access_token'];
				$client->setAccessToken($_SESSION["access_token"]);
			}
			$authToken = $_SESSION["access_token"]["access_token"];
			$scopes = getCurrentGoogleScopes($authToken);
			if(strpos($scopes, 'https://www.googleapis.com/auth/analytics.readonly') === false){
				$authUrl = $client->createAuthUrl();
			}else{
				$AUTH_TOKEN = $authToken;
				$authenticated = true;
			}
		}

		//Usage
		echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
		echo "<div class='page'>";

			//Page Title
			echo "<div class='row'>";
				echo "<div class='input-field col s8' style='margin-top:0px;'>";
					echo "<h4>Usage Analytics</h4>";
					if(!$authenticated){
						echo "<p>To access Google Analytics, click below to give Google permission to access your usage.</p>";
						echo "<a class='waves-effect btn-flat white-text' style='background-color:".getSiteColor().";' href='$authUrl'>Enable Google Analytics</a>";
					}
				echo "</div>";
				if($authenticated && $viewId != ""){
					echo "<div class='input-field col s4'>";
						echo "<div id='active-users-container' style='float:right;'></div>";
					echo "</div>";
			echo "</div>";
			echo "<div id='googleCharts'>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
							echo "<h5 id='chart1Title' class='center-align'> Number of Users (Past 30 Days)</h5>";
							echo "<div class='center-align' id='chart-1-container'><div id='loader1' class='mdl-spinner mdl-js-spinner is-active'></div></div>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col l6 s12'>";
							echo "<h5 id='chart2Title' class='center-align'>Page Views by Device Type (Past 30 Days)</h5>";
							echo "<div class='center-align' id='chart-2-container'><div id='loader2' class='mdl-spinner mdl-js-spinner is-active'></div></div>";
						echo "</div>";
						echo "<div class='input-field col l6 s12'>";
							echo "<h5 id='chart4Title' class='center-align'> Page Views (Past 30 Days)</h5>";
							echo "<div class='center-align' id='chart-4-container'><div id='loader4' class='mdl-spinner mdl-js-spinner is-active'></div></div>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
					echo "<div class='input-field col l6 s12'>";
						echo "<h5 id='chart3Title' class='center-align'> Users by Time of Day (Past 30 Days)</h5>";
						echo "<div class='center-align' id='chart-3-container'><div id='loader3' class='mdl-spinner mdl-js-spinner is-active'></div></div>";
					echo "</div>";
					echo "</div>";
				}elseif($authenticated && $viewId == ""){
					echo "</div>";
					echo "<h6>You are authenticated, but have not set up your analytics ID in the settings page. Please visit <a href='#settings' style='color: ".getSiteColor().";'>the settings page</a> to provide this information.</h6>";
				}
			echo "</div>";
			echo "<h6 id='viewIdError' style='display:none'>You do not have permission to view the entered Analytics View ID. Please enter a View ID you have permission to access.</h6>";

		echo "</div>";
		echo "</div>";

	}

?>

<?php if($authenticated && $viewId != ""){ ?>
	<script>
		(function (w, d, s, g, js, fs) {
		if ($('#googleCache').length > 0) return;
		g = w.gapi || (w.gapi = {}); g.analytics = { q: [], ready: function (f) { this.q.push(f); } };
		js = d.createElement(s); fs = d.getElementsByTagName(s)[0];
		js.src = 'https://apis.google.com/js/platform.js';
		js.id = "googleCache";
		fs.parentNode.insertBefore(js, fs); js.onload = function () { g.load('analytics'); };
		}(window, document, 'script'));
	</script>

	<script src="./modules/settings/javascript/active-users.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<script>


	gapi.analytics.ready(function() {

		/**
		 * Authorize the user immediately if the user has already granted access.
		 * If no access has been created, render an authorize button inside the
		 * element with the ID "embed-api-auth-container".
		 */
		gapi.analytics.auth.authorize({
			'serverAuth': {
				'access_token': "<?php echo $AUTH_TOKEN;  ?>"
			}
		});

		if(gapi.analytics.auth.isAuthorized()){

			/**
			 * Create a new ActiveUsers instance to be rendered inside of an
			 * element with the id "active-users-container" and poll for changes every
			 * five seconds.
			 */
			var activeUsers = new gapi.analytics.ext.ActiveUsers({
				container: 'active-users-container',
				ids: 'ga:' + "<?php echo getSiteAnalyticsViewId(); ?>",
				pollingInterval: 30
			});
			activeUsers.execute();

			/**
			 * Creates a new DataChart instance showing sessions over the past 30 days.
			 * It will be rendered inside an element with the id "chart-1-container".
			 */
			var dataChart1 = new gapi.analytics.googleCharts.DataChart({
				query: {
					'ids': 'ga:' + "<?php echo getSiteAnalyticsViewId(); ?>", // <-- Replace with the ids value for your view.
					'start-date': '30daysAgo',
					'end-date': 'yesterday',
					'metrics': 'ga:users',
					'dimensions': 'ga:date'
				},
				chart: {
					'container': 'chart-1-container',
					'type': 'LINE',
					'options': {
						'width': '100%',
						'vAxis': {
									'title': 'Hello',
						},
						'hAxis': {
									'title': 'Date',
						}
					}
				}
			});
			dataChart1.execute();

			dataChart1.on('error', function(response){
				$("#googleCharts").hide();
				$("#active-users-container").hide();
				$("#viewIdError").show();
			});

			dataChart1.on('success', function(response) {
				$("#loader1").hide();
			});

			/**
			 * Creates a new DataChart instance showing top 5 most popular demos/tools
			 * amongst returning users only.
			 * It will be rendered inside an element with the id "chart-3-container".
			 */
			var dataChart2 = new gapi.analytics.googleCharts.DataChart({
				query: {
					'ids': 'ga:' + "<?php echo getSiteAnalyticsViewId(); ?>", // <-- Replace with the ids value for your view.
					'start-date': '30daysAgo',
					'end-date': 'yesterday',
					'dimensions': 'ga:deviceCategory',
					'metrics': 'ga:pageviews',
					'sort': '-ga:pageviews',
				},
				chart: {
					'container': 'chart-2-container',
					'type': 'PIE',
					'options': {
						'width': '100%',
						'pieHole': 4/9,
					}
				}
			});
			dataChart2.execute();

			dataChart2.on('error', function(response){
				$("#googleCharts").hide();
				$("#active-users-container").hide();
				$("#viewIdError").show();
			});

			dataChart2.on('success', function(response) {
				$("#loader2").hide();
			});

			/**
			 * Creates a new DataChart instance showing sessions over the past 30 days.
			 * It will be rendered inside an element with the id "chart-1-container".
			 */
			var dataChart3 = new gapi.analytics.googleCharts.DataChart({
				query: {
					'ids': 'ga:' + "<?php echo getSiteAnalyticsViewId(); ?>", // <-- Replace with the ids value for your view.
					'start-date': '30daysAgo',
					'end-date': 'yesterday',
					'metrics': 'ga:users',
					'dimensions': 'ga:hour',
					'filters': 'ga:users>0'
				},
				chart: {
					'container': 'chart-3-container',
					'type': 'COLUMN',
					'options': {
						'vAxis': {
									'title': 'Number of Users',
						},
						'hAxis': {
									'title': 'Time of Day',
						}
					}
				}
			});
			dataChart3.execute();

			dataChart3.on('error', function(response){
				$("#googleCharts").hide();
				$("#active-users-container").hide();
				$("#viewIdError").show();
			});

			dataChart3.on('success', function(response) {
				$("#loader3").hide();
			});

			var dataChart4 = new gapi.analytics.googleCharts.DataChart({
				query: {
					'ids': 'ga:' + "<?php echo getSiteAnalyticsViewId(); ?>", // <-- Replace with the ids value for your view.
					'start-date': '30daysAgo',
					'end-date': 'yesterday',
					'dimensions': 'ga:pagePathLevel1',
					'metrics': 'ga:pageviews',
					'filters': 'ga:pageviews>=5',
					'sort': '-ga:pageviews',
				},
				chart: {
					'container': 'chart-4-container',
					'type': 'PIE',
					'options': {
						'width': '100%',
						'pieHole': 4/9,
					}
				}
			});
			dataChart4.execute();

			dataChart4.on('error', function(response){
				$("#googleCharts").hide();
				$("#active-users-container").hide();
				$("#viewIdError").show();
			});

			dataChart4.on('success', function(response) {
				$("#loader4").hide();
			});
		}

	});

	</script>

<?php } ?>