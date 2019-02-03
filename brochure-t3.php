<?php
	//$all_features = array_merge($basic_info[APPEND_TRUE], $interior_features[APPEND_TRUE], $exterior_features[APPEND_TRUE]);

	include("IsValid.class.php");
	// include("AccountsTable.class.php");
	//$all_features = array_merge($basic_info[APPEND_TRUE], $interior_features[APPEND_TRUE], $exterior_features[APPEND_TRUE]);
	$all_features = unserialize(file_get_contents("all_features.serialized"));
	$myrow = unserialize(file_get_contents("myrow.serialized"));
	$primary_agent = unserialize(file_get_contents("primary_agent.serialized"));
	$photo_data = unserialize(file_get_contents("photo_data.serialized"));
	$primary_photo_url = "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg";
	$display_address = "123 Harborton Road";
	$profile_photo = "https://www.nystatemls.com/images/ACTIMG/A176/55676/p55676.jpg";
	$display_price = "$235,000";
	$status_text = "Active for Sale";
	$full_address = $display_address.', '.$myrow['city'].', '.$myrow['state'].' '.$myrow['zip'];
	
	$bar_font_size = '26px';
	if (strlen($display_price.' '.$full_address) > 54)
	{
		$bar_font_size = '18px';
	}
?>
<style>
	#primary_photo {
		width: 510px;
		height: 300px;
		background-size: cover;
	}
	#agent_detail {
		width: 170px;
		background-color: #EEE;
	}
	#agent_photo {
		margin-bottom: 20px;
		width: 150px;
		height: 150px;
		border-radius: 100%;
		background-size: cover;
	}
	#header {
		background-color: #28A7AD;
		color: #FFF;
		font-weight: bold;
		padding: 10px;
	}
	#photo_left,
	#photo_right,
	#photo_center {
		width: 225px;
		height: 150px;
		background-size: cover;
	}
	#agent_desc {
		padding:10px;
		height: 250px;
	}
	#agent_desc_text {
		padding: 4px 0 0 0;
		height: 110px;
	}
</style>
<div data-template-id="t3">
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 680px; border: 0px solid #383838;">
<tr>
	<td>
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td style="width: 510px;">
					<div id="primary_photo" data-editable="image" style="background-image:url(<?= $primary_photo_url ?>);">
					</div>
				</td>
				<td id="agent_detail" data-editable="background-color">
					<div style="text-align: center;">
						<?php if (IsValid::Url($profile_photo)) : ?>
						<div style="padding: 10px;">
						<div id="agent_photo" style="background-image:url(<?= $profile_photo ?>);">
						</div>
						<?php endif ?>
						<?php if (!empty($logo_url)) : ?>
						<div data-editable="image" style="max-width: 150px; max-height: 150px; background-image:url(<?= $logo_url ?>); background-size: cover">
						</div>
						<?php endif // logo url ?>

						<?php $agent_name = "Guy Rombaut"; ?>
						<div><strong style="font-size: 12pt;"><?= $agent_name; ?></strong></div>
						<div><span style="font-size: 13pt;"><?= $primary_agent['company']; ?></span></div>

						<?php if (!empty($primary_agent['bphone'])) { echo '<div>' . $primary_agent['bphone'] . '</div>'; } ?>
						<?php if (!empty($primary_agent['bcell'])) { echo '<div>' . $primary_agent['bcell'] . '</div>'; } ?>
					</div>
				</td>
			</tr>
		</table>
		<div id="header" data-editable="background-color" style="font-size: <?= $bar_font_size ?>;">
			<?= $display_price ?>
			<span style="float: right;"><?= $full_address ?></span>
		</div>
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td style="width: 225px;">
					<?php if (IsValid::Url($photo_data['p2']['url_small'])) : ?>
					<div id="photo_left" data-editable="image" style="background-image:url(<?= $photo_data['p2']['url_small'] ?>);">
					</div>
					<?php endif ?>
				</td>
				<td style="width: 225px;">
					<?php if (IsValid::Url($photo_data['p3']['url_small'])) : ?>
					<div id="photo_center" data-editable="image" style="background-image:url(<?= $photo_data['p3']['url_small'] ?>);">
					</div>
					<?php endif ?>
				</td>
				<td style="width: 225px;">
					<?php if (IsValid::Url($photo_data['p4']['url_small'])) : ?>
					<div id="photo_right" data-editable="image" style="background-image:url(<?= $photo_data['p4']['url_small'] ?>);">
					</div>
					<?php endif ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td id="agent_desc" data-editable="background-color font-color">
		<?php if (!empty($all_features)) : ?>
		<?php $num_elements = count($all_features); ?>
		<table id="BasicDetails" cellpadding="4" cellspacing="0" border="0" style="margin: 0 auto;">
			<tr>
			<?php for ($i = 1; $i <= $num_elements; $i++) : ?>
				<td style="width: 160px;"><?= $all_features[$i-1] ?></td>
				<?php if ((($i % 4) == 0) && ($i != $num_elements)) : ?>
				</tr><tr>
				<?php endif ?>
			<?php endfor ?>
			</tr>
		</table>
		<?php endif // features ?>
		
		<div id="agent_desc_text" data-editable="text" data-character-limit=500>
			<?php
			$unbranded_desc = '';
			if (strlen($myrow['agent_desc']) > 500)
			{
				$unbranded_desc = substr($myrow['agent_desc'], 0, 500);
				$dot_pos = strrpos($unbranded_desc, '.');
				$unbranded_desc = substr($unbranded_desc, 0, $dot_pos+1);
			}
			else
			{
				$unbranded_desc = $myrow['agent_desc'];
			}
			?>
			<?= $unbranded_desc ?>
		</div>
	</td>
</tr>
<tr>
	<td colspan="2" style="text-align: right; color: #888; font-size: 10pt; padding-top: 6px;">
		https://www.mystatemls.com/property/<?= $myrow['mlsid'] ?>/<br />
		My State MLS &middot; State Listings, Inc.
	</td>
</tr>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>

<link rel="stylesheet" href="css/colorpicker/colorpicker.css" type="text/css" />
<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker/layout.css" />
<script type="text/javascript" src="js/colorpicker/colorpicker.js"></script>
<script type="text/javascript" src="js/colorpicker/eye.js"></script>
<script type="text/javascript" src="js/colorpicker/utils.js"></script>

<link rel="stylesheet" href="./plugin.css">
<script src="./plugin.js"></script>