<?php 
	if (isset($_SESSION['current_user']))
		$curUser = $_SESSION['current_user'];
?>

<li class="activity" id="activity_section_<?php echo $_ACT_OBJ_VIEW->getId()?>" 
	onclick="expandActivity('<?php echo $_ACT_OBJ_VIEW->getId()?>', '<?php echo $curUser->getId()?>')">
	<?php include "activity_basic_view.php"?>
</li>