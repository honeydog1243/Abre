<?php

  // When we rewrite the login flow, this should be refactored back into the header

  require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
  require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
  require_once(dirname(__FILE__) . '/../../core/abre_google_authentication.php');
  if($_SESSION['usertype'] == "staff" && $_SESSION['auth_service'] == "google"){
?>
    <!-- <script> // uncomment to see syntax highlighting. re-comment before submitting -->
    function loadGoogleApiPromise() {
      return new Promise(function (resolve, reject) {
        var developerKey = "<?= getConfigGoogleApiKey() ?>";
        var clientId = "<?= getConfigGoogleClientID() ?>";

        <?php $client->refreshToken($_SESSION['access_token']['refresh_token']); ?>
        var oauthToken = "<?= $client->getAccessToken()['access_token'] ?>";

        $.getScript("https://apis.google.com/js/api.js?onload=onApiLoad", function (data, textStatus, jqxhr) {
          gapi.load('picker', () => {
            resolve({
              showPicker: showPicker
            });
          });

          // Create and render a Picker object for picking user Photos.
          function showPicker(options, callback) {
            <?php $client -> refreshToken($_SESSION['access_token']['refresh_token']); ?>
              oauthToken = "<?= $client->getAccessToken()['access_token'] ?>";
            if (oauthToken) {
              var view = new google.picker.DocsView(google.picker.ViewId.DOCS)
                .setIncludeFolders(true)
                .setOwnedByMe(true);
              var view2 = new google.picker.DocsView(google.picker.ViewId.DOCS)
                .setIncludeFolders(true)
                .setEnableTeamDrives(true);
              view.setMode(google.picker.DocsViewMode.LIST);
              var builder = new google.picker.PickerBuilder()
                .addView(view)
                .addView(view2)
                .enableFeature(google.picker.Feature.SUPPORT_TEAM_DRIVES)
                .setOAuthToken(oauthToken)
                .setDeveloperKey(developerKey)
                .setCallback(callback);
              if(options && options.multiselect) {
                builder.enableFeature(google.picker.Feature.MULTISELECT_ENABLED);
              }
              var picker = builder.build();
              picker.setVisible(true);
            } else {
              throw "OAuth token not valid";
            }
            var elements = document.getElementsByClassName('picker-dialog');
            for (var i = 0; i < elements.length; i++) {
              elements[i].style.zIndex = "2000";
            }
          }
        });
      });
    }

    // MASSIVE GOTCHA: The google drive picker callback gets called when the picker opens as well as it closes.
    // For our promise, this means we only want to resolve when the user picks an item or cancels the dialog.
    // And we need to be careful about error checking (or we'll flag a dialog open as an error and reject on it).

    var googleApiPromise = null;
    function showPickerPromise(options) {
      return new Promise((resolve, reject) => {
        googleApiPromise = googleApiPromise || loadGoogleApiPromise();

        googleApiPromise.then((api) => api.showPicker(options, pickerCallback))
          .catch(reject);

        function pickerCallback(data) {
          if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
            resolve(data[google.picker.Response.DOCUMENTS]);
          } else if (data[google.picker.Response.ACTION] == google.picker.Action.CANCEL) {
            resolve([]);
          }
        }
      });
    }
<?php
  }
?>