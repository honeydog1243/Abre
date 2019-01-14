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
  require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
?>

<script>
	$(function() {
		$(".like-icon").unbind().click(function() {
			event.preventDefault();

			var streamTitle = $(this).data('title');
			var streamUrl = $(this).data('url');
			var streamImage = $(this).data('image');
			var excerpt = $(this).data('excerpt');
			var elementCount = $(this).next();
			var elementIcon = $(this);

			$.post("modules/stream/view_stream_like.php", { 
				url: streamUrl, 
				title: streamTitle, 
				image: streamImage, 
				excerpt: excerpt 
			}).done(function(data) {
				$.post("modules/<?php echo basename(__DIR__); ?>/action_update_card.php", {
					url: streamUrl, 
					type: "like"
				}).done(function(data) {
					if (data.count == 0) {
						elementIcon.addClass("mdl-color-text--grey-600");
						elementCount.removeClass("mdl-color-text--red");
						elementCount.addClass("mdl-color-text--grey-600");
						elementCount.html(data.count);
					} else {
						if(data.currentusercount == 0) {
							elementIcon.addClass("mdl-color-text--grey-600");
							elementIcon.removeClass("mdl-color-text--red");
							elementCount.addClass("mdl-color-text--grey-600");
							elementCount.removeClass("mdl-color-text--red");
							elementCount.html(data.count);
						} else {
							elementIcon.removeClass("mdl-color-text--grey-600");
							elementIcon.addClass("mdl-color-text--red");
							elementCount.removeClass("mdl-color-text--grey-600");
							elementCount.addClass("mdl-color-text--red");
							elementCount.html(data.count);
						}
					}
				});
			});
    });
    
    $(".share-info").unbind().click(function(){
      event.preventDefault();
      var Article_URL = $(this).data('url');
      Article_URL = atob(Article_URL);
      $(".modal-content #share_url").val(Article_URL);
      $('#sharecard').openModal({ in_duration: 0, out_duration: 0 });
    });

		$('.modal-addstreamcomment').leanModal({
			in_duration: 0,
			out_duration: 0,
			ready: function() {
				$("#streamComment").focus();
			}
		});

		//Fill comment modal
		$(document).off().on("click", ".modal-addstreamcomment", function (event) {
			event.preventDefault();

			$("#commentloader").show();
			$("#streamComments").empty();
			$(".modal-content #streamTitle").text('');
			$(".modal-content #streamTitle").val('');
			$(".modal-content #streamUrl").val('');
			$(".modal-content #commentID").val('');
			$(".modal-content #streamImage").val('');
			$(".modal-content #redirect").val('');
			$(".modal-content #streamExcerpt").val('');
			$(".modal-content #streamExcerptDisplay").html('');

			var type = $(this).data('type');
			if (type == "custom") {
				$("#readStreamTitle").text("Announcement");
			} else {
				$("#readStreamTitle").text("News");
			}
			var streamTitle = $(this).data('title');
			$(".modal-content #streamTitle").text(streamTitle);
			$(".modal-content #streamTitleValue").val(streamTitle);
			var streamUrl = $(this).data('url');
			$(".modal-content #streamUrl").val(streamUrl);
			var commentID = $(this).data('commenticonid');
			$(".modal-content #commentID").val(commentID);
			var streamImage = $(this).data('image');
			$(".modal-content #streamImage").val(streamImage);
			var redirect = $(this).data('redirect');
			$(".modal-content #redirect").val(redirect);
			var excerpt = $(this).data('excerpt');
			$(".modal-content #streamExcerpt").val(excerpt);
			$(".modal-content #streamExcerptDisplay").html(excerpt);
			if (streamImage != "") {
				$(".modal-content #streamPhotoHolder").show();
				$(".modal-content #streamPhoto").addClass("mdl-card__media");
				$(".modal-content #streamPhoto").attr('style', 'height:300px;');
				$(".modal-content #streamPhoto").css("background-image", "url("+atob(streamImage)+")");
			} else {
				$(".modal-content #streamPhotoHolder").hide();
				$(".modal-content #streamPhoto").removeAttr('style');
				$(".modal-content #streamPhoto").removeClass("mdl-card__media");
			}
			if (type == "custom") {
				$(".modal-content #streamLink").attr("href", "");
				$(".modal-content #streamLink").hide();
			} else {
				$(".modal-content #streamLink").show();
				$(".modal-content #streamLink").attr("href", atob(streamUrl));
			}

			$("#streamComments" ).load( "modules/stream/view_comment_list.php?url="+streamUrl, function() {
				$("#commentloader").hide();
			});

			$('.modal-content').animate({
        scrollTop: $("#streamComments").offset().top
      }, 0);
		});

		$(".read-stream").unbind().click(function(event) {
			event.preventDefault();

			$("#commentloader").show();
			$("#streamComments").empty();
			$(".modal-content #streamTitle").text('');
			$(".modal-content #streamTitle").val('');
			$(".modal-content #streamUrl").val('');
			$(".modal-content #commentID").val('');
			$(".modal-content #streamImage").val('');
			$(".modal-content #redirect").val('');
			$(".modal-content #streamExcerpt").val('');
			$(".modal-content #streamExcerptDisplay").html('');

			var card = $(this).closest('.stream-post');

			var url = card.data('url');
			$(".modal-content #streamUrl").val(url);

			if (card.data('type') == "custom") {
				$("#readStreamTitle").text("Announcement");
				$(".modal-content #streamLink").attr("href", "");
				$(".modal-content #streamLink").hide();
			} else {
				$("#readStreamTitle").text("News");
				$(".modal-content #streamLink").show();
				$(".modal-content #streamLink").attr("href", atob(url));
			}
			$(".modal-content #streamTitle").text(card.data('title'));
			$(".modal-content #streamTitleValue").val(card.data('title'));
			$(".modal-content #commentID").val(card.data('commenticonid'));
			$(".modal-content #redirect").val(card.data('redirect'));
			$(".modal-content #streamExcerpt").val(card.data('excerpt'));
			$(".modal-content #streamExcerptDisplay").html(card.data('excerpt'));
			
			var image = card.data('image');
			$(".modal-content #streamImage").val(image);
			if (image != "") {
				$(".modal-content #streamPhotoHolder").show();
				$(".modal-content #streamPhoto").addClass("mdl-card__media");
				$(".modal-content #streamPhoto").attr('style', 'height:300px;');
				$(".modal-content #streamPhoto").css("background-image", "url("+atob(image)+")");
			} else {
				$(".modal-content #streamPhotoHolder").hide();
				$(".modal-content #streamPhoto").removeAttr('style');
				$(".modal-content #streamPhoto").removeClass("mdl-card__media");
			}

<?php if($_SESSION['usertype'] == 'staff') { ?>
				$("#streamComments" ).load("modules/stream/view_comment_list.php?url="+url, function() {
					$("#commentloader").hide();
				});
<?php } else { ?>
				$("#commentloader").hide();
<?php } ?>

			if (card.data('type') == "custom") {
				var id = card.data('id');
				$.ajax({
					type: 'POST',
					url: 'modules/stream/action_view_post.php',
					data: { id }
				});
			}

			$('#addstreamcomment').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function(){}
			});

			$('.modal-content').animate({ scrollTop: 0 }, 0);
		});

		//edit custom post
		$(".editpost").unbind().click(function (event) {
			event.preventDefault();
			var id = $(this).data('id');
			$("#post_id").val(id);

			$("#post_header").text("Edit Announcement");

			var streamCategory = $(this).data('feedtitle');
			$("#post_stream").val(streamCategory);

			var streamTitle = $(this).data('title');
			$("#post_title").val(streamTitle);

			var streamExcerpt = $(this).data('excerpt');
			tinymce.get("post_content").setContent(streamExcerpt);

			var streamImage = atob($(this).data('image'));
			if (streamImage != "") {
				$('#post_image').attr('src', streamImage);
				$("#post_image").show();
			} else {
				$('#post_image').attr('src', '');
				$("#post_image").hide();
			}

			$("#custompostdeletebutton").show();
			$("#custompostsavebutton").show();
			$("#custompostbutton").hide();

			$('#streampost').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function() {
					$('.modal-content').animate({ scrollTop: 0}, 0);
					$("select").material_select();
				}
			});
		});

		$("#custompostdeletebutton").unbind().click(function (event) {
			event.preventDefault();
			var id = $("#post_id").val();
			var result = confirm("Are you sure you want to remove this post?");
			if (result) {
				$('#custompostdeletebutton').html("Deleting...");
				//Make the post request
				$.ajax({
					type: 'POST',
					url: 'modules/stream/action_remove_announcement.php',
					data: { id: id }
				}).done(function(response) {
					$('#custompostdeletebutton').html("Delete");
					$('#streampost').closeModal({ in_duration: 0, out_duration: 0, });
					$.get('modules/stream/view_stream_announcements.php?StreamStartResult=0&StreamEndResult=24', function(results) {
						$('#showmorestream').hide();
						$('#streamcards').html(results);
						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response.message };
						notification.MaterialSnackbar.showSnackbar(data);
					});
				});
			}
		});
	});
</script>
