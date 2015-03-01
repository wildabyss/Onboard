<?php $curUser = $_SESSION['current_user'];?>

<!-- bottom margin -->
<div id="bottom_margin"></div>
	
<!-- news feed -->
<div id="feed_wrapper">
	<ul id="feed_content">
		<?php $recentActivityAssocs = ActivityUserAssociationQuery::getRecentActivityUserAssociations($curUser->getId(), 20)?>
		<?php foreach ($recentActivityAssocs as $recentActivityAssoc):?>
			<?php $assocUser = $recentActivityAssoc->getUser() ?>
			<li class="feed_block" onclick="window.location.href = '/id/<?php echo $assocUser->getId()?>';">
				<a class="feed_profile_pic"></a>
				<span class="feed_block_body">
					<a class="feed_title">
						<i><?php echo htmlentities($assocUser->getDisplayName())?></i> has added <b><?php echo htmlentities($recentActivityAssoc->getAlias())?></b>.
					</a>
					<a class="feed_datetime"><?php echo $recentActivityAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
				</span>
			</li>
		<?php endforeach;?>
	</ul>
</div>
<script type="text/javascript">
	// prevent parent vertical scrolling
	$(document).on('DOMMouseScroll mousewheel', '#feed_content', function(ev) {
		var $this = $(this),
			scrollTop = this.scrollTop,
			scrollHeight = this.scrollHeight,
			height = $this.height(),
			delta = (ev.type == 'DOMMouseScroll' ?
				ev.originalEvent.detail * -40 :
				ev.originalEvent.wheelDelta),
			up = delta > 0;

		$("#feed_content")[0].scrollLeft -= (delta * 2.08);

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
