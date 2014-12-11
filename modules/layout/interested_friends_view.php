<?php if (count($_INTERESTED_FRIENDS)>0):?>
	<div id="interest_details_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<ul class="interest_details">
			<?php foreach ($_INTERESTED_FRIENDS as $friend):?>
				<li onclick="window.location.href = 'community?id=<?php echo $friend->getId()?>';">
					<a class="feed_profile_pic" 
						style="background-image: url(../profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
				</li>
			<?php endforeach?>
		</ul>
	</div>
<?php endif?>