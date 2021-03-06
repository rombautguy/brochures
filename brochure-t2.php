<?php
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
?>
<style>
	#header {
		padding: 5px;
	}
	#primary_photo {
		width: 510px;
		height: 300px;
		background-size: cover;
	}
	#agent_desc {
		height:215px;
		padding:10px;
	}
	#agent_detail {
	}
	#agent_photo {
		margin-bottom: 20px;
		width: 150px;
		height: 150px;
		border-radius: 100%;
		background-size: cover;
	}
	#left_photo,
	#right_photo {
		width: 340px;
		height: 200px;
		background-size: cover;
	}
</style>
<div data-template-id="t2">
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 680px; border: 0px solid #383838;">
<tr>
	<td id="header" data-editable="background-color font-color">
		<div style="text-align: left; font-size: 18pt; font-weight: bold; margin: 0 0 4px 0;">
			<?= $display_address.', '.$myrow['city'].', '.$myrow['state'].' '.$myrow['zip']; ?>
			<span style="float: right; font-size: 14pt;">#<?= $myrow['mlsid'] ?></span>
		</div>
		<div style="font-size: 22pt;">
			<?= $display_price ?>
			<span style="float: right; font-size: 12pt;"><?= $status_text ?></span>
		</div>
	</td>
</tr>
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
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td style="width: 340px;">
					<?php if (IsValid::Url($photo_data['p2']['url_small'])) : ?>
					<div id="left_photo" data-editable="image" style="background-image:url(<?= $photo_data['p2']['url_small'] ?>);">
					</div>
					<?php endif ?>
				</td>
				<td style="width: 340px;">
					<?php if (IsValid::Url($photo_data['p3']['url_small'])) : ?>
					<div id="right_photo" data-editable="image" style="background-image:url(<?= $photo_data['p3']['url_small'] ?>);">
					</div>
					<?php endif ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td id="agent_desc" data-editable="background-color font-color">
		<div id="agent_desc_text" data-editable="text" style="height:110px" data-character-limit=500>
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
		
		<div>
		<?php if (!empty($all_features)) : ?>
		<?php $num_elements = count($all_features); ?>
		<table id="BasicDetails" cellpadding="4" cellspacing="0" border="0" style="margin: 0 auto;">
			<tr>
			<?php for ($i = 1; $i <= $num_elements; $i++) : ?>
				<td style="width: 160px;">&#9679; <?= $all_features[$i-1] ?></td>
				<?php if ((($i % 4) == 0) && ($i != $num_elements)) : ?>
				</tr><tr>
				<?php endif ?>
			<?php endfor ?>
			</tr>
		</table>
		<?php endif // basic ?>
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