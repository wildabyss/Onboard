<div id="haze"></div>
<div id="global_wrapper">
	<div id="header">
		<a href="/" id="header_logo"></a>
		
		<?php $_SEARCH_QUERY = (isset($_REQUEST["query"])) ? trim($_REQUEST["query"]) : "" ?>
		<form method="get" name="globalsearch" action="browse">
			<input class="search" id="global_search" placeholder="search" value="<?php echo $_SEARCH_QUERY ?>" name="query" />
		</form>
		<a href="/logout" id="header_options"><?php if (isset($_SESSION['current_user'])):?>Sign Out<?php else:?>Sign In<?php endif?></a>
	</div>
	
	<div id="content_wrapper">
		<!-- navigation -->
		<div class="content_column" id="column_left">
			<div class="content_column_wrapper" id="column_wrapper_left">
				<ul>
					<li class="button_middle"><a id="button_list" href="/<?php if (isset($_SESSION['current_user'])):?>id/<?php echo $_SESSION['current_user']->getId()?><?php endif?>"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_MY_ACTIVITIES) echo "selected"?>">My Activities</a></li>
					<li class="button_middle"><a id="button_recent" href="/recent"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_RECENT) echo "selected"?>">News Feed</a></li>
					<li class="button_middle"><a id="button_community" href="/community"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_COMMUNITY) echo "selected"?>">Community</a></li>
					<li><a id="button_browse" href="/browse"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_BROWSE) echo "selected"?>">Search</a></li>
				</ul>
			</div>
		</div>