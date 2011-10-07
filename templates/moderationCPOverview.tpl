{include file="documentHeader"}
<head>
	<title>{lang}wcf.moderation.overview{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

<div id="main">
	
	{include file="moderationCPHeader"}
	
	<div class="border tabMenuContent">
		<div class="container-1">
			<h3 class="subHeadline">{lang}wcf.moderation.overview{/lang}</h3>
			
			<ul class="dataList">
				{foreach from=$moderationTypes item=moderationType}
					{assign var=outstandingModerations value=$moderationType->getOutstandingModerations()}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<img src="{icon}{@$moderationType->getIcon()}M.png{/icon}" alt="" style="width: 24px;" />
						</div>
						
						<div class="containerContent">
							<h4><a href="{$moderationType->getURL()}{@SID_ARG_2ND}"{if $outstandingModerations} class="new"{/if}>{lang}wcf.moderation.{@$moderationType->getName()}{/lang}</a></h4>
							<p class="firstPost smallFont light">{lang}wcf.moderation.outstandingModerations{/lang}</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>

</div>

{include file='footer' sandbox=false}

</body>
</html>