<?php if (count($_INTERESTED_FRIENDS)>0):?>
	<div id="interest_details_<?php echo $_ACT_OBJ_VIEW->getId()?>">
		<ul class="interest_details">
			<?php foreach ($_INTERESTED_FRIENDS as $friend):?>
				<li onclick="window.location.href = 'community?id=<?php echo $friend->getId()?>';">
					<a class="interested_friends_summary" id="interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>"><?php echo $friend->getDisplayName()?></a>
					<a class="feed_profile_pic" 
						onmouseover="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').show('fast')" 
						onmouseout="$('#interested_friends_hint_<?php echo $_ACT_OBJ_VIEW->getId()?>_<?php echo $friend->getId()?>').hide()"
						style="background-image: url(../profile_pic_cache/<?php echo $friend->getId()?>_small.jpg)"></a>
				</li>
			<?php endforeach?>
		</ul>
	</div>
<?php endif?>