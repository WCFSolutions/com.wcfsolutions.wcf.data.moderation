<ul class="breadCrumbs">
	<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
</ul>

<div class="mainHeadline">
	<img src="{icon}moderationcpL.png{/icon}" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.moderation{/lang}</h2>
	</div>
</div>

{if $userMessages|isset}{@$userMessages}{/if}

<div id="moderationContent" class="tabMenu">
	<ul>
		{foreach from=$this->getModerationCPMenu()->getMenuItems('') item=item}
			<li{if $item.menuItem|in_array:$this->getModerationCPMenu()->getActiveMenuItems()} class="activeTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
		{/foreach}
	</ul>
</div>
<div class="subTabMenu">
	<div class="containerHead">
		{assign var=activeMenuItem value=$this->getModerationCPMenu()->getActiveMenuItem()}
		{if $activeMenuItem && $this->getModerationCPMenu()->getMenuItems($activeMenuItem)|count}
			<ul>
				{foreach from=$this->getModerationCPMenu()->getMenuItems($activeMenuItem) item=item}
					<li{if $item.menuItem|in_array:$this->getModerationCPMenu()->getActiveMenuItems()} class="activeSubTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
				{/foreach}
			</ul>
		{/if}
	</div>
</div>