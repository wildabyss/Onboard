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