<?php 
	// set basic variables for layout
	$_PAGE_TITLE = Utilities::PAGE_HOME; 
?>

<?php include "layout/screen_header_start.php"; ?>
<?php include "layout/screen_layout_start.php"; ?>

<!-- js inclusions -->
<script type="text/javascript" src="js/home.js"></script>
				
<!-- main content -->
<div class="content_column" id="column_middle">
	<div class="content_column_wrapper" id="column_wrapper_middle">

		<div id="profile_section">
			<a id="profile_pic"></a>
			<h1 class="profile_name"><?php echo $_CUR_USER->getDisplayName();?></h1>
			<a class="user_info">Email: <?php echo $_CUR_USER->getEmail();?></a>
			<a class="user_info">Phone: <?php echo $_CUR_USER->getPhone();?></a>
		</div>
		
		<div id="modification_bar" onclick="addNewActivity()">
			<a>Add new activity</a>
		</div>
		
		<?php $list = $_CUR_USER->getDefaultActivityList(); ?>
		<?php $activities = $list->getActiveOrCompletedActivityAssociations(); ?>
		
		<?php if (count($activities) == 0):?>
			<p id="no_activity_msg">You currently have no activity. Add one to get started.</p>
		<?php endif;?>
	
		<ul class="activity_list" id="default_activity_list">
			<li id="adding_activity">
				<input type="text" class="new_activity_input" placeholder="Activity title" />
				<input type="text" class="new_activity_input" placeholder="Tag Categories" />
				<textarea class="new_activity_text" placeholder="Description"></textarea>
				<span style="float:right"><input type="button" class="new_activity_buttons" value="Save" />
				<input type="button" class="new_activity_buttons" value="Cancel" onclick="cancelNewActivity()" /></span>
			</li>
		
			<?php for ($i=0; $i<count($activities); $i++):?>
				<?php $actAssoc = $activities[$i];?>
				<li>
					<h2 class="activity_title"><?php echo $actAssoc->getAlias();?></h2>
					<a class="details" id="activity_drop_<?php echo $i?>" onclick="displayDetailsBox(event, 'activity_edit_<?php echo $i?>')"></a>
					<div class="details_box" id="activity_edit_<?php echo $i?>">
						<a class="checked detail_box_item">Mark as completed</a>
						<a class="edit detail_box_item">Edit</a>
						<a class="delete detail_box_item">Delete</a>
					</div>
					
					<?php $cats = $actAssoc->getActivityCategories();?>
					<span class="category_info">
						<?php for ($j=0; $j<count($cats); $j++):?>
							[<?php echo $cats[$j]->getCategory()->getName();?>]
						<?php endfor;?>
					</span>
					
					<a class="datetime">Added: <?php echo $actAssoc->getDateAdded()->format('Y-m-d H:i:s');?></a>
					<p class="post_body">
						<?php echo $actAssoc->getDescription();?>
					</p>
					
					<div class="interest_info">
						<a class="interest_tally">
							<?php echo ActivityListAssociationQuery::countInterestedFriends($_CUR_USER->getPrimaryKey(), $actAssoc->getActivityId());?>
							interests
						</a>
					</div>
					<div class="expand">...</div>
				</li>
			<?php endfor;?>
		</ul>
		
	</div>
	
	<?php include "layout/screen_footer.php"?>
</div>

<?php include "layout/screen_layout_end.php"; ?>

<?php include "newsfeed.php"; ?>

<?php include "layout/screen_header_end.php"; ?>
