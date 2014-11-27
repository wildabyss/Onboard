<div class="content_column" id="column_right">
	<div class="content_column_wrapper" id="column_wrapper_right">
		<?php $recentActivityAssocs = ActivityListAssociationQuery::getRecentActivityListAssociations($_CUR_USER->getId(), 10)?>
		<?php foreach ($recentActivityAssocs as $recentActivityAssoc):?>
			<?php $assocUser = $recentActivityAssoc->getUser() ?>
			<span class="feed_block" onclick="window.location.href = 'community?id=<?php echo $assocUser->getId()?>';">
				<a class="feed_profile_pic"></a>
				<span class="feed_block_body">
					<a class="feed_title">
						<i><?php echo $assocUser->getDisplayName()?></i> has added <b><?php echo $recentActivityAssoc->getAlias()?></b>.
					</a>
					<a class="feed_datetime"><?php echo $recentActivityAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
				</span>
			</span>
		<?php endforeach;?>
	</div>
</div>
<script type="text/javascript">
	// prevent parent scrolling when mouse wheel scrolling through the news feed
	$(document).on('DOMMouseScroll mousewheel', '#column_wrapper_right', function(ev) {
		var $this = $(this),
			scrollTop = this.scrollTop,
			scrollHeight = this.scrollHeight,
			height = $this.height(),
			delta = (ev.type == 'DOMMouseScroll' ?
				ev.originalEvent.detail * -40 :
				ev.originalEvent.wheelDelta),
			up = delta > 0;

		var prevent = function() {
			ev.stopPropagation();
			ev.preventDefault();
			ev.returnValue = false;
			return false;
		}

		if (!up && -delta > scrollHeight - height - scrollTop) {
			// Scrolling down, but this will take us past the bottom.
			$this.scrollTop(scrollHeight);
			return prevent();
		} else if (up && delta > scrollTop) {
			// Scrolling up, but this will take us past the top.
			$this.scrollTop(0);
			return prevent();
		}
	});
</script>
